<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = 'admin123'; // This should be changed in production
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $data = [
            'email'        => 'admin@example.com',
            'password_hash' => $hashedPassword,
            'status'       => 'active',
            'created_at'   => Time::now(),
            'updated_at'   => Time::now()
        ];

        $this->db->table('users')->insert($data);
    }
}
