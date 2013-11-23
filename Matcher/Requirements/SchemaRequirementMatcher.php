<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\Requirements\InvalidValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\ValidValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RequirementMatcherInterface;

class SchemaRequirementMatcher implements RequirementMatcherInterface {
	
	protected $context;

	public function __construct(RequestContext $context = NULL) {
		$this->context = $context;
	}
	
	public function match(RequirementContext $context) {
		$route 				= $context->getRoute();
		$scheme  			= $route->getRequirement('_scheme');
		$selectedContext 	= $this->chooseContext(
			$context->getRequestContext()
		);
        if ($scheme && $scheme !== $selectedContext->getScheme()) {
            return new InvalidValidationResult();
        }
		return new ValidValidationResult();
	}

	protected function chooseContext(RequestContext $context = NULL) {
		if ($context !== NULL) {
			return $context;
		} else {
			if ($this->context === NULL) {
				//TODO: Replace with proper exception
				throw new \Exception(); 
			}
			return $this->context;
		}
	}
}