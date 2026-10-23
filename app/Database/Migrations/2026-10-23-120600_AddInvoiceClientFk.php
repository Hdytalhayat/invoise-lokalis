<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInvoiceClientFk extends Migration
{
    public function up()
    {
        // Verify the foreign key doesn't already exist before adding
        $columns = $this->forge->getFieldProperties('invoices');
        if (!empty($this->db->getIndexData('invoices'))) {
            return;
        }

        $this->db->disableForeignKeyChecks();

        $sql = "ALTER TABLE invoices 
                ADD CONSTRAINT fk_invoices_client_id 
                FOREIGN KEY (client_id) REFERENCES clients(id) 
                ON DELETE CASCADE ON UPDATE CASCADE";

        $this->db->query($sql);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->db->query('ALTER TABLE invoices DROP FOREIGN KEY fk_invoices_client_id');
        $this->db->enableForeignKeyChecks();
    }
}
