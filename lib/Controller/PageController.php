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

use DateTime;
use DateTimeZone;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\Util;
use OCA\Adminly_Clients\Db\ClientMapper;
use OCP\IUserSession;
use OCA\Adminly_Clients\Db\Client;
use Exception;
use OCP\Activity\IManager as IActivityManager;
use OCA\DAV\CalDAV\CalDavBackend;
use Sabre\VObject\Reader;

class PageController extends Controller {

	/** @var IActivityManager */
	protected $activityManager;

	/** @var ClientMapper */
	private $mapper;

	/** @var string */
	private $userId;

	/** @var CalDavBackend */
	private $caldavBackend;

	public function __construct(IActivityManager $activityManager, string $AppName, IRequest $request, ClientMapper $mapper, IUserSession $userSession, CalDavBackend $calDavBackend) {
		parent::__construct($AppName, $request);
		$this->activityManager = $activityManager;
		$this->mapper = $mapper;
		$this->userId = $userSession->getUser()->getUID();
		$this->caldavBackend = $calDavBackend;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Render default template
	 */
	public function index(): TemplateResponse {
		Util::addScript($this->appName, 'adminly_clients-main');
		return new TemplateResponse('adminly_clients', 'main');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Creates a new client
	 */
	public function create(string $name, string $email, string $description) {
		try {
			$client = new Client();
			$client->setName($name);
			$client->setEmail($email);
			$client->setDescription($description);
			$client->setProviderId($this->userId);

			$new_client = $this->mapper->insert($client);

			$event = $this->activityManager->generateEvent();
			$event->setApp('adminly_clients')
				->setObject('client', $client->getId())
				->setType('clients')
				->setAffectedUser($this->userId)
				->setSubject(
					"client_add",
					[
						'client' => [
							'type' => 'addressbook-contact',
							'id' => $client->getId(),
							'name' => $client->getName()
						],
					]
				);

			$this->activityManager->publish($event);

			return $new_client;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Updates a client
	 */
	public function update(
		int $id,
		string $name,
		string $description = "",
		string $timezone = "",
		string $country = "",
		string $city = "",
		int $age = null,
		string $contacts = ""
	) {
		try {
			$client = $this->mapper->find($id, $this->userId);
			$client->setName($name);
			$client->setDescription($description);
			$client->setTimezone($timezone);
			$client->setCountry($country);
			$client->setCity($city);
			$client->setAge($age);
			$client->setContacts($contacts);
			return $this->mapper->update($client);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get all clients from the current user
	 */
	public function get(): array {
		$clients = $this->mapper->findAll($this->userId);

		$clientsArray = [];

		foreach ($clients as $client) {
			$clientsArray[] = $client->jsonSerialize();
		}
		return $clientsArray;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get all sessions for a specific client
	 */
	public function getClientSessions(int $clientId): array {
		$client = $this->mapper->find($clientId, $this->userId);

		$calendarId = $this->caldavBackend->getCalendarByUri("principals/users/{$this->userId}", "personal")["id"];

		$filters = [
			'name' => 'VCALENDAR',
			'comp-filters' => [
				[
					'name' => 'VEVENT',
					'comp-filters' => [],
					'prop-filters' => [
						[
							'name' => 'ATTENDEE',
							'is-not-defined' => false,
							'param-filters' => [],
							'text-match' => [
								'collation' => 'i;unicode-casemap',
								'negate-condition' => false,
								'value' => $client->getEmail(),
							],
						]
					],
					'is-not-defined' => false,
					'time-range' => null,
				]
			],
			'prop-filters' => [],
			'is-not-defined' => false,
			'time-range' => null,
		];

		$eventIds = $this->caldavBackend->calendarQuery($calendarId, $filters);

		$events = $this->caldavBackend->getMultipleCalendarObjects($calendarId, $eventIds);

		$sessions = [];

		foreach ($events as $event) {
			$eventData = Reader::read($event["calendardata"]);
			$dtstart = $eventData->vevent->dtstart->jsonSerialize();
			$date = new DateTime($dtstart[3], new DateTimeZone($dtstart[1]->tzid));
			$description = $eventData->vevent->description->jsonSerialize()[3];

			$sessions[] = [
				"title" => "Session",
				"date" => $date->format(DateTime::ISO8601),
				"description" => $description,
			];
		}

		// Sort sessions in descending order.
		usort($sessions, function($a, $b) {
			return $b['date'] <=> $a['date'];
		});

		return $sessions;
	}
}
