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

use Symfony\Component\Routing\Matcher\Requirements\ExpressionRequirementMatcher;

class ExpressionRequirementMatcherTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->matcher = new ExpressionRequirementMatcher();
	}

	/**
	* @test
	*/
	public function ifTheExpressionEvaluatesFalseThenItReturnsRequirementMismatchesResponse() {
		$this->fail();
	}
	
	/**
	* @test
	*/
	public function ifTheExpressionEvaluatesTrueThenItReturnsRequirementMatchesResponse() {
		$this->fail();
	}

	/**
	* @test
	*/
	public function ifNoConditionIsSetThenReturnsItRequirementMatchesResponse() {
		$this->fail();
	}

}