<?php

class Paymentwall_Pingback extends Paymentwall_Base
{
	/**
	 * Pingback types
	 */
	const PINGBACK_TYPE_REGULAR = 0;
	const PINGBACK_TYPE_GOODWILL = 1;
	const PINGBACK_TYPE_NEGATIVE = 2;

	const PINGBACK_TYPE_RISK_UNDER_REVIEW = 200;
	const PINGBACK_TYPE_RISK_REVIEWED_ACCEPTED = 201;
	const PINGBACK_TYPE_RISK_REVIEWED_DECLINED = 202;

	const PINGBACK_TYPE_SUBSCRIPTION_CANCELLATION = 12;
	const PINGBACK_TYPE_SUBSCRIPTION_EXPIRED = 13;
	const PINGBACK_TYPE_SUBSCRIPTION_PAYMENT_FAILED = 14;

	/**
	 * Pingback parameters, usually $_GET
	 */
	protected $parameters;

	/**
	 * IP address, usually $_SERVER['REMOTE_ADDR']
	 */
	protected $ipAddress;

	/**
	 * @param array $parameters array of parameters received by pingback processing script, e.g. $_GET
	 * @param string $ipAddress IP address from where the pingback request originates, e.g. '127.0.0.1'
	 */
	public function __construct(array $parameters, $ipAddress)
	{
		$this->parameters = $parameters;
		$this->ipAddress = $ipAddress;
	}

	/**
	 * Check whether pingback is valid
	 *
	 * @param bool $skipIpWhitelistCheck if IP whitelist check should be skipped, e.g. if you have a load-balancer changing the IP
	 * @return bool
	 */
	public function validate($skipIpWhitelistCheck = false)
	{
		$validated = false;

		if ($this->isParametersValid()) {

			if ($this->isIpAddressValid() || $skipIpWhitelistCheck) {

				if ($this->isSignatureValid()) {

					$validated = true;

				} else {
					$this->appendToErrors('Wrong signature');
				}

			} else {
				$this->appendToErrors('IP address is not whitelisted');
			}

		} else {
			$this->appendToErrors('Missing parameters');
		}

		return $validated;
	}

	/**
	 * @return bool
	 */
	public function isSignatureValid()
	{
		$signatureParamsToSign = array();

		if (self::getApiType() == self::API_VC) {

			$signatureParams = array('uid', 'currency', 'type', 'ref');

		} else if (self::getApiType() == self::API_GOODS) {

			$signatureParams = array('uid', 'goodsid', 'slength', 'speriod', 'type', 'ref');

		} else { // API_CART

			$signatureParams = array('uid', 'goodsid', 'type', 'ref');

			$this->parameters['sign_version'] = self::SIGNATURE_VERSION_2;

		}

		if (empty($this->parameters['sign_version']) || $this->parameters['sign_version'] == self::SIGNATURE_VERSION_1) {

			foreach ($signatureParams as $field) {
				$signatureParamsToSign[$field] = isset($this->parameters[$field]) ? $this->parameters[$field] : null;
			}

			$this->parameters['sign_version'] = self::SIGNATURE_VERSION_1;

		} else {
			$signatureParamsToSign = $this->parameters;
		}

		$signatureCalculated = $this->calculateSignature($signatureParamsToSign, self::getSecretKey(), $this->parameters['sign_version']);

		$signature = isset($this->parameters['sig']) ? $this->parameters['sig'] : null;

		return $signature == $signatureCalculated;
	}

	/**
	 * @return bool
	 */
	public function isIpAddressValid()
	{
		$ipsWhitelist = array(
			'174.36.92.186',
			'174.36.96.66',
			'174.36.92.187',
			'174.36.92.192',
			'174.37.14.28'
		);

		return in_array($this->ipAddress, $ipsWhitelist);
	}

	/**
	 * @return bool
	 */
	public function isParametersValid()
	{
		$errorsNumber = 0;

		if (self::getApiType() == self::API_VC) {

			$requiredParams = array('uid', 'currency', 'type', 'ref', 'sig');

		} else if (self::getApiType() == self::API_GOODS) {

			$requiredParams = array('uid', 'goodsid', 'type', 'ref', 'sig');

		} else { // Cart API

			$requiredParams = array('uid', 'goodsid', 'type', 'ref', 'sig');

		}

		foreach ($requiredParams as $field) {
			if (!isset($this->parameters[$field]) || $this->parameters[$field] === '') {
				$this->appendToErrors('Parameter ' . $field . ' is missing');
				$errorsNumber++;
			}
		}

		return $errorsNumber == 0;
	}

	/**
	 * Get pingback parameter
	 *
	 * @param $param
	 * @return string
	 */
	public function getParameter($param)
	{
		if (isset($this->parameters[$param])) {
			return $this->parameters[$param];
		}
	}

