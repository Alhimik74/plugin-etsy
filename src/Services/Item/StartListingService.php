<?php

namespace Etsy\Services\Item;

use Plenty\Modules\Item\DataLayer\Models\Record;

use Etsy\Helper\ImageHelper;
use Etsy\Helper\SettingsHelper;
use Etsy\Api\Services\ListingService;
use Etsy\Api\Services\ListingImageService;
use Etsy\Helper\ItemHelper;
use Etsy\Api\Services\ListingTranslationService;
use Plenty\Plugin\Log\Loggable;

/**
 * Class StartListingService
 */
class StartListingService
{
	use Loggable;

	/**
	 * @var ItemHelper
	 */
	private $itemHelper;

	/**
	 * @var ListingService
	 */
	private $listingService;

	/**
	 * @var DeleteListingService
	 */
	private $deleteListingService;

	/**
	 * @var ListingImageService
	 */
	private $listingImageService;

	/**
	 * @var ListingTranslationService
	 */
	private $listingTranslationService;

	/**
	 * @var SettingsHelper
	 */
	private $settingsHelper;

	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * @param ItemHelper                $itemHelper
	 * @param ListingService            $listingService
	 * @param DeleteListingService      $deleteListingService
	 * @param ListingImageService       $listingImageService
	 * @param ListingTranslationService $listingTranslationService
	 * @param SettingsHelper            $settingsHelper
	 * @param ImageHelper               $imageHelper
	 */
	public function __construct(
		ItemHelper $itemHelper,
		ListingService $listingService,
		DeleteListingService $deleteListingService,
		ListingImageService $listingImageService,
		ListingTranslationService $listingTranslationService,
		SettingsHelper $settingsHelper,
		ImageHelper $imageHelper)
	{
		$this->itemHelper                = $itemHelper;
		$this->listingTranslationService = $listingTranslationService;
		$this->listingService            = $listingService;
		$this->deleteListingService      = $deleteListingService;
		$this->listingImageService       = $listingImageService;
		$this->settingsHelper            = $settingsHelper;
		$this->imageHelper               = $imageHelper;
	}

	/**
	 * Start the listing
	 *
	 * @param Record $record
	 */
	public function start(Record $record)
	{
		$listingId = $this->createListing($record);

		if(!is_null($listingId))
		{
			try
			{
				$this->addPictures($record, $listingId);

				$this->addTranslations($record, $listingId);

				$this->publish($listingId, $record->variationBase->id);

				$this->getLogger(__FUNCTION__)
				     ->addReference('etsyListingId', $listingId)
				     ->addReference('variationId', $record->variationBase->id)
				     ->info('Etsy::item.itemExportSuccess');
			}
			catch(\Exception $ex)
			{
				$this->deleteListingService->delete($listingId);

				$this->itemHelper->deleteSku($record->variationMarketStatus->id);

				$this->getLogger(__FUNCTION__)
				     ->addReference('variationId', $record->variationBase->id)
				     ->addReference('etsyListingId', $listingId)
				     ->warning('Etsy::item.skuRemovalSuccess', [
					     'sku' => $record->variationMarketStatus->sku
				     ]);

				$this->getLogger(__FUNCTION__)
					->setReferenceType('variationId')
					->setReferenceValue($record->variationBase->id)
					->error('Etsy::item.startListingError', $ex->getMessage());
			}
		}
		else
		{
			$this->getLogger(__FUNCTION__)
				->setReferenceType('variationId')
				->setReferenceValue($record->variationBase->id)
				->info('Etsy::item.startListingError');
		}
	}

