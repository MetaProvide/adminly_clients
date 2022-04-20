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

namespace OCA\Adminly_Clients\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\Util;
use OCA\Adminly_Clients\Db\ClientMapper;
use OCP\IUserSession;
use OCA\Adminly_Clients\Db\Client;

class PageController extends Controller {

	/** @var ClientMapper */
	private $mapper;

	/** @var string */
	private $userId;

	public function __construct(string $AppName, IRequest $request, ClientMapper $mapper, IUserSession $userSession) {
		parent::__construct($AppName, $request);
		$this->mapper = $mapper;
		$this->userId = $userSession->getUser()->getUID();
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Render default template
	 */
	public function index(): TemplateResponse {
		$clients = $this->mapper->findAll($this->userId);

		$clientsArray = [];

		foreach ($clients as $client) {
			$clientsArray[] = $client->jsonSerialize();
		}

		Util::addScript($this->appName, 'adminly_clients-main');
		return new TemplateResponse('adminly_clients', 'main', ['clients' => $clientsArray]);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Creates a new client
	 */
	public function create(string $name, string $email, string $description): String {
		$client = new Client();
		$client->setName($name);
		$client->setEmail($email);
		$client->setDescription($description);
		$client->setProviderId($this->userId);

		return (string) $this->mapper->create($client);
	}
}
