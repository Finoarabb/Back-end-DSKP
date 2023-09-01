<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'auto_increment' => true
            ],
            'nama'=>[
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 200
            ],
            'role'=>[
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' =>'staff'
            ]
        ]);
        $this->forge->addKey('id', true);
        
        $this->forge->createTable('users');
    }

    public function down()
    {
        //
    }
}
