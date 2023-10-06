<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

use function PHPSTORM_META\type;

class Disposisi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => '5',
                'auto_increment' => true
            ],
            'sid' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false
            ],
            'uid' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false
            ],
            'pesan'=>[
                'type' => 'TEXT',
            ],
            'created_at' =>[
                'type' => 'datetime',
            ],
            'updated_at' =>[
                'type' => 'datetime',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('uid', 'users', 'id','cascade','cascade');
        $this->forge->addForeignKey('sid', 'letters', 'id','cascade','cascade');
        $this->forge->createTable('disposisi');
    }

    public function down()
    {
        //
    }
}
