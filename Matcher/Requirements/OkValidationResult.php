<?php

namespace Symfony\Component\Routing\Matcher\Requirements;
// TODO: Rename this to SuccessValidationResult

class OkValidationResult extends ValidationResult {
	public function __construct($results = array() )  {
		parent::__construct(ValidationResult::OK, $results);
	}
}