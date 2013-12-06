<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\RouteValidationContext;

interface RouteValidationHandlerInterface {
	public function handle(RouteValidationContext $context);
}