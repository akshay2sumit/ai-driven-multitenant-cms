<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['email', 'password_hash', 'status', 'last_login'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required',
        'status' => 'required|in_list[active,inactive,suspended]',
    ];

    /**
     * Find a user by email
     */
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)
                   ->where('deleted_at', null)
                   ->first();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $email, string $password)
    {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        if ($user['status'] !== 'active') {
            return false;
        }

        return $user;
    }
}
