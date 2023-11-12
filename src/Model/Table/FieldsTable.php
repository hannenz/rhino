<?php
declare(strict_types=1);

namespace Rhino\Model\Table;

use Cake\ORM\Table;

use Migrations\Migrations;
use Migrations\AbstractMigration;

class FieldsTable extends Table {

	public $types = [
		"string",
		"text",
		"integer",
		"boolean",
		"float",
		"date",
		"datetime",
		"time",
		"timestamp",
		"binary",
		"bit",
		"biginteger",
		"blob",
		"char",
		"decimal",
		"double",
		"enum",
		"json",
		"set",
		"smallinteger",
		"uuid"
	];

	public $translateType = [
		'int(11)' => "integer",
		'varchar(255)' => "string",
		'varchar(100)' => "string",
		'tinyint(1) unsigned' => "boolean",
		'tinyint(1)' => "boolean",
	];

	public $rows = [
		"Field",
		"Type",
		"Null",
		"Default",
		"Extra"
	];

	private $fieldValues = [
		"limit",
		"comment",
		"default",
		"null",
		"after",
		"signed",
		"precision",
		"scale",
		"values",
		"update",
		"timezone"
	];

    public function initialize(array $config): void {
		parent::initialize($config);

		$this->setTable('rhino_fields');
		$this->setDisplayField('id');
		$this->setPrimaryKey('id');

		// Create Abstact to start Database Operations
		$migrations = new Migrations;
		$migrations->setInput($migrations->getInput('Seed', [], []));
		$manager = $migrations->getManager($migrations->getConfig());
		$env = $manager->getEnvironment('default');

		// Use Abstract to alter database
		// https://book.cakephp.org/phinx/0/en/migrations.html
		$this->abstract = new AbstractMigration('default', 1);
		$this->abstract->setAdapter($env->getAdapter());
    }

	public function create(string $tableName, array $data): void {
		$table = $this->abstract->table($tableName);
		
		$name = $data['name'];
		$type = $data['type'];

		$data = $this->prepareFieldOptions($data);
		$table->addColumn($name, $type, $data);
		$table->save();
	}

	public function update($tableName, $fieldName, $data) {
		$type = $data["type"];
		$table = $this->abstract->table($tableName);

		$data = $this->prepareFieldOptions($data);
		$table->changeColumn($fieldName, $type, $data);
		$table->update();
	}

	public function rename(string $tableName, string $currentName, string $name): void {
		$table = $this->abstract->table($tableName);
		$table->renameColumn($currentName, $name)->save();
	}

	public function drop(string $tableName, string $field): void {
		$table = $this->abstract->table($tableName);
		$table->removeColumn($field)->save();
	}
	
	public function getColumns(string $tableName) {
		// Protection against posible SQL Injection
		if (!$this->abstract->hasTable($tableName)) {
			return;
		}

		$query = "describe " . $tableName;
		$_columns = $this->abstract->query($query)->fetchAll();
		$columns = [];

		foreach ($_columns as $fields) {
			$column = [];
			foreach ($this->rows as $row) {
				$column[$row] = $fields[$row];
			}

			$entry = $this->checkForEntry($column['Field'], $tableName);
			if (!empty($entry)) {
				$column['Type'] = $entry['type'];
			}

			$columns[] = $column;
		};

		return $columns;
	}

	public function getColumn(string $tableName, string $fieldName) {
		$columns = $this->getColumns($tableName);

		foreach ($columns as $column) {
			if ($column["Field"] == $fieldName) {
				return $column;
			}
		}

		return false;
	}

	public function getByName(string $fieldName, string $tableName) {
		$entry = $this->checkForEntry($fieldName, $tableName);

		if (empty($entry)) {
			$entry = $this->newEmptyEntity();
			$entry->name = $fieldName;
			$entry->tableName = $tableName;

		}
		
		$column = $this->getColumn($tableName, $fieldName);
		if (isset($column)) {
			$entry->alias = $entry->alias ?? $column['Field'];
			$entry->type = $this->getHumanType($column['Type']);
			$entry->standard = $column['Default'];
			$entry->extra = $column['Extra'];
		}

		return $entry;
	}

	public function checkForEntry($fieldName, $tableName) {
		$query = $this->find()->where(['name' => $fieldName, 'tableName' => $tableName]);
		if (!$query->all()->isEmpty()) {
			return $query->first();
		}
	
		return null;
	}

	public function getHumanType(string $type) : string {
		if (isset($this->translateType[$type])) {
			return $this->translateType[$type];
		}

		return $type;
	}

	private function prepareFieldOptions($data) {
		$options = [];

		foreach ($this->fieldValues as $value) {
			// $foo !== "" insted of !empty($foo) to allow 0 as default
			if (isset($data[$value]) && $data[$value] !== "") {
				$options[$value] = $data[$value];
			}
		}

		// if ($data['type'] == 'checkbox') {
		// 	# code...
		// 	if (isset($data['default']) && $data['default'] === 'false') {
		// 		$options['default'] = 0;
		// 	}

		// 	if (isset($data['default']) && $data['default'] === 'true') {
		// 		$options['default'] = 1;
		// 	}
		// }

		if (isset($data['null'])) {
			$options['null'] = (bool)$data['null'];
		}

		if (in_array($data['type'], ['datetime', 'date', 'time'])) {
			// if ($options['default'] === '0') {
			// 	$options['default'] = null;
			// }

			if (isset($data['current_time']) && $data["current_time"]) {
				$options["default"] = "CURRENT_TIMESTAMP";
			}
			
			if (isset($data['update_time']) && $data["update_time"]) {
				$options["update"] = "CURRENT_TIMESTAMP";
			}
		}
			
		return $options;
	}
}
