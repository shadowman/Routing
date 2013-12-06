<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionHandlerContext;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RouteCollectionValidationHandler implements RouteCollectionHandlerInterface {
	
	public function handle(RouteCollectionHandlerContext $context) {
        $allRoutesResults   = array_values($context->getAllRoutesResults());
        $lastRouteResult    = end($allRoutesResults);
        if (count($allRoutesResults) > 0 && !$lastRouteResult->isValid()) {
    		$allowed = $this->findAllowedMethodsInFailingMethodChecks($allRoutesResults);
	        if (count($allowed) > 0) {
	            throw new MethodNotAllowedException($allowed);
	        }
	        throw new ResourceNotFoundException();
        }
        return $lastRouteResult;
	}

    private function findAllowedMethodsInFailingMethodChecks(array $allRoutesResults) {
        $allowed = array();
        // TODO: Optimize this with hashes
        foreach ($allRoutesResults as $routeResult) {
            foreach ($routeResult->getInnerValidationResults() as $innerResult) {
                if ($innerResult instanceOf HttpMethodValidationResult && !$innerResult->isValid()) {
                    $allowed = array_merge($allowed, $innerResult->getAllowedMethods());
                }
            }
        }
        return $allowed;
    }
}