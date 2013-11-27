<?php

namespace Symfony\Component\Routing\Matcher\Requirements;

class HostValidationResult extends ValidationResult {
	protected $hosts = array();
	
	public function __construct($isValid, $hosts = array(), $results = NULL) {
		parent::__construct($isValid, $results);
		$this->hosts = $hosts;
	}

	public function getHosts() {
		return $this->hosts;
	}
}