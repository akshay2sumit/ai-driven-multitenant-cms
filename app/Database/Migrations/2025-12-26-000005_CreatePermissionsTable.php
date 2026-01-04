<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create Permissions Table Migration
 * 
 * Creates the permissions table for permission decision storage.
 * 
 * PURPOSE: Store permission decisions and audit evidence
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This migration creates the permissions table structure only. No actual
 * permission evaluation or storage is allowed in Phase 4. This migration exists
 * only to establish the permission foundation for future phases.
 * 
 * SECURITY: Permission is a decision, not a property. Actors have capabilities.
 * System decides permission at request time. Decision is always contextual and state-aware.
 * 
 * @package App\Database\Migrations
 */
class CreatePermissionsTable extends Migration
{
    /**
     * Run the migration
     * 
     * NOTE: Creates the permissions table with audit trail support.
     * Enforces permission evaluation rules and security constraints.
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
            'permission_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Unique permission identifier for tracking'
            ],
            'actor_identity' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Actor requesting permission (user ID or system identifier)'
            ],
            'actor_type' => [
                'type' => 'ENUM',
                'constraint' => ['human', 'system', 'service', 'ai_operator'],
                'null' => false,
                'comment' => 'Type of actor requesting permission'
            ],
            'capability' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Capability being requested (exact match, no wildcards)'
            ],
            'execution_context' => [
                'type' => 'ENUM',
                'constraint' => ['authoring', 'runtime_public', 'system_background', 'governance'],
                'null' => false,
                'comment' => 'Execution context for permission evaluation'
            ],
            'resource_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Resource being accessed (if applicable)'
            ],
            'resource_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Type of resource being accessed'
            ],
            'resource_state' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published', 'archived', 'locked'],
                'null' => true,
                'comment' => 'Current state of resource (if applicable)'
            ],
            'tenant_scope' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Tenant scope for permission evaluation'
            ],
            'decision' => [
                'type' => 'ENUM',
                'constraint' => ['allow', 'deny'],
                'null' => false,
                'default' => 'deny',
                'comment' => 'Permission decision (fail-closed default)'
            ],
            'decision_evidence' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Evidence supporting the permission decision'
            ],
            'decision_timestamp' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'When the permission decision was made'
            ],
            'is_deterministic' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Whether permission decision is deterministic'
            ],
            'is_auditable' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Whether permission decision is auditable'
            ],
            'is_explainable' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Whether permission decision is explainable'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        // Unique constraint for permission_id
        $this->forge->addUniqueKey('permission_id');
        
        // Indexes for performance and audit queries
        $this->forge->addKey('actor_identity');
        $this->forge->addKey('capability');
        $this->forge->addKey('execution_context');
        $this->forge->addKey('tenant_scope');
        $this->forge->addKey('decision');
        $this->forge->addKey('decision_timestamp');
        
        // Composite indexes for common queries
        $this->forge->addKey(['actor_identity', 'capability']);
        $this->forge->addKey(['tenant_scope', 'decision']);
        $this->forge->addKey(['execution_context', 'decision']);
        
        $this->forge->createTable('permissions');
    }

    /**
     * Rollback the migration
     * 
     * NOTE: Drops the permissions table and all constraints.
     * 
     * @return void
     */
    public function down()
    {
        $this->forge->dropTable('permissions', true);
    }
}
