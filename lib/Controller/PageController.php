<?php

declare(strict_types=1);

/**
 * Adminly Clients
 *
 * @copyright Copyright (C) 2022  Magnus Walbeck <magnus@metaprovide.org>
 *
 * @author Magnus Walbeck <magnus@metaprovide.org>
 * @author Igor Oliveira <igoroliveira@metaprovide.org>
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
use OCA\Adminly_Clients\Db\Client;
use Exception;
use OCP\Activity\IManager as IActivityManager;
use OCA\DAV\CalDAV\CalDavBackend;
use Sabre\VObject\Reader;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;

class PageController extends Controller
{

	/** @var IActivityManager */
	protected $activityManager;

	/** @var ClientMapper */
	private $mapper;

	/** @var string */
	private $userId;

	/** @var CalDavBackend */
	private $caldavBackend;

	public function __construct(
		IActivityManager $activityManager,
		string $AppName,
		IRequest $request,
		ClientMapper $mapper,
		CalDavBackend $calDavBackend,
		?string $userId
	) {
		parent::__construct($AppName, $request);
		$this->activityManager = $activityManager;
		$this->mapper = $mapper;
		$this->userId = $userId;
		$this->caldavBackend = $calDavBackend;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Render default template
	 */
	public function index(): TemplateResponse
	{
		Util::addScript($this->appName, 'adminly_clients-main');
		return new TemplateResponse('adminly_clients', 'main');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Creates a new client
	 */
	public function create(string $name, string $email, string $description = "", string $phoneNumber = "", string $city = "", string $country = "")
	{
		try {
			if (!$this->mapper->findByEmail($email, $this->userId)) {
				$client = new Client();
				$client->setName($name);
				$client->setEmail(strtolower($email));
				$client->setDescription($description);
				$client->setPhoneNumber($phoneNumber);
				$client->setCity($city);
				$client->setCountry($country);
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
			} else {
				return new JSONResponse("Client with email $email already exists", Http::STATUS_BAD_REQUEST);
			}
		} catch (Exception $e) {
			$this->handleException($e);
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
		string $contacts = "",
		string $phoneNumber = "",
		string $email = ""
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
			$client->setPhoneNumber($phoneNumber);

			if ($email) {
				$oldEmail = $client->getEmail();
				$client->setEmail(strtolower($email));
				$this->updateClientSessionsEmail($client, $oldEmail);
			}

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
	public function get(): array
	{
		$clients = $this->mapper->findAll($this->userId);

		$clientsArray = [];

		foreach ($clients as $client) {
			$client = $client->jsonSerialize();
			$client['nextSession'] = $this->getClientNextSession($client['id']);
			$client['lastSession'] = $this->getClientLastSession($client['id']);
			$clientsArray[] = $client;
		}
		return $clientsArray;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get clients of a page from the current user
	 */
	public function getPage(int $pageNumber, int $clientsPerPage): array
	{
		$offset = $clientsPerPage * ($pageNumber - 1);
		$clients = $this->mapper->findWithOffsetAndLimit($this->userId, $offset, $clientsPerPage);

		$clientsArray = [];

		foreach ($clients as $client) {
			$client = $client->jsonSerialize();
			$client['nextSession'] = $this->getClientNextSession($client['id']);
			$client['lastSession'] = $this->getClientLastSession($client['id']);
			$clientsArray[] = $client;
		}
		return $clientsArray;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get the number of clients from the current user
	 */
	public function getNumberOfClients()
	{
		$clientNumber = $this->mapper->count($this->userId);
		return $clientNumber;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Searchs clients from the current user
	 */
	public function searchByName($name): array
	{
		$clients = $this->mapper->findByName($name, $this->userId);
		$clientsArray = [];

		foreach ($clients as $client) {
			$client = $client->jsonSerialize();
			$client['nextSession'] = $this->getClientNextSession($client['id']);
			$client['lastSession'] = $this->getClientLastSession($client['id']);
			$clientsArray[] = $client;
		}
		return $clientsArray;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Updates a client
	 */
	public function delete(int $id)
	{
		try {
			$client = $this->mapper->find($id, $this->userId);
			$calendarId = $this->caldavBackend->getCalendarByUri("principals/users/{$this->userId}", "personal")["id"];
			$sessions = $this->getClientSessions($client->getId());

			foreach ($sessions as $session) {
				$this->caldavBackend->deleteCalendarObject($calendarId, $session["uri"]);
			}

			return $this->mapper->delete($client);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get all sessions for a specific client
	 */
	public function getClientSessions(int $clientId): array
	{
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
				"uri" => $event["uri"],
			];
		}

		// Sort sessions in descending order.
		usort($sessions, function ($a, $b) {
			return $b["date"] <=> $a["date"];
		});

		return $sessions;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get next session for a specific client
	 */
	public function getClientNextSession(int $clientId)
	{
		$dateNow = new DateTime();
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
						],
						[
							'name' => 'DTSTART',
							'is-not-defined' => false,
							'param-filters' => [],
							'text-match' => [],
							'time-range' => ['start' => $dateNow],
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

		if ($eventIds) {
			$events = $this->caldavBackend->getMultipleCalendarObjects($calendarId, $eventIds);

			$sessionsDates = [];

			foreach ($events as $event) {
				$eventData = Reader::read($event["calendardata"]);
				$dtstart = $eventData->vevent->dtstart->jsonSerialize();
				$date = new DateTime($dtstart[3], new DateTimeZone($dtstart[1]->tzid));

				$sessionsDates[] = $date->format(DateTime::ISO8601);
			}

			// Sort sessions in ascending order.
			usort($sessionsDates, function ($a, $b) {
				return $a <=> $b;
			});

			return $sessionsDates[0];
		}
		return null;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Get last session for a specific client
	 */
	public function getClientLastSession(int $clientId)
	{
		$dateNow = new DateTime();

		$sixWeeksAgo = new DateTime();
		$sixWeeksAgo = $sixWeeksAgo->modify("-6 week");

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
						],
						[
							'name' => 'DTSTART',
							'is-not-defined' => false,
							'param-filters' => [],
							'text-match' => [],
							'time-range' => ['start' => $sixWeeksAgo, 'end' => $dateNow],
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
		if ($eventIds) {
			$events = $this->caldavBackend->getMultipleCalendarObjects($calendarId, $eventIds);

			$sessionsDates = [];

			foreach ($events as $event) {
				$eventData = Reader::read($event["calendardata"]);
				$dtstart = $eventData->vevent->dtstart->jsonSerialize();
				$date = new DateTime($dtstart[3], new DateTimeZone($dtstart[1]->tzid));

				$sessionsDates[] = $date->format(DateTime::ISO8601);
			}

			// Sort sessions in descending order.
			usort($sessionsDates, function ($a, $b) {
				return $b <=> $a;
			});

			return $sessionsDates[0];
		}
		return null;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Updates the email on the sessions for a specific client
	 */
	public function updateClientSessionsEmail($client, string $oldEmail)
	{
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
								'value' => $oldEmail,
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
		if ($eventIds) {
			$events = $this->caldavBackend->getMultipleCalendarObjects($calendarId, $eventIds);

			foreach ($events as $event) {
				// updates the attendee email
				$eventData = Reader::read($event["calendardata"]);
				$eventData->vevent->attendee->setValue("mailto:" . $client->getEmail());

				// updates description
				$description = $eventData->vevent->description->getValue();
				$newDescription = str_replace($oldEmail, $client->getEmail(), $description);

				$eventData->vevent->description->setValue($newDescription);

				$this->caldavBackend->updateCalendarObject($calendarId, $event["uri"], $eventData->serialize());
			}
		}
	}
}
