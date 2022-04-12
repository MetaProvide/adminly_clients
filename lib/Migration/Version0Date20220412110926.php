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
class Version0Date20220412110926 extends SimpleMigrationStep {
	const _TABLENAME = "adminly_clients";

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable(self::_TABLENAME)) {
			$table = $schema->createTable(self::_TABLENAME);
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
				'notnull' => false,
				'length' => 250,
			]);
			$table->addColumn('name', Types::STRING, [
				'notnull' => false,
				'length' => 250,
			]);
			$table->addColumn('description', Types::TEXT, [
				'notnull' => false,
			]);
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['email'], 'email_index');
		}
		return $schema;
	}
}
