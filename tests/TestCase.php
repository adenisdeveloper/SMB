<?php
/**
 * Copyright (c) 2015 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Tecnovix\SMB\Test;

abstract class TestCase extends \PHPUnit\Framework\TestCase {
	protected function requireBackendEnv($backend) {
		if (getenv('BACKEND') and getenv('BACKEND') !== $backend) {
			$this->markTestSkipped('Skipping tests for ' . $backend . ' backend');
		}
	}
}
