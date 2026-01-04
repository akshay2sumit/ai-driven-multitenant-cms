<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create Roles Table Migration
 * 
 * Creates the roles table for tenant-scoped role management.
 * 
 * PURPOSE: Store tenant-scoped role definitions with capabilities
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This migration creates the roles table structure only. No seed data
 * or default roles are created in Phase 4. This migration exists only to
 * establish the role foundation for future phases.
 * 
 * SECURITY: Roles are tenant-scoped capability bundles with no authority of their own.
 * Roles never bypass capabilities, context rules, or tenant isolation.
 * 
 * @package App\Database\Migrations
 */
class CreateRolesTable extends Migration
{
    /**
     * Run the migration
     * 
     * NOTE: Creates the roles table with tenant-scoped constraints.
     * Enforces role composition rules and security constraints.
     * 
     * @return void
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tenant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Tenant scope - roles are tenant-scoped only'
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Role name - tenant-scoped, not global'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Role description for documentation and user communication'
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['functional', 'administrative', 'system'],
                'default' => 'functional',
                'null' => false,
                'comment' => 'Role type classification'
            ],
            'capabilities' => [
                'type' => 'JSON',
                'null' => false,
                'comment' => 'Array of capabilities this role contains'
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Role status - inactive roles cannot be assigned'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Soft delete for audit trail preservation'
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        // Foreign key to tenants table
        $this->forge->addForeignKey(
            'tenant_id',
            'tenants',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        // Unique constraint for role name within tenant
        $this->forge->addUniqueKey(['tenant_id', 'name']);
        
        // Indexes for performance
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        
        $this->forge->createTable('roles');
    }

    /**
     * Rollback the migration
     * 
     * NOTE: Drops the roles table and all constraints.
     * 
     * @return void
     */
    public function down()
    {
        $this->forge->dropTable('roles', true);
    }
}
