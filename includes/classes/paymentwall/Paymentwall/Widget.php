<?php

class Paymentwall_Widget extends Paymentwall_Base
{
	/**
	 * Widget call URL
	 */
	const BASE_URL = 'https://api.paymentwall.com/api';

	protected $userId;
	protected $widgetCode;
	protected $products;
	protected $extraParams;

	/**
	 * @param string $userId identifier of the end-user who is viewing the widget
	 * @param string $widgetCode e.g. p1 or p1_1, can be found inside of your Paymentwall Merchant account in the Widgets section
	 * @param array $products array that consists of Paymentwall_Product entities; for Flexible Widget Call use array of 1 product
	 * @param array $extraParams associative array of additional params that will be included into the widget URL, 
	 * e.g. 'sign_version' or 'email'. Full list of parameters for each API is available at http://paymentwall.com/documentation
	 */
	public function __construct($userId, $widgetCode, $products = array(), $extraParams = array()) {
		$this->userId = $userId;
		$this->widgetCode = $widgetCode;
		$this->products = $products;
		$this->extraParams = $extraParams;
	}

	/**
	 * Get default signature version for this API type
	 * 
	 * @return int
	 */
	public function getDefaultSignatureVersion() {
		return self::getApiType() != self::API_CART ? self::DEFAULT_SIGNATURE_VERSION : self::SIGNATURE_VERSION_2;
	}

	/**
	 * Return URL for the widget
	 *
	 * @return string
	 */
	public function getUrl()
	{
		$params = array(
			'key' => self::getAppKey(),
			'uid' => $this->userId,
			'widget' => $this->widgetCode
		);

		$productsNumber = count($this->products);

		if (self::getApiType() == self::API_GOODS) {

			if (!empty($this->products)) {

				if ($productsNumber == 1) {

					$product = current($this->products);

					if ($product->getTrialProduct() instanceof Paymentwall_Product) {
						$postTrialProduct = $product;
						$product = $product->getTrialProduct();
					}

					$params['amount'] = $product->getAmount();
					$params['currencyCode'] = $product->getCurrencyCode();
					$params['ag_name'] = $product->getName();
					$params['ag_external_id'] = $product->getId();
					$params['ag_type'] = $product->getType();

					if ($product->getType() == Paymentwall_Product::TYPE_SUBSCRIPTION) {
						$params['ag_period_length'] = $product->getPeriodLength();
						$params['ag_period_type'] = $product->getPeriodType();
						if ($product->isRecurring()) {

							$params['ag_recurring'] = intval($product->isRecurring());

							if (isset($postTrialProduct)) {
								$params['ag_trial'] = 1;
								$params['ag_post_trial_external_id'] = $postTrialProduct->getId();
								$params['ag_post_trial_period_length'] = $postTrialProduct->getPeriodLength();
								$params['ag_post_trial_period_type'] = $postTrialProduct->getPeriodType();
								$params['ag_post_trial_name'] = $postTrialProduct->getName();
								$params['post_trial_amount'] = $postTrialProduct->getAmount();
								$params['post_trial_currencyCode'] = $postTrialProduct->getCurrencyCode();
							}

						}
					}

				} else {
					//TODO: $this->appendToErrors('Only 1 product is allowed in flexible widget call');
				}

			}

		} else if (self::getApiType() == self::API_CART) {

			$index = 0;
			foreach ($this->products as $product) {
				$params['external_ids[' . $index . ']'] = $product->getId();

				if (isset($product->amount)) {
					$params['prices[' . $index . ']'] = $product->getAmount();
				}
				if (isset($product->currencyCode)) {
					$params['currencies[' . $index . ']'] = $product->getCurrencyCode();
				}

				$index++;
			}
			unset($index);
		}

		$params['sign_version'] = $signatureVersion = self::getDefaultSignatureVersion();

		if (!empty($this->extraParams['sign_version'])) {
			$signatureVersion = $params['sign_version'] = $this->extraParams['sign_version'];
		}

		$params = array_merge($params, $this->extraParams);

		$params['sign'] = $this->calculateSignature($params, self::getSecretKey(), $signatureVersion);

		return self::BASE_URL . '/' . self::buildController($this->widgetCode) . '?' . http_build_query($params);
	}

	/**
	 * Return HTML code for the widget
	 *
	 * @param array $attributes associative array of additional HTML attributes, e.g. array('width' => '100%')
	 * @return string
	 */
	public function getHtmlCode($attributes = array())
	{

		$defaultAttributes = array(
			'frameborder' => '0',
			'width' => '750',
			'height' => '800'
		);

		$attributes = array_merge($defaultAttributes, $attributes);

		$attributesQuery = '';
		foreach ($attributes as $attr => $value) {
			$attributesQuery .= ' ' . $attr . '="' . $value . '"';
		}

		return '<iframe src="' . $this->getUrl() . '" ' . $attributesQuery . '></iframe>';

	}

	/**
	 * Build controller URL depending on API type
	 *
	 * @param string $widget code of the widget
	 * @param bool $flexibleCall
	 * @return string
	 */
	protected function buildController($widget, $flexibleCall = false)
	{
		if (self::getApiType() == self::API_VC) {

			if (!preg_match('/^w|s|mw/', $widget)) {
				return self::CONTROLLER_PAYMENT_VIRTUAL_CURRENCY;
			}

		} else if (self::getApiType() == self::API_GOODS) {

			if (!$flexibleCall) {
				if (!preg_match('/^w|s|mw/', $widget)) {
					return self::CONTROLLER_PAYMENT_DIGITAL_GOODS;
				}
			} else {
				return self::CONTROLLER_PAYMENT_DIGITAL_GOODS;
			}

		} else {

			return self::CONTROLLER_PAYMENT_CART;

		}
	}

	/**
	 * Build signature for the widget specified
	 *
	 * @param array $params
	 * @param string $secret Paymentwall Secret Key
	 * @param int $version Paymentwall Signature Version
	 * @return string
	 */
	public static function calculateSignature($params, $secret, $version)
	{

		$baseString = '';

		if ($version == self::SIGNATURE_VERSION_1) {
			// TODO: throw exception if no uid parameter is present

			$baseString .= isset($params['uid']) ? $params['uid'] : '';
			$baseString .= $secret;

			return md5($baseString);

		} else {

			if (is_array($params)) {
				ksort($params);
				foreach ($params as &$p) {
					if (is_array($p)) {
						ksort($p);
					}
				}
			}

			foreach ($params as $key => $value) {
				if (!isset($value)) {
					continue;
				}
				if (is_array($value)) {
					foreach ($value as $k => $v) {
						$baseString .= $key . '[' . $k . ']' . '=' . ($v === false ? '0' : $value);
					}
				} else {
					$baseString .= $key . '=' . ($value === false ? '0' : $value);
				}
			}

			$baseString .= $secret;

			if ($version == self::SIGNATURE_VERSION_2) {
				return md5($baseString);
			}
			return hash('sha256', $baseString);
		}
	}
}
