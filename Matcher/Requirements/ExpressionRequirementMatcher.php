<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\Requirements\RequirementMatcherInterface;
use Symfony\Component\Routing\Matcher\Requirements\ValidationResult;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionRequirementMatcher  implements RequirementMatcherInterface {
	
	protected $expressionLanguage;
	protected $context;

	public function __construct(RequestContext $context = NULL, Request $request = NULL) {
		$this->context = $context;
		$this->request = $request;
	}

	//TODO: Build/load somewhere else... Mixing reflection with the logic is... sucky
	protected function buildExpressionEvaluator()
    {
        if (null === $this->expressionLanguage) {
            if (!class_exists('Symfony\Component\ExpressionLanguage\ExpressionLanguage')) {
                throw new \RuntimeException('Unable to use expressions as the Symfony ExpressionLanguage component is not installed.');
            }
            $this->expressionLanguage = new ExpressionLanguage();
        }

        return $this->expressionLanguage;
    }

	public function match(RequirementContext $context) {
		
		$route = $context->getRoute();

		if ($route->getCondition()) {
			$variables 			= array(
				'context' => $context->getRequestContext(), 
				'request' => $context->getRequest()
			);
			
			$expression = $this->buildExpressionEvaluator();
			$evaluationIsTrue = $expression->evaluate(
				$route->getCondition(), 
				$variables
			);
			
			if (!$evaluationIsTrue) {
            	return new ConditionValidationResult(
            		ValidationResult::KO, 
            		$route->getCondition()
            	);
			}
        }

		return new ConditionValidationResult(
    		ValidationResult::OK, 
    		$route->getCondition()
    	);
	}


}