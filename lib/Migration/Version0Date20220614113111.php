<?php

declare(strict_types=1);

namespace OCA\Adminly_Clients\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;
use OCP\DB\Types;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version0Date20220614113111 extends SimpleMigrationStep {
	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable("adminly_clients")) {
			$table = $schema->createTable("adminly_clients");
			$table->addColumn('id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 20,
			]);
			$table->addColumn('provider_id', Types::STRING, [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('email', Types::STRING, [
				'notnull' => true,
				'length' => 250,
			]);
			$table->addColumn('name', Types::STRING, [
				'notnull' => true,
				'length' => 250,
			]);
			$table->addColumn('description', Types::TEXT, [
				'notnull' => false,
				'default' => ''
			]);
			$table->addColumn('phone_number', Types::STRING, [
				'notnull' => false,
				'default' => '',
				'length' => 64,
			]);
			$table->addColumn('timezone', Types::STRING, [
				'notnull' => false,
				'default' => '',
				'length' => 64,
			]);
			$table->addColumn('country', Types::STRING, [
				'notnull' => false,
				'default' => '',
				'length' => 250,
			]);
			$table->addColumn('city', Types::STRING, [
				'notnull' => false,
				'default' => '',
				'length' => 250,
			]);
			$table->addColumn('age', Types::SMALLINT, [
				'notnull' => false,
				'length' => 3,
				'unsigned' => true,
			]);
			$table->addColumn('contacts', Types::TEXT, [
				'notnull' => false,
			]);
			$table->setPrimaryKey(['id']);
			$table->addIndex(['provider_id'], 'adminly_clients_pid_idx');
		}

		return $schema;
	}
}
