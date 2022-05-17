<?php

/**
 * @copyright Copyright (c) 2022 Igor Oliveira <igor.oliveira@metaprovide.org>
 *
 * @author Igor Oliveira <igor.oliveira@metaprovide.org>
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Adminly_Clients\Activity;

use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Activity\IProvider;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;
use OCP\Activity\IEventMerger;

class Provider implements IProvider {
	public const SUBJECT_ADD = 'client_add';
	public const SUBJECT_UPDATE = 'client_update';
	public const SUBJECT_DELETE = 'client_delete';

	/** @var IFactory */
	protected $languageFactory;

	/** @var IL10N */
	protected $l;

	/** @var IManager */
	protected $activityManager;

	/** @var IUrlGenerator */

	protected $url;

	/** @var IEventMerger */

	protected $eventMerger;

	/**
	 * @param IFactory $languageFactory
	 * @param IURLGenerator $url
	 * @param IManager $activityManager
	 * @param IGroupManager $groupManager
	 * @param IEventMerger $eventMerger
	 */
	public function __construct(IFactory $languageFactory, IURLGenerator $url, IManager $activityManager, IEventMerger $eventMerger) {
		$this->languageFactory = $languageFactory;
		$this->url = $url;
		$this->activityManager = $activityManager;
		$this->eventMerger = $eventMerger;
	}

	/**
	 * @param string $language
	 * @param IEvent $event
	 * @param IEvent|null $previousEvent
	 * @return IEvent
	 * @throws \InvalidArgumentException
	 * @since 11.0.0
	 */
	public function parse($language, IEvent $event, IEvent $previousEvent = null) {
		$subjectParameters = $event->getSubjectParameters();

		if ($event->getApp() !== 'adminly_clients' || $event->getType() !== 'clients') {
			throw new \InvalidArgumentException();
		}

		$this->l = $this->languageFactory->get('adminly_clients', $language);

		if ($this->activityManager->getRequirePNG()) {
			$event->setIcon($this->url->getAbsoluteURL($this->url->imagePath('core', 'places/calendar-dark.png')));
		} else {
			$event->setIcon($this->url->getAbsoluteURL($this->url->imagePath('core', 'places/calendar.svg')));
		}

		if ($event->getSubject() === self::SUBJECT_ADD) {
			$subject = 'You created client {client}';
		} elseif ($event->getSubject() === self::SUBJECT_DELETE) {
			$subject = 'You deleted client {client}';
		} elseif ($event->getSubject() === self::SUBJECT_UPDATE) {
			$subject = 'You updated client {client}';
		} else {
			throw new \InvalidArgumentException();
		}

		$event->setParsedSubject(str_replace("{client}", $subjectParameters['client'], $subject));
		// ->setRichSubject($subject, $subjectParameters);

		$event = $this->eventMerger->mergeEvents('clients', $event, $previousEvent);

		return $event;
	}
}
