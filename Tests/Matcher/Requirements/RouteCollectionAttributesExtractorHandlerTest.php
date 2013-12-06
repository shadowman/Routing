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
use Symfony\Component\Routing\Matcher\Requirements\HostValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RegExpValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RouteValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionHandlerContext;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionAttributesExtractorHandler;

class RouteCollectionAttributesExtractorHandlerTest extends \PHPUnit_Framework_TestCase
{
	const VALID 		= true;
	const ROUTE_NAME 	= "foo_route";

	private function createFakeRoute() {
		$route = new Route(
			'/foo/{id}', 
			array('_controller' => 'foo'),
			array('_method' => 'post')
		);
		return $route;
	}

	private function createFakeRouteValidationResult() {

		$routeResults = array(
			new RegExpValidationResult(self::VALID, array('id' => 'bar')),
			new HostValidationResult(self::VALID, array('http://www.google.com'))
		);

		$result = new RouteValidationResult(
			self::VALID, 
			self::ROUTE_NAME, 
			$this->createFakeRoute(self::ROUTE_NAME), 
			$routeResults
		);

		return $result;
	}

	/**
	 * @test
	 */
	public function itReturnsTheAttributesForTheRouteAccordingToCollectedResultsInContext() {
		$collectionResults = array(
			$this->createFakeRouteValidationResult()
		);

		$handler = new RouteCollectionAttributesExtractorHandler();
		
		$result = $handler->handle(
			new RouteCollectionHandlerContext($collectionResults)
		);

		$this->assertEquals(
			array(
				'_controller' 	=> 'foo',
				'id'			=> 'bar',
				'_route' 		=> self::ROUTE_NAME
			), 
			$result
		);
	}
}