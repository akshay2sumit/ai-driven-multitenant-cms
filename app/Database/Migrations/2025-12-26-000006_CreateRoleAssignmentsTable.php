<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create Role Assignments Table Migration
 * 
 * Creates the role_assignments table for tenant-scoped role assignment management.
 * 
 * PURPOSE: Store role assignments with audit trail and constraints
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This migration creates the role_assignments table structure only. No actual
 * role assignment or management is allowed in Phase 4. This migration exists only to
 * establish the role assignment foundation for future phases.
 * 
 * SECURITY: Roles are assigned explicitly to actors. Assignment requires assigner identity,
 * tenant match, capability subset rule, and audit emission. No automatic role assignment.
 * 
 * @package App\Database\Migrations
 */
class CreateRoleAssignmentsTable extends Migration
{
    /**
     * Run the migration
     * 
     * NOTE: Creates the role_assignments table with tenant-scoped constraints.
     * Enforces role assignment invariants and audit requirements.
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
                'comment' => 'Tenant scope - assignments are tenant-scoped only'
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Role being assigned'
            ],
            'actor_identity' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Actor receiving the role assignment'
            ],
            'actor_type' => [
                'type' => 'ENUM',
                'constraint' => ['human', 'system', 'service', 'ai_operator'],
                'null' => false,
                'comment' => 'Type of actor receiving role assignment'
            ],
            'assigner_identity' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Identity of who assigned the role'
            ],
            'assigner_type' => [
                'type' => 'ENUM',
                'constraint' => ['human', 'system', 'service', 'ai_operator'],
                'null' => false,
                'comment' => 'Type of assigner who assigned the role'
            ],
            'assignment_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for role assignment (audit requirement)'
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Assignment status - inactive assignments are ignored'
            ],
            'assigned_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'When the role was assigned'
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When the assignment expires (optional)'
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When the assignment was revoked'
            ],
            'revoker_identity' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Identity of who revoked the role'
            ],
            'revocation_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for role revocation (audit requirement)'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        
        // Foreign key to roles table
        $this->forge->addForeignKey(
            'role_id',
            'roles',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        // Unique constraint for active role assignments
        $this->forge->addUniqueKey(['tenant_id', 'role_id', 'actor_identity'], 'unique_active_assignment');
        
        // Indexes for performance and audit queries
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('role_id');
        $this->forge->addKey('actor_identity');
        $this->forge->addKey('assigner_identity');
        $this->forge->addKey('is_active');
        $this->forge->addKey('assigned_at');
        $this->forge->addKey('expires_at');
        
        // Composite indexes for common queries
        $this->forge->addKey(['actor_identity', 'is_active']);
        $this->forge->addKey(['tenant_id', 'is_active']);
        $this->forge->addKey(['assigner_identity', 'assigned_at']);
        
        $this->forge->createTable('role_assignments');
    }

    /**
     * Rollback the migration
     * 
     * NOTE: Drops the role_assignments table and all constraints.
     * 
     * @return void
     */
    public function down()
    {
        $this->forge->dropTable('role_assignments', true);
    }
}
