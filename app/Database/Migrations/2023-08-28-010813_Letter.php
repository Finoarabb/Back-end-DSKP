<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

use function PHPSTORM_META\type;

class Letter extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'no_surat' => [
                'type' => 'INT',
                'null' => false
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
            'Approval' =>[
                'type' => 'boolean',
            ],
            'disposisi' =>[
                'type' => 'boolean',
            ]
        ]);
        $this->forge->addKey('no_surat', true);
        $this->forge->createTable('Letters');
    }


    public function down()
    {
        //
    }
}
