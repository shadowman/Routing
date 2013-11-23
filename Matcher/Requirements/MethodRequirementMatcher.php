<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\Requirements\RequirementMatcherInterface;
use Symfony\Component\Routing\Matcher\Requirements\ValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\HttpMethodValidationResult;

class MethodRequirementMatcher implements RequirementMatcherInterface {
	
	const  METHODS_SEPARATOR= "|";
	const  METHOD_HEAD 		= "HEAD";
	const  METHOD_POST 		= "POST";
	const  METHOD_GET 		= "GET";
	const  METHOD_OPTIONS 	= "OPTIONS";
	const  METHOD_TRACE 	= "TRACE";
	const  METHOD_DELETE 	= "DELETE";
	const  METHOD_PUT 		= "PUT";

	protected $requestContext;
	protected $knownMethods;

	public function __construct(RequestContext $context = NULL) {
		$this->requestContext = $context;
		$this->knownMethods = array(
			self::METHOD_GET,
			self::METHOD_PUT,
			self::METHOD_POST,
			self::METHOD_DELETE,
			self::METHOD_HEAD,
			self::METHOD_OPTIONS,
			self::METHOD_TRACE
		);
	}
	
	public function match(RequirementContext $context) {
		$route 				= $context->getRoute();
		$requestContext		= $context->getRequestContext();
		$requiredMethods	= $this->extractRequiredMethods($route);
		$requestMethod		= $this->extractRequestMethod($requestContext);
		if (!$this->fulfillsConstraints($requestMethod, $requiredMethods)) {
             return new HttpMethodValidationResult(ValidationResult::KO, $requiredMethods);
        }
		return new HttpMethodValidationResult(ValidationResult::OK, $requiredMethods);
	}

	private function fulfillsConstraints($requestMethod, $requiredMethods) {
		if (!in_array($requestMethod, $this->knownMethods)) {
			throw new MethodNotAllowedException(
            	array_map('strtoupper', $requiredMethods)
            );	
		}

		$isValid = in_array($requestMethod, $requiredMethods);
		return $isValid;
	}

	private function extractRequestMethod(RequestContext $context) {
		$method = $context->getMethod();
		if ($method === self::METHOD_HEAD ) {
			// HEAD and GET are equivalent as per RFC
            $method = self::METHOD_GET;
		}
		return $method;
	}

	private function extractRequiredMethods(Route $route) {
		$methods 			= strtoupper($route->getRequirement('_method'));
		$extractedMethods 	= $this->knownMethods;
		if (stripos($methods, self::METHODS_SEPARATOR) || strlen($methods) > 0 ) {
			$extractedMethods = explode(self::METHODS_SEPARATOR, $methods);
			$extractedMethods = array_map('strtoupper', $extractedMethods);
			$extractedMethods = array_map('trim', $extractedMethods);	
		}
		return $extractedMethods;
	}
}