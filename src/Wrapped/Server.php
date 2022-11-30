<?php
/**
 * Copyright (c) 2014 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace BInfotech\SMB\Wrapped;

use BInfotech\SMB\AbstractServer;
use BInfotech\SMB\Exception\AuthenticationException;
use BInfotech\SMB\Exception\ConnectException;
use BInfotech\SMB\Exception\ConnectionException;
use BInfotech\SMB\Exception\ConnectionRefusedException;
use BInfotech\SMB\Exception\Exception;
use BInfotech\SMB\Exception\InvalidHostException;
use BInfotech\SMB\IShare;
use BInfotech\SMB\ISystem;

class Server extends AbstractServer {
	/**
	 * Check if the smbclient php extension is available
	 *
	 * @param ISystem $system
	 * @return bool
	 */
	public static function available(ISystem $system): bool {
		return $system->getSmbclientPath() !== null;
	}

	private function getAuthFileArgument(): string {
		if ($this->getAuth()->getUsername()) {
			return '--authentication-file=' . $this->system->getFD(3);
		} else {
			return '';
		}
	}

	/**
	 * @return IShare[]
	 *
	 * @throws AuthenticationException
	 * @throws InvalidHostException
	 * @throws ConnectException
	 */
	public function listShares(): array {
		$maxProtocol = $this->options->getMaxProtocol();
		$minProtocol = $this->options->getMinProtocol();
		$smbClient = $this->system->getSmbclientPath();
		if ($smbClient === null) {
			throw new Exception("Backend not available");
		}
		$command = sprintf(
			'%s %s %s %s %s -L %s',
			$smbClient,
			$this->getAuthFileArgument(),
			$this->getAuth()->getExtraCommandLineArguments(),
			$maxProtocol ? "--option='client max protocol=" . $maxProtocol . "'" : "",
			$minProtocol ? "--option='client min protocol=" . $minProtocol . "'" : "",
			escapeshellarg('//' . $this->getHost())
		);
		$connection = new RawConnection($command);
		$connection->writeAuthentication($this->getAuth()->getUsername(), $this->getAuth()->getPassword());
		$connection->connect();
		if (!$connection->isValid()) {
			throw new ConnectionException((string)$connection->readLine());
		}

		$parser = new Parser('UTC');

		$output = $connection->readAll();
		if (isset($output[0])) {
			$parser->checkConnectionError($output[0]);
		}

		// sometimes we get an empty line first
		if (count($output) < 2) {
			$output = $connection->readAll();
		}

		if (isset($output[0])) {
			$parser->checkConnectionError($output[0]);
		}
		if (count($output) === 0) {
			throw new ConnectionRefusedException();
		}

		$shareNames = $parser->parseListShares($output);

		$shares = [];
		foreach ($shareNames as $name => $_description) {
			$shares[] = $this->getShare($name);
		}
		return $shares;
	}

	/**
	 * @param string $name
	 * @return IShare
	 */
	public function getShare(string $name): IShare {
		return new Share($this, $name, $this->system);
	}
}
