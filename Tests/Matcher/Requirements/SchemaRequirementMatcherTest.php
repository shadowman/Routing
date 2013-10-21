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

use Symfony\Component\Routing\Matcher\Requirements\SchemaRequirementMatcher;

class SchemaRequirementMatcherTest extends \PHPUnit_Framework_TestCase
{
	const TEST_ROUTE_PATH = '/foo';
	const TEST_ROUTE_NAME = 'foo_route';

	protected $matcher;
	protected $httpContext;

	public function setUp() {
		$this->context = new RequestContext();
		$this->matcher = new SchemaRequirementMatcher();
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
    public function ifContextPassedInConstructorAndParametersTheOneInParametersGetsUsed() {
    	$this->matcher 	= new SchemaRequirementMatcher($this->context);
    	$httpsContext 	= new RequestContext();
    	$httpsContext->setScheme('https');
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array('_scheme' => 'https'));
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::TEST_ROUTE_PATH,
        		self::TEST_ROUTE_NAME, 
        		$route,
        		$httpsContext
            )
    	);

		$this->assertInstanceOf(
    		'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
    		$response
    	);
    }

    /**
	* @test
	*/
    public function ifContextPassedInConstructorNoNeedToPassContextInMatchMethod() {
    	$this->matcher = new SchemaRequirementMatcher($this->context);
    	$route = new Route(self::TEST_ROUTE_PATH, array(), array('_scheme' => 'http'));
		
		$response = $this->matcher->match(
    		$this->createRequirementContextForRoute($route)
    	);

		$this->assertInstanceOf(
    		'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
    		$response
    	);
    }


	/**
	* @test
	*/
    public function ifSchemeRequirementIsDefinedAndMatchesContextThenReturnsRequirementMatchesResponse() {
    	$route = new Route(self::TEST_ROUTE_PATH, array(), array('_scheme' => 'http'));

    	$response = $this->matcher->match(
            $this->createRequirementContextForRoute($route)
    	);

    	$this->assertInstanceOf(
    		'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
    		$response
    	);
    }
	
	/**
	* @test
	*/
    public function ifSchemeRequirementIsNotDefinedReturnsRequirementMatchesResponse() {
    	$route = new Route(self::TEST_ROUTE_PATH, array(), array());

    	$response = $this->matcher->match(
            $this->createRequirementContextForRoute($route)
    	);
    	
    	$this->assertInstanceOf(
    		'Symfony\Component\Routing\Matcher\Requirements\RequirementMatchesResponse', 
    		$response
    	);
    }
	
	/**
	* @test
	*/
    public function ifSchemeRequirementIsDefinedAndMismatchesContextReturnsRequirementMismatchesResponse() {
    	$route = new Route(self::TEST_ROUTE_PATH, array(), array('_scheme' => 'https'));

    	$response = $this->matcher->match(
            $this->createRequirementContextForRoute($route)
    	);
    	
    	$this->assertInstanceOf(
    		'Symfony\Component\Routing\Matcher\Requirements\RequirementMismatchesResponse', 
    		$response
    	);
    }
}