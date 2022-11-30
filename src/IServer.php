<?php
/**
 * @copyright Copyright (c) 2018 Raman Deep Bajwa <dbajwa763@gmail.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Tecnovix\SMB;

interface IServer {
	public function getAuth(): IAuth;

	public function getHost(): string;

	/**
	 * @return \Tecnovix\SMB\IShare[]
	 *
	 * @throws \Tecnovix\SMB\Exception\AuthenticationException
	 * @throws \Tecnovix\SMB\Exception\InvalidHostException
	 */
	public function listShares(): array;

	public function getShare(string $name): IShare;

	public function getTimeZone(): string;

	public function getSystem(): ISystem;

	public function getOptions(): IOptions;

	public static function available(ISystem $system): bool;
}
