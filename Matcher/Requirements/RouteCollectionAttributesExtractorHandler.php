<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;

use Symfony\Component\Routing\Matcher\Requirements\HostValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RegExpValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionHandlerContext;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionAttributesExtractorHandler;



class RouteCollectionAttributesExtractorHandler implements RouteCollectionHandlerInterface {
	public function handle(RouteCollectionHandlerContext $context) {
        return $this->extractRouteAttributesFromResults(
            $context->getAllRoutesResults()
        );
	}

	private function extractRouteAttributesFromResults(array $allRoutesResults) {

        $attributes         = array();
        $values             = array_values($allRoutesResults);
        $lastRouteResult    = end($values);
        if ($lastRouteResult->isValid()) {
            $attributes = $this->buildMatchingRouteAttributes(
                $lastRouteResult
            );
        }
        return $attributes;
    }

    private function buildMatchingRouteAttributes(RouteValidationResult $result) {
        $innerCheckResults = $result->getInnerValidationResults();
        $regExpMatches = $this->findRegExpMatches($innerCheckResults);
        $hostMatches   = $this->findHostMatches($innerCheckResults);
        $attributes = $this->getAttributes(
            $result->getRoute(), 
            $result->getRouteName(), 
            array_replace(
                $regExpMatches, 
                $hostMatches
            )
        );
        return $attributes;
    }


    private function findRegExpMatches(array $results) {
        // TODO: Optimize this with hashes
        foreach ($results as $result) {
            if ($result instanceOf RegExpValidationResult) {
                return $result->getMatches();
            }
        }
        return array();
    }
    
    private function findHostMatches(array $results) {
        // TODO: Optimize this with hashes
        foreach ($results as $result) {
            if ($result instanceOf HostValidationResult) {
                return $result->getHosts();
            }
        }
        return array();
    }

    /**
     * Get merged default parameters.
     *
     * @param array $params   The parameters
     * @param array $defaults The defaults
     *
     * @return array Merged default parameters
     */
    protected function mergeDefaults($params, $defaults)
    {
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    /**
     * Returns an array of values to use as request attributes.
     *
     * As this method requires the Route object, it is not available
     * in matchers that do not have access to the matched Route instance
     * (like the PHP and Apache matcher dumpers).
     *
     * @param Route  $route      The route we are matching against
     * @param string $name       The name of the route
     * @param array  $attributes An array of attributes from the matcher
     *
     * @return array An array of parameters
     */
    protected function getAttributes(Route $route, $name, array $attributes)
    {
        $attributes['_route'] = $name;

        return $this->mergeDefaults($attributes, $route->getDefaults());
    }
}