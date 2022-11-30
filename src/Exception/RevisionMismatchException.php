<?php
/**
 * Copyright (c) 2014 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Tecnovix\SMB\Exception;

use Throwable;

class RevisionMismatchException extends Exception {
	public function __construct(string $message = 'Protocol version mismatch', int $code = 0, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}
