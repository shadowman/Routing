<?php

namespace Symfony\Component\Routing\Matcher\Requirements;
// TODO: Rename this to FailureValidationResult
class KoValidationResult extends ValidationResult {
	public function __construct($results = NULL) {
		parent::__construct(ValidationResult::KO, $results);
	}
}