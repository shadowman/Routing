<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;

class RouteValidationContext {
	protected $route;
	protected $routeName;
	protected $routeMatchingResults;

	public function __construct(Route $route, $routeName, $results) {
		$this->route = $route;
		$this->routeName = $routeName;
		$this->routeMatchingResults = $results;
	}

	public function getRoute() {
		return $this->route;
	}
	
	public function getRouteName() {
		return $this->routeName;
	}

	public function getMatchingResults() {
		return $this->routeMatchingResults;
	}
}