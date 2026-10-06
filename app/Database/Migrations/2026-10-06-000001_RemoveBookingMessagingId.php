<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveBookingMessagingId extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('bookings') && $this->db->fieldExists('messaging_id', 'bookings')) {
            $this->forge->dropColumn('bookings', 'messaging_id');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('bookings') && !$this->db->fieldExists('messaging_id', 'bookings')) {
            $this->forge->addColumn('bookings', [
                'messaging_id' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            ]);
        }
    }
}
