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


namespace OCA\Adminly_Clients\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;
use Exception;

class ClientMapper extends QBMapper {
	public const TABLE_NAME = 'adminly_clients';

	public function __construct(IDBConnection $db) {
		parent::__construct($db, self::TABLE_NAME);
	}

	public function find(int $id, string $providerId) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id))
			)->andWhere(
				$qb->expr()->eq('provider_id', $qb->createNamedParameter($providerId))
			);

		return $this->findEntity($qb);
	}

	public function findAll(string $providerId) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('name', 'email', 'id', 'phone_number')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('provider_id', $qb->createNamedParameter($providerId))
			)->orderBy('id', 'desc');

		return $this->findEntities($qb);
	}

	public function findWithOffsetAndLimit(string $providerId, int $offset, int $limit) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('name', 'email', 'phone_number', 'timezone', 'id')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('provider_id', $qb->createNamedParameter($providerId))
			)->orderBy('id', 'desc')->setFirstResult($offset)->setMaxResults($limit);

		return $this->findEntities($qb);
	}

	public function findByEmail(string $email, string $providerId) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('email', $qb->createNamedParameter($email))
			)->andWhere(
				$qb->expr()->eq('provider_id', $qb->createNamedParameter($providerId))
			);

		try {
			$result = $this->findEntity($qb);
		} catch (\OCP\AppFramework\Db\DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			throw $e;
		}
		return $result;
	}

	public function findByName(string $name, string $providerId) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where($qb->expr()->iLike('name', $qb->createNamedParameter(
				'%' . $this->db->escapeLikeParameter($name) . '%'
			)))->andWhere(
				$qb->expr()->eq('provider_id', $qb->createNamedParameter($providerId))
			);

		try {
			$result = $this->findEntities($qb);
		} catch (\OCP\AppFramework\Db\DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			throw $e;
		}
		return $result;
	}

	public function count(string $providerId) {
		$qb = $this->db->getQueryBuilder();

		$qb->select($qb->func()->count('*'))
			->from($this->getTableName())->andWhere(
				$qb->expr()->eq('provider_id', $qb->createNamedParameter($providerId))
			);
		$result = $qb->executeQuery();
		$column = (int) $result->fetchOne();
		$result->closeCursor();
		return $column;
	}

	public function listProviders(){
		$qb = $this->db->getQueryBuilder();
		$qb->select('provider_id')
				->from($this->getTableName())
				->groupBy('provider_id');

		$result = $qb->executeQuery();
		$column = $result->fetchAll();
		$result->closeCursor();
		return $column;
	}
}
