<?php
/**
 * Copyright (c) 2014 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Tecnovix\SMB\Native;

use Tecnovix\SMB\AbstractServer;
use Tecnovix\SMB\Exception\AuthenticationException;
use Tecnovix\SMB\Exception\InvalidHostException;
use Tecnovix\SMB\IAuth;
use Tecnovix\SMB\IOptions;
use Tecnovix\SMB\IShare;
use Tecnovix\SMB\ISystem;
use Tecnovix\SMB\ITimeZoneProvider;

class NativeServer extends AbstractServer {
	/**
	 * @var NativeState
	 */
	protected $state;

	public function __construct(string $host, IAuth $auth, ISystem $system, ITimeZoneProvider $timeZoneProvider, IOptions $options) {
		parent::__construct($host, $auth, $system, $timeZoneProvider, $options);
		$this->state = new NativeState();
	}

	protected function connect(): void {
		$this->state->init($this->getAuth(), $this->getOptions());
	}

	/**
	 * @return IShare[]
	 * @throws AuthenticationException
	 * @throws InvalidHostException
	 */
	public function listShares(): array {
		$this->connect();
		$shares = [];
		$dh = $this->state->opendir('smb://' . $this->getHost());
		while ($share = $this->state->readdir($dh, '')) {
			if ($share['type'] === 'file share') {
				$shares[] = $this->getShare($share['name']);
			}
		}
		$this->state->closedir($dh, '');
		return $shares;
	}

	public function getShare(string $name): IShare {
		return new NativeShare($this, $name);
	}

	/**
	 * Check if the smbclient php extension is available
	 *
	 * @param ISystem $system
	 * @return bool
	 */
	public static function available(ISystem $system): bool {
		return $system->libSmbclientAvailable();
	}
}
