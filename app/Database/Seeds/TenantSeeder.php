<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'   => 'Default Tenant',
            'slug'   => 'default',
            'status' => 'active',
        ];

        $this->db->table('tenants')->insert($data);
    }
}
