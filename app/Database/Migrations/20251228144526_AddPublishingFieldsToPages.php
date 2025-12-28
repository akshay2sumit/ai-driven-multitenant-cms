<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublishingFieldsToPages extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published', 'archived', 'scheduled'],
                'default'    => 'draft',
                'after'      => 'content'
            ],
            'published_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'status'
            ],
            'expire_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'published_at'
            ]
        ];

        $this->forge->addColumn('pages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pages', ['status', 'published_at', 'expire_at']);
    }
}
