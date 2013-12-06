<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\RouteCollectionValidationContext;

interface RouteCollectionHandlerInterface {
	public function handle(RouteCollectionHandlerContext $context);
}