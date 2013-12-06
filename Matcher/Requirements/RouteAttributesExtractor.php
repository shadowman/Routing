<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class RouteCollectionAttributesExtractorHandler implements RouteCollectionHandler {
	public function extract() {

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

}