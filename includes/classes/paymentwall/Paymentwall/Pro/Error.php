<?php

class Paymentwall_Pro_Error
{
	const ERROR = 'error';
	const ERROR_MESSAGE = 'error';
	const ERROR_CODE = 'code';

	const RISK = 'risk';
	const RISK_PENDING = 'pending';
	const CLICK_ID = 'id';
	const SUPPORT_LINK = 'support_link';

	/**
	 * Error codes
	 */
	const GENERAL_INTERNAL = 1000;
	const APPLICATION_NOT_LOADED = 1001;
	const CHARGE_NOT_FOUND = 3000;
	const CHARGE_PERMISSION_DENIED = 3001;
	const CHARGE_WRONG_AMOUNT = 3002;
	const CHARGE_WRONG_CARD_NUMBER = 3003;
	const CHARGE_WRONG_EXP_MONTH  = 3004;
	const CHARGE_WRONG_EXP_YEAR  = 3005;
	const CHARGE_WRONG_EXP_DATE = 3006;
	const CHARGE_WRONG_CURRENCY = 3007;
	const CHARGE_EMPTY_FIELDS = 3008;
	const CHARGE_WRONG_TOKEN = 3111;
	const CHARGE_CARD_NUMBER_ERROR = 3101;
	const CHARGE_CARD_NUMBER_EXPIRED = 3102;
	const CHARGE_UNSUPPORTED_CARD = 3103;
	const CHARGE_UNSUPPORTED_COUNTRY = 3104;
	const CHARGE_BILLING_ADDRESS_ERROR = 3009;
	const CHARGE_BANK_DECLINE = 3010;
	const CHARGE_INSUFFICIENT_FUNDS = 3011;
	const CHARGE_GATEWAY_DECLINE = 3012;
	const CHARGE_FRAUD_SUSPECTED = 3013;
	const CHARGE_CVV_ERROR = 3014;
	const CHARGE_WRONG_PIN = 3015;
	const CHARGE_FAILED = 3200;
	const CHARGE_ALREADY_REFUNDED = 3201;
	const CHARGE_CANCEL_FAILED = 3202;
	const CHARGE_ALREADY_CAPTURED = 3203;
	const CHARGE_REFUND_FAILED  = 3204;
	const CHARGE_DUPLICATE = 3205;
	const CHARGE_AUTH_EXPIRED = 3206;
	const FIELD_FIRSTNAME = 3301;
	const FIELD_LASTNAME = 3302;
	const FIELD_ADDRESS = 3303;
	const FIELD_CITY = 3304;
	const FIELD_STATE = 3305;
	const FIELD_ZIP = 3306;
	const SUBSCRIPTION_WRONG_PERIOD = 3401;
	const SUBSCRIPTION_NOT_FOUND = 3402;
	const SUBSCRIPTION_WRONG_PERIOD_DURATION = 3403;
	const SUBSCRIPTION_MISSING_TRIAL_PARAMS = 3404;
	const SUBSCRIPTION_WRONG_TRIAL_PERIOD = 3405;
	const SUBSCRIPTION_WRONG_TRIAL_PERIOD_DURATION = 3406;
	const SUBSCRIPTION_WRONG_TRIAL_AMOUNT = 3407;
	const SUBSCRIPTION_WRONG_PAYMENTS_LIMIT = 3408;
	const API_UNDEFINED_METHOD = 4004;
	const API_EMPTY_REQUEST = 4005;
	const API_KEY_MISSED = 4006;
	const API_KEY_INVALID = 4007;
	const API_DECRYPTION_FAILED = 4008;
	const API_WRONG_SIGNATURE = 4009;
	const API_NOT_ACTIVATED = 4010;
	const USER_BANNED = 5000;
	const PARAMETER_WRONG_COUNTRY_CODE = 6001;

