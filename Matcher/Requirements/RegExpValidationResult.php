<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class RegExpValidationResult extends ValidationResult {
	protected $matches = array();
	
	public function __construct($isValid, $matches = array(), $results = array()) {
		parent::__construct($isValid, $results);
		$this->matches = $matches;
	}

	public function getMatches() {
		return $this->matches;
	}
}