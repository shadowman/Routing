<?php

namespace Symfony\Component\Routing\Matcher\Requirements;
// TODO: Rename this to SuccessValidationResult

class RegExpValidationResult extends ValidationResult {
	public function __construct($isValid, $matches = array(), $results = NULL) {
		parent::__construct($isValid, $results);
		$this->matches = $matches;
	}

	public function getMatches() {
		return $this->matches;
	}
}