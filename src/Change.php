<?php
/**
 * @copyright Copyright (c) 2016 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 *
 */

namespace BInfotech\SMB;

class Change {
	/** @var int */
	private $code;
	/** @var string */
	private $path;

	public function __construct(int $code, string $path) {
		$this->code = $code;
		$this->path = $path;
	}

	public function getCode(): int {
		return $this->code;
	}

	public function getPath(): string {
		return $this->path;
	}
}
