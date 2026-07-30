<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByRole(string $role): Collection;
    public function getSuspendedCount(): int;
    public function getActiveCount(): int;
    public function getTotalCount(): int;
}
