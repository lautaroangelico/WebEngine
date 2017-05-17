<?php

class Paymentwall_Pro_Charge
{
	public function __construct(array $attr) {
		$result = new Paymentwall_Pro_HttpWrapper($attr);
		$this->properties = $result->post();
	}

	public function getPublicData() {
		return Paymentwall_Pro_Error::getPublicData($this->properties);
	}

	public function isCaptured() {
		return $this->captured;
	}

	public function isRiskPending() {
		return ($this->risk == 'pending') ? true : false;
	}

	public function __get($property) {
		return (isset($this->properties[$property])) ? $this->properties[$property] : null;
	}
}