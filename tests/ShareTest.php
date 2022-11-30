<?php
/**
 * Copyright (c) 2014 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace BInfotech\SMB\Test;

use BInfotech\SMB\BasicAuth;
use BInfotech\SMB\Exception\ConnectException;
use BInfotech\SMB\Exception\DependencyException;
use BInfotech\SMB\Options;
use BInfotech\SMB\System;
use BInfotech\SMB\TimeZoneProvider;
use BInfotech\SMB\Wrapped\Server as NormalServer;

class ShareTest extends AbstractShareTest {
	public function getServerClass(): string {
		$this->requireBackendEnv('smbclient');
		return NormalServer::class;
	}

	public function testAppendStream() {
		$this->expectException(DependencyException::class);
		$this->share->append($this->root . '/foo');
	}

	public function testHostEscape() {
		$this->expectException(ConnectException::class);
		$this->requireBackendEnv('smbclient');
		$this->config = json_decode(file_get_contents(__DIR__ . '/config.json'));
		$this->server = new NormalServer(
			$this->config->host . ';asd',
			new BasicAuth(
				$this->config->user,
				'test',
				$this->config->password
			),
			new System(),
			new TimeZoneProvider(new System()),
			new Options()
		);
		$share = $this->server->getShare($this->config->share);
		$share->dir($this->root);
	}
}
