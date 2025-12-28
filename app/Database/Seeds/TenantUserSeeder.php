<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TenantUserSeeder extends Seeder
{
    public function run()
    {
        // Get the first tenant and user (assuming they exist from previous seeders)
        $tenant = $this->db->table('tenants')->get()->getRow();
        $user = $this->db->table('users')->get()->getRow();

        if ($tenant && $user) {
            $data = [
                'tenant_id'   => $tenant->id,
                'user_id'     => $user->id,
                'role'        => 'admin',
                'permissions' => json_encode(['*']), // Full access
                'created_at'  => Time::now(),
                'updated_at'  => Time::now()
            ];

            $this->db->table('tenant_users')->insert($data);
        }
    }
}
