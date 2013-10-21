<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Tests\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;

use Symfony\Component\Routing\Matcher\Requirements\RequirementContext;
use Symfony\Component\Routing\Matcher\Requirements\ExpressionRequirementMatcher;

class ExpressionRequirementMatcherTest extends \PHPUnit_Framework_TestCase {
	const TEST_ROUTE_PATH = '/foo';
	const TEST_ROUTE_NAME = 'foo_route';

	protected $matcher;
	protected $httpContext;

	public function setUp() {
		$this->context = new RequestContext();
		$this->matcher = new ExpressionRequirementMatcher();
	}

	private function createRequirementContextForRoute($route) {
		return $this->createRequirementContext(self::TEST_ROUTE_PATH, self::TEST_ROUTE_NAME, $route, $this->context);
	}

	private function createRequirementContext($path, $name, $route, $context) {
		$reqContext =  new RequirementContext();
		$reqContext->setPath($path);
		$reqContext->setRouteName($name);
		$reqContext->setRoute($route);
		$reqContext->setRequestContext($context);
		return $reqContext;
	}

	/**
	* @test
	*/
	public function ifTheExpressionEvaluatesFalseThenItReturnsRequirementMismatchesResponse() {
		$route = new Route('/foo');
        $route->setCondition('context.getMethod() != "GET"');
        $context = $this->createRequirementContextForRoute($route);
		
		$response = $this->matcher->match($context);
		
		$this->assertInstanceOf(
			'Symfony\Component\Routing\Matcher\Requirements\RequirementMismatchesResponse', 
			$response
		);
	}
	
	/**
	* @test
	*/
	public function itEvaluatesContextToTheCurrentRequestContext() {
		$route = new Route('/foo');
        $route->setCondition('context != NULL');

        $context = $this->createRequirementContextForRoute($route);

		$response = $this->matcher->match($context);
		
		$this->assertInstanceOf(
			'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
			$response
		);
	}

	/**
	* @test
	*/
	public function ifTheExpressionEvaluatesTrueThenItReturnsRequirementMatchesResponse() {
		$this->context->setMethod('POST');
		$route = new Route('/foo');
        $route->setCondition('context.getMethod() == "POST"');
        $context = $this->createRequirementContextForRoute($route);

		$response = $this->matcher->match($context);
		
		$this->assertInstanceOf(
			'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
			$response
		);
	}

	/**
	* @test
	*/
	public function ifNoConditionIsSetThenReturnsRequirementMatchesResponse() {
		$route = new Route('/foo');
        $context = $this->createRequirementContextForRoute($route);

		$response = $this->matcher->match($context);
		
		$this->assertInstanceOf(
			'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
			$response
		);
	}

}