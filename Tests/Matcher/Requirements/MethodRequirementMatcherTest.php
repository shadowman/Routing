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

use Symfony\Component\Routing\Matcher\Requirements\MethodRequirementMatcher;

class MethodRequirementMatcherTest extends \PHPUnit_Framework_TestCase
{
	const TEST_ROUTE_PATH = '/foo';
	const TEST_ROUTE_NAME = 'foo_route';

	protected $matcher;
	protected $httpContext;

	public function setUp() {
		$this->context = new RequestContext();
		$this->matcher = new MethodRequirementMatcher();
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
    public function itShouldMatchIfNoMethodRequirementsArePresent() {
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array());
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);
		$this->assertTrue($response->isValid());
    }

	/**
    * @test
    */
    public function itShouldMatchIfSingleMethodRequirementMatchesTheRequestsMethod() {
    	$this->context->setMethod('GET');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_method' => 'GET'));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);

        $this->assertTrue($response->isValid());
    }

	/**
    * @test
    */
    public function itShouldNotMatchIfTheRequestMethodIsNotAllowed() {
    	$this->context->setMethod('PUT');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_method' => 'GET'));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);
        $this->assertFalse($response->isValid());

    }

    /**
    * @test
    */
    public function itShouldReturnTheAllowedMethodsInTheMismatchResponse() {
    	$this->context->setMethod('PUT');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_method' => 'GET'));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);

		$this->assertEquals($response->getAllowedMethods(), array("GET"));
    }
	/**
    * @test
    */
    public function itShouldMatchIfAnyOfTheMultipleMethodRequirementMatchesTheRequestsMethod() {
    	$this->context->setMethod('PUT');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_method' => "GET|PUT"));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);

        $this->assertTrue($response->isValid());
    }

    /**
    * @test
    */
    public function itShouldMatchHEADAsIfItWereGET() {
    	$this->context->setMethod('HEAD');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_method' => "GET"));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);
        $this->assertTrue($response->isValid());
    }

    /**
    * @test
    */
    public function itShouldNotMatchIfTheMultipleMethodRequirementsDoNotMatchTheRequestsMethod() {
    	$this->context->setMethod('GET');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_method' => "PUT|POST"));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);
        $this->assertFalse($response->isValid());
    }

    /**
    * @test
    * @expectedException Symfony\Component\Routing\Exception\MethodNotAllowedException
    */
    public function itShouldThrowAMethodNotAllowedExceptionIfTheRequestMethodIsUnknown() {
    	$this->context->setMethod('UNKNOWN_METHOD');
    	$route 	= new Route(self::TEST_ROUTE_PATH, array(), array());
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$this->context
            )
    	);
    }
}

