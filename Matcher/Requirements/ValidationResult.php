<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class ValidationResult {
	const OK = true;
	const KO = false;

	protected $results;
	protected $isValid;

	public function __construct($isValid, $results = array()) {
		$this->isValid = $isValid;
		$this->results = $results;
	}

	public function isValid() {
		return $this->isValid;
	}
	
	public function getInnerValidationResults() {
		$this->results;
	}

	protected function setInnerValidationResults($results) {
		$this->results = $results;
	}
}



