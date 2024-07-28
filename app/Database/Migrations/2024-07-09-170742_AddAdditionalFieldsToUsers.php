<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddAdditionalFieldsToUsers extends Migration
{
    /**
     * @var string[]
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var \Config\Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $fields = [
            'nombres' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'paterno' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'materno' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'dni' => ['type' => 'VARCHAR', 'constraint' => '15', 'null' => true],
            'cargo' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'telefono' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'oficina_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'persona_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ];

        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'nombres',
            'paterno',
            'materno',
            'dni',
            'cargo',
            'telefono',
            'office_id',
            'person_id',
        ];

        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
