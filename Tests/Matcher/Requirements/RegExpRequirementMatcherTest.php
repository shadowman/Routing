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

use Symfony\Component\Routing\Matcher\Requirements\RegExpRequirementMatcher;

class RegExpRequirementMatcherTest extends \PHPUnit_Framework_TestCase
{
	const TEST_ROUTE_PATH = '/foo/{name}';
	const WRONG_ROUTE_PATH = '/foo2/{name}';
	const TEST_ROUTE_NAME = 'foo_route';

	protected $matcher;
	protected $httpContext;

	public function setUp() {
		$this->context = new RequestContext();
		$this->matcher = new RegExpRequirementMatcher();
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
    public function itShouldNotMatchIfRegExpDoesNotMatches() {
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array());
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		self::WRONG_ROUTE_PATH,
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
    public function itShouldNotMatchIfStaticPrefixDoesNotMatch() {
    	$route 			= new Route(self::TEST_ROUTE_PATH, array(), array());
		
		$response = $this->matcher->match(
            $this->createRequirementContext(
        		"helloo",
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
    public function itShouldMatchIfStaticPrefixMatchesAndRegExpMatches() {
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

}