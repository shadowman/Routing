<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\Requirements\RequirementMatcherInterface;
use Symfony\Component\Routing\Matcher\Requirements\RegExpValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\OkValidationResult;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class HostRequirementMatcher  implements RequirementMatcherInterface {
	

	public function match(RequirementContext $context) {
		$route 				 = $context->getRoute();
		$path 				 = $context->getPath();
		$compiledRoute 		 = $route->compile();
        $validationResult 	 = $this->validateHost(
        	$path, 
        	$context->getRequestContext(), 
        	$compiledRoute
        );
        return $validationResult;
	}

	private function validateHost($pathinfo, $context, $compiledRoute) {
        $hostMatches = array();
        if ($compiledRoute->getHostRegex() && !preg_match($compiledRoute->getHostRegex(), $context->getHost(), $hostMatches)) {
            return new HostValidationResult(ValidationResult::KO, $hostMatches);
        }
        return new HostValidationResult(ValidationResult::OK, $hostMatches);        
    }
}