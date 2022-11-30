<?php
/**
 * Copyright (c) 2014 Raman Deep Bajwa <dbajwa763@gmail.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace BInfotech\SMB\Test;

use BInfotech\SMB\ACL;
use BInfotech\SMB\BasicAuth;
use BInfotech\SMB\Exception\InvalidArgumentException;
use BInfotech\SMB\IOptions;
use BInfotech\SMB\Native\NativeServer;
use BInfotech\SMB\Options;
use BInfotech\SMB\System;
use BInfotech\SMB\TimeZoneProvider;

class NativeShareTest extends AbstractShareTest {
	public function getServerClass(): string {
		$this->requireBackendEnv('libsmbclient');
		if (!function_exists('smbclient_state_new')) {
			$this->markTestSkipped('libsmbclient php extension not installed');
		}
		return NativeServer::class;
	}

	public function testProtocolMatch() {
		$options = new Options();
		$options->setMinProtocol(IOptions::PROTOCOL_SMB2);
		$options->setMaxProtocol(IOptions::PROTOCOL_SMB3);
		$server = new NativeServer(
			$this->config->host,
			new BasicAuth(
				$this->config->user,
				'test',
				$this->config->password
			),
			new System(),
			new TimeZoneProvider(new System()),
			$options
		);
		$server->listShares();
		$this->assertTrue(true);
	}

	public function testToLowMaxProtocol() {
		$this->expectException(InvalidArgumentException::class);
		$options = new Options();
		$options->setMaxProtocol(IOptions::PROTOCOL_NT1);
		$server = new NativeServer(
			$this->config->host,
			new BasicAuth(
				$this->config->user,
				'test',
				$this->config->password
			),
			new System(),
			new TimeZoneProvider(new System()),
			$options
		);
		$server->listShares();
	}
}