	/**
	 * Get pingback parameter 'type'
	 *
	 * @return int
	 */
	public function getType()
	{
		if (isset($this->parameters['type'])) {
			return intval($this->parameters['type']);
		}
	}

	/**
	 * Get verbal explanation of the informational pingback
	 *
	 * @return string
	 */
	public function getTypeVerbal() {
		$pingbackTypes = array(
			self::PINGBACK_TYPE_SUBSCRIPTION_CANCELLATION => 'user_subscription_cancellation',
			self::PINGBACK_TYPE_SUBSCRIPTION_EXPIRED => 'user_subscription_expired',
			self::PINGBACK_TYPE_SUBSCRIPTION_PAYMENT_FAILED => 'user_subscription_payment_failed'
		);

		if (!empty($this->parameters['type'])) {
			if (array_key_exists($this->parameters['type'], $pingbackTypes)) {
				return $pingbackTypes[$this->parameters['type']];
			}
		}
	}

	/**
	 * Get pingback parameter 'uid'
	 *
	 * @return string
	 */
	public function getUserId()
	{
		return $this->getParameter('uid');
	}

	/**
	 * Get pingback parameter 'currency'
	 *
	 * @return string
	 */
	public function getVirtualCurrencyAmount()
	{
		return $this->getParameter('currency');
	}

	/**
	 * Get product id
	 *
	 * @return string
	 */
	public function getProductId()
	{
		return $this->getParameter('goodsid');
	}

	/**
	 * @return int
	 */
	public function getProductPeriodLength()
	{
		return $this->getParameter('slength');
	}

	/**
	 * @return string
	 */
	public function getProductPeriodType()
	{
		return $this->getParameter('speriod');
	}

	/**
	 * @return Paymentwall_Product
	 */
	public function getProduct() {
		return new Paymentwall_Product(
			$this->getProductId(),
			0,
			null,
			null,
			$this->getProductPeriodLength() > 0 ? Paymentwall_Product::TYPE_SUBSCRIPTION : Paymentwall_Product::TYPE_FIXED,
			$this->getProductPeriodLength(),
			$this->getProductPeriodType()
		);
	}

	/**
	 * @return array Paymentwall_Product
	 */
	public function getProducts() {
		$result = array();
		$productIds = $this->getParameter('goodsid');

		if (!empty($productIds)) {
			foreach ($productIds as $Id) {
				$result[] = new Paymentwall_Product($Id);
			}
		}

		return $result;
	}

	/**
	 * Get pingback parameter 'ref'
	 *
	 * @return string
	 */
	public function getReferenceId()
	{
		return $this->getParameter('ref');
	}

	/**
	 * Returns unique identifier of the pingback that can be used for checking
	 * if the same pingback was already processed by your servers.
	 * Two pingbacks with the same unique ID should not be processed more than once
	 *
	 * @return string
	 */
	public function getPingbackUniqueId()
	{
		return $this->getReferenceId() . '_' . $this->getType();
	}

	/**
	 * Check whether product is deliverable
	 *
	 * @return bool
	 */
	public function isDeliverable()
	{
		return (
			$this->getType() === self::PINGBACK_TYPE_REGULAR ||
			$this->getType() === self::PINGBACK_TYPE_GOODWILL ||
			$this->getType() === self::PINGBACK_TYPE_RISK_REVIEWED_ACCEPTED
		);
	}

	/**
	 * Check whether product is cancelable
	 *
	 * @return bool
	 */
	public function isCancelable()
	{
		return (
			$this->getType() === self::PINGBACK_TYPE_NEGATIVE ||
			$this->getType() === self::PINGBACK_TYPE_RISK_REVIEWED_DECLINED
		);
	}

	/**
	 * Check whether product is under review
	 *
	 * @return bool
	 */
	public function isUnderReview() {
		return $this->getType() === self::PINGBACK_TYPE_RISK_UNDER_REVIEW;
	}

	/**
	 * Build signature for the pingback received
	 *
	 * @param array $params
	 * @param string $secret Paymentwall Secret Key
	 * @param int $version Paymentwall Signature Version
	 * @return string
	 */
	protected function calculateSignature($params, $secret, $version)
	{
		$baseString = '';

		unset($params['sig']);

		if ($version == self::SIGNATURE_VERSION_2 or $version == self::SIGNATURE_VERSION_3) {
			if (is_array($params)) {
				ksort($params);
				foreach ($params as &$p) {
					if (is_array($p)) {
						ksort($p);
					}
				}
			}
		}

		foreach ($params as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$baseString .= $key . '[' . $k . ']' . '=' . $v;
				}
			} else {
				$baseString .= $key . '=' . $value;
			}
		}

		$baseString .= $secret;

		if ($version == self::SIGNATURE_VERSION_3) {
			return hash('sha256', $baseString);
		}

		return md5($baseString);

	}
}
