<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TeamController extends Controller
{
    private function ownerCompany()
    {
        return auth()->user()->company;
    }

    public function index()
    {
        $company = $this->ownerCompany();
        $operators = $company->users()
            ->where('role', 'user')
            ->latest()
            ->get();

        return view('marketplace.team.index', compact('company', 'operators'));
    }

    public function create()
    {
        $company = $this->ownerCompany();
        return view('marketplace.team.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = $this->ownerCompany();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
            'lang'     => ['required', 'in:uz,ru,en'],
        ]);

        User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'user',
            'company_id' => $company->id,
            'lang'       => $data['lang'],
        ]);

        return redirect()->route('marketplace.team.index')
            ->with('success', "Operator qo'shildi.");
    }

    public function destroy(User $user)
    {
        $owner   = auth()->user();
        $company = $this->ownerCompany();

        // Guard: target must be an operator in the same company
        if ($user->role !== 'user' || $user->company_id !== $company->id) {
            abort(403);
        }

        DB::transaction(function () use ($user, $owner) {
            // Transfer all user_id references to the owner
            DB::table('addresses')->where('user_id', $user->id)->update(['user_id' => $owner->id]);
            DB::table('contacts')->where('user_id', $user->id)->update(['user_id' => $owner->id]);
            DB::table('rates')->where('user_id', $user->id)->update(['user_id' => $owner->id]);

            $user->delete();
        });

        return redirect()->route('marketplace.team.index')
            ->with('success', 'Operator o\'chirildi, barcha ma\'lumotlar sizga o\'tkazildi.');
    }
}
