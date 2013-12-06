<?php


namespace Symfony\Component\Routing\Matcher\Requirements;

class RouteCollectionHandlerContext {
	protected $allRoutesResults;

	public function __construct(array $results = array()) {
		$this->allRoutesResults = $results;
	}

	public function getAllRoutesResults() {
		return $this->allRoutesResults;
	}
}