	/**
	 * Messages with fields to highlight in JavaScript library corresponding to error codes
	 */
	static $messages = array(
		self::GENERAL_INTERNAL                          => array('field' => ''),
		self::APPLICATION_NOT_LOADED                    => array('field' => ''),
		self::CHARGE_NOT_FOUND                          => array('field' => ''),
		self::CHARGE_PERMISSION_DENIED                  => array('field' => ''),
		self::CHARGE_WRONG_AMOUNT                       => array('field' => ''),
		self::CHARGE_WRONG_CARD_NUMBER                  => array('field' => 'cc-number'),
		self::CHARGE_WRONG_EXP_MONTH                    => array('field' => 'cc-expiry'),
		self::CHARGE_WRONG_EXP_YEAR                     => array('field' => 'cc-expiry'),
		self::CHARGE_WRONG_EXP_DATE                     => array('field' => 'cc-expiry'),
		self::CHARGE_WRONG_CURRENCY                     => array('field' => ''),
		self::CHARGE_EMPTY_FIELDS                       => array('field' => ''),
		self::CHARGE_WRONG_PIN                          => array('field' => ''),
		self::CHARGE_WRONG_TOKEN                        => array('field' => ''),
		self::CHARGE_CARD_NUMBER_ERROR                  => array('field' => 'cc-number'),
		self::CHARGE_CARD_NUMBER_EXPIRED                => array('field' => 'cc-number'),
		self::CHARGE_UNSUPPORTED_CARD                   => array('field' => 'cc-number'),
		self::CHARGE_UNSUPPORTED_COUNTRY                => array('field' => ''),
		self::CHARGE_CVV_ERROR                          => array('field' => 'cc-cvv'),
		self::CHARGE_BILLING_ADDRESS_ERROR              => array('field' => ''),
		self::CHARGE_BANK_DECLINE                       => array('field' => ''),
		self::CHARGE_INSUFFICIENT_FUNDS                 => array('field' => ''),
		self::CHARGE_GATEWAY_DECLINE                    => array('field' => ''),
		self::CHARGE_FRAUD_SUSPECTED                    => array('field' => ''),
		self::CHARGE_FAILED                             => array('field' => ''),
		self::CHARGE_ALREADY_REFUNDED                   => array('field' => ''),
		self::CHARGE_CANCEL_FAILED                      => array('field' => ''),
		self::CHARGE_ALREADY_CAPTURED                   => array('field' => ''),
		self::CHARGE_REFUND_FAILED                      => array('field' => ''),
		self::CHARGE_DUPLICATE                          => array('field' => ''),
		self::CHARGE_AUTH_EXPIRED                       => array('field' => ''),
		self::FIELD_FIRSTNAME                           => array('field' => ''),
		self::FIELD_LASTNAME                            => array('field' => ''),
		self::FIELD_ADDRESS                             => array('field' => ''),
		self::FIELD_CITY                                => array('field' => ''),
		self::FIELD_STATE                               => array('field' => ''),
		self::FIELD_ZIP                                 => array('field' => ''),
		self::SUBSCRIPTION_WRONG_PERIOD                 => array('field' => ''),
		self::SUBSCRIPTION_NOT_FOUND                    => array('field' => ''),
		self::SUBSCRIPTION_WRONG_PERIOD_DURATION        => array('field' => ''),
		self::SUBSCRIPTION_MISSING_TRIAL_PARAMS         => array('field' => ''),
		self::SUBSCRIPTION_WRONG_TRIAL_PERIOD           => array('field' => ''),
		self::SUBSCRIPTION_WRONG_TRIAL_PERIOD_DURATION  => array('field' => ''),
		self::SUBSCRIPTION_WRONG_TRIAL_AMOUNT           => array('field' => ''),
		self::SUBSCRIPTION_WRONG_PAYMENTS_LIMIT         => array('field' => ''),
		self::API_UNDEFINED_METHOD                      => array('field' => ''),
		self::API_EMPTY_REQUEST                         => array('field' => ''),
		self::API_KEY_MISSED                            => array('field' => ''),
		self::API_KEY_INVALID                           => array('field' => ''),
		self::API_DECRYPTION_FAILED                     => array('field' => ''),
		self::API_WRONG_SIGNATURE                       => array('field' => ''),
		self::API_NOT_ACTIVATED                         => array('field' => ''),
		self::USER_BANNED                               => array('field' => ''),
		self::PARAMETER_WRONG_COUNTRY_CODE              => array('field' => '')
	);

	public static function getFieldFromMessages($errorCode) {
		return (array_key_exists($errorCode, self::$messages)) ? self::$messages[$errorCode]['field'] : null;
	}

	public static function isError($response) {
		return isset($response['type']) ? $response['type'] === 'Error' : null;
	}

	public static function wrapError($response) {
		$result = array(
			'error' => $response
		);
		return $result;
	}

	public static function wrapInternalError() {
		$result = array(
			'success' => 0,
			'error' => array(
				'message' => 'Sorry, internal error occurred'
			)
		);
		return $result;
	}

	public static function getPublicData($properties) {
		if (isset($properties[self::ERROR])) {
			return array(
				'success' => 0,
				'error' => array(
					'message' => isset($properties[self::ERROR][self::ERROR_MESSAGE]) ? $properties[self::ERROR][self::ERROR_MESSAGE] : $properties[self::ERROR]['message'],
					'field' => isset($properties[self::ERROR][self::ERROR_CODE]) ? self::getFieldFromMessages($properties[self::ERROR][self::ERROR_CODE]) : ''
				)
			);
		} else if (isset($properties[self::RISK]) && $properties[self::RISK] == self::RISK_PENDING) {
			return array(
				'risk' => 1,
				'support_link' => $properties[self::SUPPORT_LINK],
				'click_id' => $properties[self::CLICK_ID]
			);
		} else {
			return array(
				'success' => 1
			);
		}
	}
}