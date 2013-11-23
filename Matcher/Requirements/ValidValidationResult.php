<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class ValidValidationResult extends ValidationResult {
	public function __construct() {
		parent::__construct(ValidationResult::OK);
	}
}