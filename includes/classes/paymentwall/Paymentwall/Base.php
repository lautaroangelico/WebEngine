<?php

abstract class Paymentwall_Base
{
	/**
	 * Paymentwall library version
	 */
	const VERSION = '1.1.1';

	/**
	 * API types
	 */
	const API_VC = 1;
	const API_GOODS = 2;
	const API_CART = 3;

	/**
	 * Controllers for APIs
	 */
	const CONTROLLER_PAYMENT_VIRTUAL_CURRENCY = 'ps';
	const CONTROLLER_PAYMENT_DIGITAL_GOODS = 'subscription';
	const CONTROLLER_PAYMENT_CART = 'cart';

	/**
	 * Signature versions
	 */
	const DEFAULT_SIGNATURE_VERSION = 3;
	const SIGNATURE_VERSION_1 = 1;
	const SIGNATURE_VERSION_2 = 2;
	const SIGNATURE_VERSION_3 = 3;

	const PRO_API_VERSION_1 = 1;
	const PRO_API_VERSION_2 = 2;

	protected $errors = array();

	/**
	 * Paymentwall API type
	 * @param int $apiType
	 */
	public static $apiType;

	/**
	 * Paymentwall application key - can be found in your merchant area
	 * @param string $appKey
	 */
	public static $appKey;

	/**
	 * Paymentwall secret key - can be found in your merchant area
	 * @param string $secretKey
	 */
	public static $secretKey;

	public static $proApiBaseUrl = 'https://api.paymentwall.com/api/';

	/**
	 * Paymentwall Pro API Version
	 * @param string $proApiVersion
	 */
	public static $proApiVersion = self::PRO_API_VERSION_1;

	/**
	 * Paymentwall Pro API Key
	 * @param string $proApiKey
	 */
	public static $proApiKey;

	public static function getUrlByApiVersion($apiVersion) {
		$map = array(
			self::PRO_API_VERSION_1 => array(
				'charge' 		=> self::getProApiBaseUrl() . 'pro/v1/charge',
				'subscription' 	=> self::getProApiBaseUrl() . 'pro/v1/subscription'
			),
			self::PRO_API_VERSION_2 => array(
				'charge' 		=> self::getProApiBaseUrl() . 'pro/v2/charge',
				'subscription' 	=> self::getProApiBaseUrl() . 'pro/v2/subscription'
			)
		);

		return $map[$apiVersion];
	}

	/**
	 * @param int $apiType API type, Paymentwall_Base::API_VC for Virtual Currency, Paymentwall_Base::API_GOODS for Digital Goods
	 * Paymentwall_Base::API_CART for Cart, more details at http://paymentwall.com/documentation
	 */ 
	public static function setApiType($apiType)
	{
		self::$apiType = $apiType;
	}

	public static function getApiType()
	{
		return self::$apiType;
	}

	/**
	 * @param string $appKey application key of your application, can be found inside of your Paymentwall Merchant Account
	 */ 
	public static function setAppKey($appKey)
	{
		self::$appKey = $appKey;
	}

	public static function getAppKey()
	{
		return self::$appKey;
	}

	/**
	 * @param string $secretKey secret key of your application, can be found inside of your Paymentwall Merchant Account
	 */ 
	public static function setSecretKey($secretKey)
	{
		self::$secretKey = $secretKey;
	}

	public static function getSecretKey()
	{
		return self::$secretKey;
	}

	/**
	 * @param string $proApiKey API key used for Pro authentication
	 */
	public static function setProApiKey($proApiKey) {
		self::$proApiKey = $proApiKey;
	}

	public static function getProApiKey() {
		return self::$proApiKey;
	}

	public static function setProApiVersion($proApiVersion) {
		self::$proApiVersion = $proApiVersion;
	}

	public static function getProApiVersion() {
		return self::$proApiVersion;
	}

	public static function setProApiBaseUrl($proApiBaseUrl) {
		self::$proApiBaseUrl = $proApiBaseUrl;
	}

	public static function getProApiBaseUrl() {
		return self::$proApiBaseUrl;
	}

	/**
	 * Fill the array with the errors found at execution
	 *
	 * @param $err
	 * @return int
	 */
	protected function appendToErrors($err)
	{
		return array_push($this->errors, $err);
	}

	/**
	 * Return errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Return error summary 
	 *
	 * @return string
	 */
	public function getErrorSummary()
	{
		return implode("\n", $this->getErrors());
	}
}
