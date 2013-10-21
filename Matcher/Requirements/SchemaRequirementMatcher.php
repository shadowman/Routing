<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse;

class SchemaRequirementMatcher {
	protected $context;

	public function __construct(RequestContext $context = NULL) {
		$this->context = $context;
	}
	
	public function match($pathinfo, $name, Route $route, RequestContext $context = NULL) {
		$scheme  			= $route->getRequirement('_scheme');
		$selectedContext 	= $this->chooseContext($context);
        if ($scheme && $scheme !== $selectedContext->getScheme()) {
            return new RequirementMismatchesResponse();
        }
		return new RequirementMatchesResponse();
	}

	protected function chooseContext(RequestContext $context = NULL) {
		if ($context !== NULL) {
			return $context;
		} else {
			if ($this->context === NULL) {
				//TODO: Replace with propper exception
				throw new Exception(); 
			}
			return $this->context;
		}
	}
}