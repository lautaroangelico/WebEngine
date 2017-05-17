<?php

class Paymentwall_Product
{
	/**
	 * Product types
	 */
	const TYPE_SUBSCRIPTION = 'subscription';
	const TYPE_FIXED = 'fixed';

	/**
	 * Product period types
	 */
	const PERIOD_TYPE_DAY = 'day';
	const PERIOD_TYPE_WEEK = 'week';
	const PERIOD_TYPE_MONTH = 'month';
	const PERIOD_TYPE_YEAR = 'year';

	/**
	 * @param string $productId your internal product ID, e.g. product1
	 * @param float $amount product price, e.g. 9.99
	 * @param string $currencyCode ISO currency code, e.g. USD
	 * @param string $name product name
	 * @param string $productType product type, Paymentwall_Product::TYPE_SUBSCRIPTION for recurring billing, or Paymentwall_Product::TYPE_FIXED for one-time payments
	 * @param int $periodLength product period length, e.g. 3. Only required if product type is subscription
	 * @param string $periodType product period type, e.g. Paymentwall_Product::PERIOD_TYPE_MONTH. Only required if product type is subscription
	 * @param bool $recurring if the product recurring
	 * @param Paymentwall_Product $trialProduct trial product, product type should be subscription, recurring should be True
	 */
	public function __construct($productId, $amount = 0.0, $currencyCode = null, $name = null, $productType = self::TYPE_FIXED, $periodLength = 0, $periodType = null, $recurring = false, Paymentwall_Product $trialProduct = null)
	{
		$this->productId = $productId;
		$this->amount = round($amount, 2);
		$this->currencyCode = $currencyCode;
		$this->name = $name;
		$this->productType = $productType;
		$this->periodLength = $periodLength;
		$this->periodType = $periodType;
		$this->recurring = $recurring;
		$this->trialProduct = ($productType == Paymentwall_Product::TYPE_SUBSCRIPTION && $recurring) ? $trialProduct : null;
	}

	/**
	 * @return string product ID
	 */ 
	public function getId()
	{
		return $this->productId;
	}

	/**
	 * @return float product price, e.g. 9.99
	 */ 
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * @return string ISO currency code, e.g. USD
	 */ 
	public function getCurrencyCode()
	{
		return $this->currencyCode;
	}

	/**
	 * @return string product name
	 */ 
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string product type, Paymentwall_Product::TYPE_SUBSCRIPTION for recurring billing, Paymentwall_Product::TYPE_FIXED for one-time
	 */ 
	public function getType()
	{
		return $this->productType;
	}

	/**
	 * @return string product period type, e.g. Paymentwall_Product::PERIOD_TYPE_MONTH
	 */ 
	public function getPeriodType()
	{
		return $this->periodType;
	}

	/**
	 * @return string product period length, e.g. 3
	 */ 
	public function getPeriodLength()
	{
		return $this->periodLength;
	}

	/**
	 * @return bool if the product recurring
	 */
	public function isRecurring()
	{
		return $this->recurring;
	}

	/**
	 * @return Paymentwall_Product trial product
	 */
	public function getTrialProduct()
	{
		return $this->trialProduct;
	}
}
