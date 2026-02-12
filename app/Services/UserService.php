<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return \App\User
     */
    public function registerUser(array $data): \App\User
    {
        // Hash the password before creating the user
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return \App\User|null
     */
    public function getUserById(int $id): ?\App\User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Get a user by email.
     *
     * @param string $email
     * @return \App\User|null
     */
    public function getUserByEmail(string $email): ?\App\User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return \App\User|null
     */
    public function updateUser(int $id, array $data): ?\App\User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->update($id, $data);
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    // Add other user-related business logic methods here
}
