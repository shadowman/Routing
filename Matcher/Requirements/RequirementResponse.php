<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

abstract class RequirementResponse {
	public abstract function matches();
}