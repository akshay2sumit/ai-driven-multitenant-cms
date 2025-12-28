<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePublishableEntitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tenant_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'References tenants.id',
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'Type of the entity (e.g., page, post, article)',
            ],
            'entity_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID of the entity in its source table',
            ],
            'version' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 1,
                'comment'    => 'Version number of this publishable entity',
            ],
            'state' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'review', 'published', 'archived', 'retracted'],
                'default'    => 'draft',
                'null'       => false,
            ],
            'published_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'comment' => 'When this version was published (null if never published)',
            ],
            'published_by' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'comment'    => 'User ID who published this version',
            ],
            'data' => [
                'type'    => 'LONGTEXT',
                'null'    => false,
                'comment' => 'JSON-encoded entity data',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'on_update' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['tenant_id', 'entity_type', 'entity_id', 'version'], false, 'idx_tenant_entity_version');
        $this->forge->addKey(['tenant_id', 'entity_type', 'state'], false, 'idx_tenant_entity_state');
        $this->forge->addKey('published_at');
        
        // Add foreign key for tenant isolation
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        
        // Add foreign key for published_by (optional, can be removed if users table doesn't exist yet)
        // $this->forge->addForeignKey('published_by', 'users', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('publishable_entities');

        // Add comment to the table
        $this->db->query('ALTER TABLE `publishable_entities` COMMENT = "Stores versioned, publishable entities with their state and data";');
    }

    public function down()
    {
        $this->forge->dropTable('publishable_entities');
    }
}
