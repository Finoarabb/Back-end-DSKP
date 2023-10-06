<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

use function PHPSTORM_META\type;

class Letter extends Migration
{   
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'auto_increment' => true
            ],
            'no_surat' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
                'unique'=>true
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'asal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tujuan' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'tanggal' => [
                'type' => 'date'
            ],
            'approval' =>[
                'type' => 'boolean',
            ],            
            'created_at' =>[
                'type' => 'datetime',
            ],
            'updated_at'=>[
                'type' => 'datetime',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('Letters');
    }


    public function down()
    {
        //
    }
}
