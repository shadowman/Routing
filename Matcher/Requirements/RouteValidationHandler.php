<?php 

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\RouteValidationContext;

class RouteValidationHandler implements RouteValidationHandlerInterface {

	public function handle(RouteValidationContext $context) {

        foreach ($context->getMatchingResults() as $matcherResult) {
            if (!$matcherResult->isValid()) {
                return new RouteValidationResult(
                    ValidationResult::KO, 
                    $context->getRouteName(), 
                    $context->getRoute(),
                    $context->getMatchingResults()
                );
            }
        }
        
        return new RouteValidationResult(
            ValidationResult::OK, 
            $context->getRouteName(), 
            $context->getRoute(),
            $context->getMatchingResults()
        );
	}	
}