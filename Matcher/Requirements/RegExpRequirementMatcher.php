<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\Requirements\RequirementMatcherInterface;
use Symfony\Component\Routing\Matcher\Requirements\RegExpValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\OkValidationResult;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RegExpRequirementMatcher  implements RequirementMatcherInterface {
    
    public function match(RequirementContext $context) {
        $route               = $context->getRoute();
        $path                = $context->getPath();
        $compiledRoute       = $route->compile();
        $results[]           = $this->staticPrefixCheck($path, $compiledRoute);
        $results[]           = $this->pregCheck($path, $compiledRoute);
        $validationResult    = $this->resolveValidationBasedOnCheckResults($results);
        return $validationResult;
    }

    private function resolveValidationBasedOnCheckResults(array $results) {
        $validationResult    = new RegExpValidationResult(ValidationResult::KO, $results);
        if ($results[0]->isValid() && $results[1]->isValid()) {
            $validationResult    = new RegExpValidationResult(ValidationResult::OK, $results);
        }
        return $validationResult;
    }

    private function staticPrefixCheck($pathinfo, $compiledRoute) {
        // check the static prefix of the URL first. Only use the more expensive preg_match when it matches
        if ('' !== $compiledRoute->getStaticPrefix() && 0 !== strpos($pathinfo, $compiledRoute->getStaticPrefix())) {
            return new RegExpValidationResult(ValidationResult::KO);
        }
        return new RegExpValidationResult(ValidationResult::OK);
    }

    private function pregCheck($pathinfo, $compiledRoute) {
        if (!preg_match($compiledRoute->getRegex(), $pathinfo, $matches)) {
            return new RegExpValidationResult(ValidationResult::KO, $matches);
        }
        return new RegExpValidationResult(ValidationResult::OK, $matches);
    }
}