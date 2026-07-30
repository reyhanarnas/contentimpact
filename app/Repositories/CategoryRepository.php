<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::all();
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = $this->find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }

    public function allWithCount(): Collection
    {
        return Category::withCount(['articles' => function ($query) {
            $query->where('status', 'published');
        }])->get();
    }
}
