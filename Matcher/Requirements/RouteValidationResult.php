<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class RouteValidationResult extends ValidationResult {
	protected $route;
	protected $attributes = array();

	public function __construct($isValid, $routeName, $route, $results = NULL) {
		parent::__construct($isValid, $results);
		$this->route = $route;
		$this->routeName = $routeName;
	}

	public function getRoute() {
		return $this->route;
	}

	public function getRouteName() {
		return $this->routeName;
	}
}