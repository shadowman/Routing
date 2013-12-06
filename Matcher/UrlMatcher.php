<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Matcher;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\Requirements\SchemaRequirementMatcher;
use Symfony\Component\Routing\Matcher\Requirements\RegExpRequirementMatcher;
use Symfony\Component\Routing\Matcher\Requirements\ExpressionRequirementMatcher;
use Symfony\Component\Routing\Matcher\Requirements\HostRequirementMatcher;
use Symfony\Component\Routing\Matcher\Requirements\MethodRequirementMatcher;
use Symfony\Component\Routing\Matcher\Requirements\RequirementContext;
use Symfony\Component\Routing\Matcher\Requirements\ValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\OkValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\KoValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\HostValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RegExpValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RouteValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\HttpMethodValidationResult;
use Symfony\Component\Routing\Matcher\Requirements\RouteValidationContext;
use Symfony\Component\Routing\Matcher\Requirements\RouteValidationHandler;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionValidationHandler;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionAttributesExtractorHandler;
use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionHandlerContext;


use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * UrlMatcher matches URL based on a set of routes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class UrlMatcher implements UrlMatcherInterface, RequestMatcherInterface
{
    /**
     * @var RequestContext
     */
    protected $context;

    /**
     * @var RouteCollection
     */
    protected $routes;

    protected $request;
    
    protected $matchers;

    /**
     * Constructor.
     *
     * @param RouteCollection $routes  A RouteCollection instance
     * @param RequestContext  $context The context
     *
     * @api
     */
    public function __construct(RouteCollection $routes, RequestContext $context)
    {
        $this->routes = $routes;
        $this->context = $context;
        
        // Matchers
        $this->matchers = array( 
            new RegExpRequirementMatcher(),
            new HostRequirementMatcher(),
            new SchemaRequirementMatcher(),
            new ExpressionRequirementMatcher(),
            new MethodRequirementMatcher()
        );

        $this->routeHandlers = array(
            new RouteValidationHandler()
        );

        $this->routeCollectionHandlers = array(
            new RouteCollectionValidationHandler(),
            new RouteCollectionAttributesExtractorHandler()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        return $this->matchCollection(
            rawurldecode($pathinfo), 
            $this->routes
        );
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $this->request = $request;

        $ret = $this->match($request->getPathInfo());

        $this->request = null;

        return $ret;
    }

    /**
     * Tries to match a URL with a set of routes.
     *
     * @param string          $pathinfo The path info to be parsed
     * @param RouteCollection $routes   The set of routes
     *
     * @return array An array of parameters
     *
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     */
    protected function matchCollection($pathinfo, RouteCollection $routes)
    {
        $allResults = array();
        
        foreach ($routes as $name => $route) {
            $routeResult = $this->matchRoute($pathinfo, $name, $route);
            $allResults[] = $routeResult;
            if ($routeResult->isValid()) {
                break;    
            }
        }

        $handlerResult = $this->handleRouteCollectionValidation($allResults);
        return $handlerResult;        
    }

    protected function handleRouteCollectionValidation(array $allRoutesResults) {
        $handlerResult = NULL;
        foreach ($this->routeCollectionHandlers as $handler) {
            $handlerResult = $handler->handle(
                new RouteCollectionHandlerContext($allRoutesResults)
            );
            if ($handlerResult != NULL) {
                break; 
            }
        }
        return $handlerResult;
    }
    
    private function applyRequirementMatchers($path, $routeName, Route $route) {
        $matchersResults    = array();
     
        foreach ($this->matchers as $matcher) {
            $context    = $this->buildRequirementContext(
                $path, 
                $routeName, 
                $route
            );
            $matchersResult     = $matcher->match($context);
            $matchersResults[]  = $matchersResult;
        }
        return $matchersResults;
    }

    private function matchRoute($path, $routeName, Route $route) {
        
        $matchersResults    = $this->applyRequirementMatchers(
            $path, 
            $routeName, 
            $route
        );

        $routeValidationHandlerResult = $this->handleRouteValidation(
            $route,
            $routeName,
            $matchersResults
        );

        return $routeValidationHandlerResult;
    }

    private function handleRouteValidation(Route $route, $routeName, $matchersResults) {
        $handlerResult = NULL;
        foreach($this->routeHandlers as $handler) {
            
            $handlerResult = $routeValidationHandlerResult = $handler->handle(
                new RouteValidationContext(
                    $route,
                    $routeName,
                    $matchersResults
                )
            );

            if ($handlerResult !== NULL) {
                break;
            }
        }
        return $handlerResult;
    }

    private function buildRequirementContext($path, $routeName, Route $route) {
        $context = new RequirementContext();
        $context->setRoute($route);
        $context->setRouteName($routeName);
        $context->setRequestContext($this->context);
        $context->setPath($path);
        // TODO: Avoid this terrible idea of using the instance variable
        //       set on a method call it makes the class not thread safe...
        $context->setRequest($this->request);
        return $context;
    }

    /**
     * Returns an array of values to use as request attributes.
     *
     * As this method requires the Route object, it is not available
     * in matchers that do not have access to the matched Route instance
     * (like the PHP and Apache matcher dumpers).
     *
     * @param Route  $route      The route we are matching against
     * @param string $name       The name of the route
     * @param array  $attributes An array of attributes from the matcher
     *
     * @return array An array of parameters
     */
    protected function getAttributes(Route $route, $name, array $attributes)
    {
        $attributes['_route'] = $name;

        return $this->mergeDefaults($attributes, $route->getDefaults());
    }

    /**
     * Get merged default parameters.
     *
     * @param array $params   The parameters
     * @param array $defaults The defaults
     *
     * @return array Merged default parameters
     */
    protected function mergeDefaults($params, $defaults)
    {
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }
}
