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
class Version0Date20220420183111 extends SimpleMigrationStep {
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		$table = $schema->getTable("adminly_clients");
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

		return $schema;
	}
}
