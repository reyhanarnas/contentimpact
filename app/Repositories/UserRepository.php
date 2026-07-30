<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->find($id);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $user = $this->find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    public function getByRole(string $role): Collection
    {
        return User::where('role', $role)->get();
    }

    public function getSuspendedCount(): int
    {
        return User::where('status', 'suspended')->count();
    }

    public function getActiveCount(): int
    {
        return User::where('status', 'active')->count();
    }

    public function getTotalCount(): int
    {
        return User::count();
    }
}
