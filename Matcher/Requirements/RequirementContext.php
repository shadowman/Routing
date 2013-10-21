<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;

class RequirementContext {
	
	protected $path;
	public function setPath($path) {
		$this->path =  $path;
	}
	public function getPath() {
		return $this->path;
	}

	protected $route;
	public function setRoute($route) {
		$this->route =  $route;
	}
	public function getRoute() {
		return $this->route;
	}
	
	protected $route_name;
	public function setRouteName($name) {
		$this->route_name =  $name;
	}
	public function getRouteName() {
		return $this->route_name;
	}
	
	protected $request;
	public function setRequest(Request $request = NULL) {
		$this->request =  $request;
	}
	public function getRequest() {
		return $this->request;
	}

	protected $request_context;
	public function setRequestContext(RequestContext $request_context = NULL) {
		$this->request_context =  $request_context;
	}
	public function getRequestContext() {
		return $this->request_context;
	}

}