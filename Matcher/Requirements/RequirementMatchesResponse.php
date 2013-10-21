<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

use Symfony\Component\Routing\Matcher\Requirements\RequirementResponse;

class RequirementMatchesResponse extends RequirementResponse {
	public function matches() {
		return true;
	}
}