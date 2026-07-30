<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ManageUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->authorizeAdmin();
        $users = $this->userService->getAllUsers();
        return view('dashboard.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,editor,journalist',
        ]);

        $this->userService->createUser($validated);

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,editor,journalist',
        ]);

        $this->userService->updateUser($id, $validated);

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->authorizeAdmin();

        if (Auth::id() === $id) {
            return redirect()->route('dashboard.users.index')->withErrors([
                'delete' => 'Anda tidak bisa menghapus akun Anda sendiri.'
            ]);
        }

        $this->userService->deleteUser($id);

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil dihapus.');
    }



    private function authorizeAdmin()
    {
        if (Gate::denies('manage', User::class)) {
            abort(403, 'Akses ditolak. Hanya administrator yang dapat mengelola pengguna.');
        }
    }
}
