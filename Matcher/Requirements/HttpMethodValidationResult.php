<?php
namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\ValidationResult;

class HttpMethodValidationResult extends ValidationResult {
	private $allowedMethods = array();

	public function __construct($isValid, $allowedMethods = array()) {
		parent::__construct($isValid);
		$this->allowedMethods = $allowedMethods;
	}

	public function getAllowedMethods() {
		return $this->allowedMethods;
	}
}