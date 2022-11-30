<?php
/**
 * Copyright (c) 2014 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Tecnovix\SMB\Exception;

class InvalidRequestException extends Exception {
	/**
	 * @var string
	 */
	protected $path;

	public function __construct(string $path = "", int $code = 0, \Throwable $previous = null) {
		$class = get_class($this);
		$parts = explode('\\', $class);
		$baseName = array_pop($parts);
		parent::__construct('Invalid request for ' . $path . ' (' . $baseName . ')', $code, $previous);
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
}
