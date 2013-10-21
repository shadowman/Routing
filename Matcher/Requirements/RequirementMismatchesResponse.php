<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\RequirementResponse;

class RequirementMismatchesResponse extends RequirementResponse {
	public function matches() {
		return false;
	}
}