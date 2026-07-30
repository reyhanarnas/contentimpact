<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ManageCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $this->authorizeAdmin();
        $categories = $this->categoryService->getAllCategories();
        return view('dashboard.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'slug' => 'nullable|string|max:120|unique:categories,slug',
            'description' => 'nullable|string|max:250',
        ]);

        $this->categoryService->createCategory($validated);

        return redirect()->route('dashboard.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:120|unique:categories,slug,' . $id,
            'description' => 'nullable|string|max:250',
        ]);

        $this->categoryService->updateCategory($id, $validated);

        return redirect()->route('dashboard.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->authorizeAdmin();

        $category = $this->categoryService->getCategoryById($id);
        if ($category && $category->articles()->count() > 0) {
            return redirect()->route('dashboard.categories.index')->withErrors([
                'delete' => 'Kategori tidak dapat dihapus karena masih memiliki artikel terkait.'
            ]);
        }

        $this->categoryService->deleteCategory($id);

        return redirect()->route('dashboard.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function authorizeAdmin()
    {
        if (Gate::denies('manage', Category::class)) {
            abort(403, 'Akses ditolak. Hanya administrator yang dapat mengelola kategori.');
        }
    }
}
