<?php

namespace Symfony\Component\Routing\Matcher\Requirements;
// TODO: Rename this to SuccessValidationResult

class RouteValidationResult extends ValidationResult {
	public function __construct($isValid, $route, $results) {
		parent::__construct(ValidationResult::OK, $results);
		$this->route = $route;
	}
}