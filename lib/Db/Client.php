<?php

declare(strict_types=1);

/**
 * Adminly Clients
 *
 * @copyright Copyright (C) 2022  Magnus Walbeck <magnus@metaprovide.org>
 *
 * @author Magnus Walbeck <magnus@metaprovide.org>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
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

namespace OCA\Adminly_Clients\Db;

use OCP\AppFramework\Db\Entity;

class Client extends Entity {

	/** @var int */
	protected $providerId;

	/** @var string */
	protected $email;

	/** @var string */
	protected $name;

	/** @var string */
	protected $description;

	public function __construct() {
		$this->addType('id', 'integer');
		$this->addType('providerId', 'integer');
		$this->addType('email', 'string');
		$this->addType('name', 'string');
		$this->addType('description', 'string');
	}

	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'providerId' => $this->providerId,
			'email' => $this->email,
			'name' => $this->name,
			'description' => $this->description
		];
	}
}
