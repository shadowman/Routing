<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class ConditionValidationResult extends ValidationResult {
	protected $condition = array();
	
	public function __construct($isValid, $condition, $results = array()) {
		parent::__construct($isValid, $results);
		$this->condition = $condition;
	}

	public function getCondition() {
		return $this->condition;
	}
}