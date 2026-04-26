<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('company')
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn($q) =>
                $q->where('role', $request->role)
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $companies = Company::where('status', 'approved')->orderBy('name')->get();
        return view('admin.users.form', compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'role'       => 'required|in:admin,producer,owner,user,client',
            'lang'       => 'required|in:uz,ru,en',
            'company_id' => 'nullable|exists:companies,id',
            'password'   => ['required', Password::min(8)],
        ]);

        $companyId = $this->resolveCompanyId($request->role, $request->company_id);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'lang'       => $request->lang,
            'company_id' => $companyId,
            'password'   => $request->password,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "«{$request->name}» qo'shildi");
    }

    public function edit(User $user): View
    {
        $companies = Company::where('status', 'approved')->orderBy('name')->get();
        return view('admin.users.form', compact('user', 'companies'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => "required|email|unique:users,email,{$user->id}",
            'role'       => 'required|in:admin,producer,owner,user,client',
            'lang'       => 'required|in:uz,ru,en',
            'company_id' => 'nullable|exists:companies,id',
            'password'   => ['nullable', Password::min(8)],
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'lang'       => $request->lang,
            'company_id' => $this->resolveCompanyId($request->role, $request->company_id),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "«{$request->name}» yangilandi");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', "O'z hisobingizni o'chira olmaysiz");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "«{$name}» o'chirildi");
    }

    /**
     * admin and producer must never be linked to a company.
     * owner and user may be linked to one company.
     */
    private function resolveCompanyId(string $role, ?string $companyId): ?string
    {
        if (in_array($role, ['admin', 'producer'], true)) {
            return null;
        }

        return $companyId ?: null;
    }
}
