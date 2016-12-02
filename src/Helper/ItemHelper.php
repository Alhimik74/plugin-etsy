<?php

namespace Etsy\Helper;

use Plenty\Modules\Market\Settings\Contracts\SettingsRepositoryContract;
use Plenty\Modules\Market\Settings\Factories\SettingsCorrelationFactory;
use Plenty\Modules\Market\Settings\Models\Settings;
use Plenty\Modules\Order\Shipping\Contracts\ParcelServicePresetRepositoryContract;
use Plenty\Plugin\Application;
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Helper\Contracts\UrlBuilderRepositoryContract;
use Plenty\Modules\Item\DataLayer\Models\Record;
use Plenty\Modules\Item\VariationSku\Contracts\VariationSkuRepositoryContract;

/**
 * Class ItemHelper
 */
class ItemHelper
{
	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var VariationSkuRepositoryContract
	 */
	private $variationSkuRepository;

	/**
	 * @var ConfigRepository
	 */
	private $config;

	/**
	 * @var UrlBuilderRepositoryContract
	 */
	private $urlBuilderRepository;

	/**
	 * @var OrderHelper
	 */
	private $orderHelper;

	/**
	 * @param Application                    $app
	 * @param UrlBuilderRepositoryContract   $urlBuilderRepository
	 * @param VariationSkuRepositoryContract $variationSkuRepository
	 * @param ConfigRepository               $config
	 * @param OrderHelper                    $orderHelper
	 */
	public function __construct(Application $app, UrlBuilderRepositoryContract $urlBuilderRepository, VariationSkuRepositoryContract $variationSkuRepository, ConfigRepository $config, OrderHelper $orderHelper)
	{
		$this->app                    = $app;
		$this->urlBuilderRepository   = $urlBuilderRepository;
		$this->variationSkuRepository = $variationSkuRepository;
		$this->config                 = $config;
		$this->orderHelper            = $orderHelper;
	}

	/**
	 * Get the stock based on the settings.
	 *
	 * @param Record $item
	 *
	 * @return int
	 */
	public function getStock(Record $item)
	{
		if($item->variationBase->limitOrderByStockSelect == 2)
		{
			$stock = 999;
		}
		elseif($item->variationBase->limitOrderByStockSelect == 1 && $item->variationStock->stockNet > 0)
		{
			if($item->variationStock->stockNet > 999)
			{
				$stock = 999;
			}
			else
			{
				$stock = $item->variationStock->stockNet;
			}
		}
		elseif($item->variationBase->limitOrderByStockSelect == 0)
		{
			if($item->variationStock->stockNet > 999)
			{
				$stock = 999;
			}
			else
			{
				if($item->variationStock->stockNet > 0)
				{
					$stock = $item->variationStock->stockNet;
				}
				else
				{
					$stock = 0;
				}
			}
		}
		else
		{
			$stock = 0;
		}

		return $stock;
	}

	/**
	 * @param int $sku
	 * @param int $variationId
	 */
	public function generateSku($sku, $variationId)
	{
		$this->variationSkuRepository->generateSku($variationId, $this->orderHelper->getReferrerId(), 0, $sku);
	}

	/**
	 * Get the Etsy property.
	 *
	 * @param Record $record
	 * @param string $propertyKey
	 * @param string $lang
	 *
	 * @return mixed
	 */
	public function getProperty(Record $record, $propertyKey, $lang):string
	{
		/** @var SettingsCorrelationFactory $settingsCorrelationFactory */
		$settingsCorrelationFactory = pluginApp(SettingsCorrelationFactory::class);

		foreach($record->itemPropertyList as $itemProperty)
		{
			// $settings = $settingsCorrelationFactory->type(SettingsCorrelationFactory::TYPE_PROPERTY)->getSettingsByCorrelation($itemProperty->propertyId);

			$settings = null;

			if(	$settings instanceof Settings &&
			       isset($settings->settings['mainPropertyKey']) &&
			       isset($settings->settings['propertyKey']) &&
			       isset($settings->settings['propertyKey'][$lang]) &&
			       $settings->settings['mainPropertyKey'] == $propertyKey)
			{
				return $settings->settings['propertyKey']['lang'];
			}
		}

		return '';
	}

	/**
	 * Get list of images for current item.
	 *
	 * @param array  $list
	 * @param string $imageSize
	 *
	 * @return array
	 */
	public function getImageList(array $list, $imageSize = 'normal')
	{
		$imageList = [];

		foreach($list as $image)
		{
			if(is_array($image) && array_key_exists('path', $image))
			{
				$imageList[] = $this->urlBuilderRepository->getImageUrl((string) $image['path'], null, $imageSize, $image['fileType'], $image['type'] == 'external');
			}
		}

		return $imageList;
	}

	/**
	 * @return array
	 */
	public function getEtsyVariationProperties()
	{
		$map = [
			200 => 'Color',
			513 => 'Custom 1',
			514 => 'Custom 2',
			515 => 'Device',
			504 => 'Diameter',
			501 => 'Dimensions',
			502 => 'Fabric',
			500 => 'Finish',
			503 => 'Flavor',
			505 => 'Height',
			506 => 'Length',
			507 => 'Material',
			508 => 'Pattern',
			509 => 'Scent',
			510 => 'Style',
			100 => 'Size',
			511 => 'Weight',
			512 => 'Width',
		];

		return $map;
	}

	/**
	 * @return array
	 */
	public function getEtsyQualifierProperties()
	{
		$map = [
			302       => 'Diameter Scale',
			303       => 'Dimensions Scale',
			304       => 'Height Scale',
			305       => 'Length Scale',
			266817057 => 'Recipient',
			300       => 'Sizing Scale',
			301       => 'Weight Scale',
			306       => 'Width Scale',
		];

		return $map;
	}

	/**
	 * Get the Etsy shipping profile id.
	 *
	 * @param Record $record
	 *
	 * @return int|null
	 */
	public function getShippingTemplateId(Record $record)
	{
		/** @var ParcelServicePresetRepositoryContract $parcelServicePresetRepo */
		$parcelServicePresetRepo = pluginApp(ParcelServicePresetRepositoryContract::class);

		$parcelServicePresetId = null;
		$currentPriority = 999;

		foreach($record->itemShippingProfilesList as $itemShippingProfile)
		{
			try
			{
				$parcelServicePreset = $parcelServicePresetRepo->getPresetById($itemShippingProfile->id);

				if($parcelServicePreset->priority < $currentPriority)
				{
					$currentPriority = $parcelServicePreset->priority;
					$parcelServicePresetId = $parcelServicePreset->id;
				}
			}
			catch(\Exception $ex)
			{
				// do nothing, move to next one
			}
		}

		// $settings = $settingsCorrelationFactory->type(SettingsCorrelationFactory::TYPE_SHIPPING)->getSettingsByCorrelation($parcelServicePresetId);

		$settings = null;

		if($settings instanceof Settings && isset($settings->settings['id']))
		{
			return $settings->settings['id'];
		}

		return null;
	}

	/**
	 * Get the Etsy taxonomy id.
	 *
	 * @param Record $record
	 *
	 * @return int|null
	 */
	public function getTaxonomyId(Record $record)
	{
		$categoryId = $record->variationStandardCategory->categoryId;

		// $settings = $settingsCorrelationFactory->type(SettingsCorrelationFactory::TYPE_CATEGORY)->getSettingsByCorrelation($categoryId);

		$settings = null;

		if($settings instanceof Settings && isset($settings->settings['id']))
		{
			return $settings->settings['id'];
		}

		return null;
	}
}
