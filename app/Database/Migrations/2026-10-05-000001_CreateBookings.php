<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookings extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('bookings')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'profile_id' => ['type' => 'INT', 'constraint' => 11],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 40],
            'message' => ['type' => 'TEXT'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'approved'], 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['profile_id', 'status'], false, false, 'bookings_profile_status');
        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE', 'bookings_profile_fk');
        $this->forge->createTable('bookings', true);
    }

    public function down()
    {
        $this->forge->dropTable('bookings', true);
    }
}
