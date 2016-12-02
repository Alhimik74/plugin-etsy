<?php

namespace Etsy\Helper;

use Plenty\Modules\Plugin\DynamoDb\Contracts\DynamoDbRepositoryContract;

/**
 * Class SettingsHelper
 */
class SettingsHelper
{
	const TABLE_NAME = 'settings';

	const SETTINGS_TOKEN_REQUEST = 'token_request';
	const SETTINGS_ACCESS_TOKEN = 'access_token';
	const SETTINGS_SETTINGS = 'settings';
	const SETTINGS_ORDER_REFERRER = 'order_referrer';
	const SETTINGS_LAST_ORDER_IMPORT = "last_order_import";

	/**
	 * @var DynamoDbRepositoryContract
	 */
	private $dynamoDbRepo;

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * @param DynamoDbRepositoryContract $dynamoDbRepository
	 */
	public function __construct(DynamoDbRepositoryContract $dynamoDbRepository)
	{
		$this->dynamoDbRepo = $dynamoDbRepository;
	}

	/**
	 * Save settings to database.
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return bool
	 */
	public function save($name, $value)
	{
		return $this->dynamoDbRepo->putItem('EtsyIntegrationPlugin', self::TABLE_NAME, [
			'name'  => [
				'S' => (string) $name,
			],
			'value' => [
				'S' => (string) $value,
			],
		]);
	}

	/**
	 * Get settings for a given id.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return string|null
	 */
	public function get($name, $default = null)
	{
		$data = $this->dynamoDbRepo->getItem('EtsyIntegrationPlugin', self::TABLE_NAME, true, [
			'name' => ['S' => $name]
		]);

		if(isset($data['value']['S']))
		{
			return $data['value']['S'];
		}

		return $default;
	}

	/**
	 * Get the shop settings.
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed|null
	 */
	public function getShopSettings($key, $default = null)
	{
		if(!$this->settings)
		{
			$this->settings = $this->get(SettingsHelper::SETTINGS_SETTINGS);
		}

		if($this->settings)
		{
			$settings = json_decode($this->settings, true);

			if(isset($settings['shop'][$key]))
			{
				return $settings['shop'][$key];
			}

		}

		return $default;
	}
}
