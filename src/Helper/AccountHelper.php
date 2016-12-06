<?php

namespace Etsy\Helper;

use Plenty\Plugin\Application;

use Etsy\Helper\OrderHelper;
use Etsy\Helper\SettingsHelper;

/**
 * Class AccountHelper
 */
class AccountHelper
{
	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var SettingsHelper
	 */
	private $settingsHelper;

	/**
	 * @var OrderHelper
	 */
	private $orderHelper;

	/**
	 * @param Application    $app
	 * @param SettingsHelper $settingsHelper
	 */
	public function __construct(Application $app, SettingsHelper $settingsHelper, OrderHelper $orderHelper)
	{
		$this->app            = $app;
		$this->settingsHelper = $settingsHelper;
		$this->orderHelper    = $orderHelper;
	}

	/**
	 * Get the access token data.
	 *
	 * @return array
	 */
	public function getTokenData()
	{
		$data = $this->settingsHelper->get(SettingsHelper::SETTINGS_ACCESS_TOKEN);

		if($data)
		{
			$data = json_decode($data, true);

			return [
				'accessToken'       => isset($data['accessToken']) ? $data['accessToken'] : '',
				'accessTokenSecret' => isset($data['accessTokenSecret']) ? $data['accessTokenSecret'] : '',
			];
		}
	}

	/**
	 * Get the consumer key.
	 *
	 * @return string
	 */
	public function getConsumerKey()
	{
		return '6d6s53b0qd09nhw37253ero8';
	}

	/**
	 * Get the consumer shared secret.
	 *
	 * @return string
	 */
	public function getConsumerSecret()
	{
		return 'dzi5pnxwxm';
	}

	/**
	 * Get the token request data.
	 *
	 * @return null|array
	 */
	public function getTokenRequest()
	{
		$data = $this->settingsHelper->get(SettingsHelper::SETTINGS_TOKEN_REQUEST);

		if($data)
		{
			return json_decode($data, true);
		}

		return null;
	}

	/**
	 * Save the token request data.
	 *
	 * @param $data
	 */
	public function saveTokenRequest($data)
	{
		$this->settingsHelper->save(SettingsHelper::SETTINGS_TOKEN_REQUEST, (string) json_encode($data));
	}

	/**
	 * Save the access token data.
	 *
	 * @param array $data
	 */
	public function saveAccessToken($data)
	{
		$this->settingsHelper->save(SettingsHelper::SETTINGS_ACCESS_TOKEN, (string) json_encode($data));
	}

	/**
	 * Validates the Etsy configuration settings.
	 *
	 * @return bool
	 */
	public function isValidConfig():bool
	{
		try
		{
			$tokenData  = $this->getTokenData();
			$shopId     = $this->settingsHelper->getShopSettings('shopId');
			$referrerId = $this->orderHelper->getReferrerId();

			if($tokenData && isset($tokenData['accessToken']) && $shopId && $shopId > 0 && $referrerId && $referrerId > 0)
			{
				return true;
			}
		}
		catch(\Exception $e)
		{
			//
		}

		return false;
	}

	/**
	 * Checks whether the given process is active.
	 *
	 * @param string $processKey
	 *
	 * @return bool
	 */
	public function isProcessActive($processKey):bool
	{
		return array_search($processKey, $this->settingsHelper->getShopSettings('processes', [])) !== false;
	}
}