	/**
	 * Create a listing base.
	 *
	 * @param Record $record
	 *
	 * @throws \Exception
	 * @return int
	 */
	private function createListing(Record $record)
	{
		$language    = $this->settingsHelper->getShopSettings('mainLanguage', 'de');

		$title       = trim(preg_replace('/\s+/', ' ', $this->itemHelper->getVariationWithAttributesName($record, $language)));
		$title       = str_replace(':', ' -', $title);
		$title = ltrim($title, ' +-!?');

		$description = html_entity_decode(strip_tags($record->itemDescription[ $language ]['description']));

		$data = [
			'state'                => 'draft',
			'title'                => $title,
			'description'          => $description,
			'quantity'             => $this->itemHelper->getStock($record),
			'price'                => number_format($record->variationRetailPrice->price, 2, '.', ''),
			'currency_code'        => $record->variationRetailPrice->currency,
			'shipping_template_id' => $this->itemHelper->getShippingTemplateId($record),
			'taxonomy_id'          => $this->itemHelper->getTaxonomyId($record),
			'should_auto_renew'    => 'true',
			'is_digital'           => 'false',
			'is_supply'            => 'false',

			// TODO
			// materials
			// shop_section_id
			// processing_min
			// processing_max

		];

		if($isSupply = $this->itemHelper->getProperty($record, 'is_supply', $language))
		{
			$data['is_supply'] = $isSupply;
		}

		if(strlen($record->itemDescription[ $language ]['keywords']))
		{
			$data['tags'] = $this->itemHelper->getTags($record, $language);
		}

		if($whoMade = $this->itemHelper->getProperty($record, 'who_made', 'en'))
		{
			$data['who_made'] = $whoMade;
		}

		if($whenMade = $this->itemHelper->getProperty($record, 'when_made', 'en'))
		{
			$data['when_made'] = $whenMade;
		}

		if($occasion = $this->itemHelper->getProperty($record, 'occasion', 'en'))
		{
			$data['occasion'] = $occasion;
		}

		if($recipient = $this->itemHelper->getProperty($record, 'recipient', 'en'))
		{
			$data['recipient'] = $recipient;
		}

		if($itemWeight = $record->variationBase->weightG)
		{
			$data['item_weight']       = $itemWeight;
			$data['item_weight_units'] = 'g';
		}

		if($itemHeight = $record->variationBase->heightMm)
		{
			$data['item_height']          = $itemHeight;
			$data['item_dimensions_unit'] = 'mm';
		}

		if($itemLength = $record->variationBase->lengthMm)
		{
			$data['item_length']          = $itemLength;
			$data['item_dimensions_unit'] = 'mm';
		}

		if($itemWidth = $record->variationBase->widthMm)
		{
			$data['item_width']           = $itemWidth;
			$data['item_dimensions_unit'] = 'mm';
		}

		$response = $this->listingService->createListing($this->settingsHelper->getShopSettings('mainLanguage', 'de'), $data);

		if(!isset($response['results']) || !is_array($response['results']))
		{
			throw new \Exception(is_string($response) ? $response : 'Failed to create listing.');
		}

		return (int) reset($response['results'])['listing_id'];
	}

	/**
	 * Add pictures to listing.
	 *
	 * @param Record $record
	 * @param int    $listingId
	 */
	private function addPictures(Record $record, $listingId)
	{
		$list = $this->itemHelper->getImageList($record->variationImageList['only_current_variation_images_and_generic_images']->toArray(), 'normal');

		$imageList = [];

		$list = array_reverse(array_slice($list, 0, 5));

		foreach($list as $id => $image)
		{
			$response = $this->listingImageService->uploadListingImage($listingId, $image);

			if(isset($response['results']) && isset($response['results'][0]) && isset($response['results'][0]['listing_image_id']))
			{
				$imageList[] = [
					'imageId'        => $id,
					'listingImageId' => $response['results'][0]['listing_image_id'],
					'listingId'      => $response['results'][0]['listing_id'],
					'imageUrl'       => $image,
				];

			}
		}

		if(count($imageList))
		{
			$this->imageHelper->save($record->variationBase->id, json_encode($imageList));
		}
	}

	/**
	 * Add translations to listing.
	 *
	 * @param Record $record
	 * @param int    $listingId
	 */
	private function addTranslations(Record $record, $listingId)
	{
		foreach($this->settingsHelper->getShopSettings('exportLanguages', [$this->settingsHelper->getShopSettings('mainLanguage', 'de')]) as $language)
		{
			if($language != $this->settingsHelper->getShopSettings('mainLanguage', 'de') && $record->itemDescription[ $language ]['name1'] && strip_tags($record->itemDescription[ $language ]['description']))
			{
				try
				{
					$title = trim(preg_replace('/\s+/', ' ', $record->itemDescription[ $language ]['name1']));
					$title = ltrim($title, ' +-!?');

					$data = [
						'title'       => $title,
						'description' => html_entity_decode(strip_tags($record->itemDescription[ $language ]['description'])),
					];

					if($record->itemDescription[ $language ]['keywords'])
					{
						$data['tags'] = $this->itemHelper->getTags($record, $language);
					}

					$this->listingTranslationService->createListingTranslation($listingId, $language, $data);
				}
				catch(\Exception $ex)
				{
					$this->getLogger(__FUNCTION__)
						->setReferenceType('listingId')
						->setReferenceValue($listingId)
						->error('Etsy::item.translationUpdateError', $ex->getMessage());
				}
			}
		}
	}

	/**
	 * @param int $listingId
	 * @param int $variationId
	 */
	private function publish($listingId, $variationId)
	{
		$data = [
			'state' => 'active',
		];

		$this->listingService->updateListing($listingId, $data);

		$this->itemHelper->generateSku($listingId, $variationId);
	}
}
