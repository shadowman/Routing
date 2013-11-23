<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class InvalidValidationResult extends ValidationResult {
	public function __construct() {
		parent::__construct(ValidationResult::KO);
	}
}