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
            'first_name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'mother_name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'dni' => ['type' => 'VARCHAR', 'constraint' => '15', 'null' => true],
            'position' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'telephone' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'office_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ];

        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'first_name',
            'last_name',
            'mother_name',
            'dni',
            'position',
            'telephone',
            'office_id',
        ];

        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
