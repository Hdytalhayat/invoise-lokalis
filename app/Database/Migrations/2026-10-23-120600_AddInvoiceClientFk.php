<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInvoiceClientFk extends Migration
{
    private string $constraint = 'fk_invoices_client_id';

    public function up()
    {
        // Check if the foreign key already exists
        $row = $this->db->query(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'invoices' AND CONSTRAINT_NAME = ?",
            [$this->constraint]
        )->getRow();

        if ($row) {
            return; // already exists
        }

        $this->db->disableForeignKeyChecks();

        $sql = "ALTER TABLE invoices
                ADD CONSTRAINT {$this->constraint}
                FOREIGN KEY (client_id) REFERENCES clients(id)
                ON DELETE CASCADE ON UPDATE CASCADE";

        $this->db->query($sql);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $row = $this->db->query(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'invoices' AND CONSTRAINT_NAME = ?",
            [$this->constraint]
        )->getRow();

        if (! $row) {
            return;
        }

        $this->db->disableForeignKeyChecks();
        $this->db->query("ALTER TABLE invoices DROP FOREIGN KEY {$this->constraint}");
        $this->db->enableForeignKeyChecks();
    }
}