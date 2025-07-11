<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Fortify\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        if ($user->hasRole('bcc_admin')) {
            $facilityUsers = User::where('facility_id', $facilityId)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'nurse');
                })
                ->get();
        } else {
            $facilityUsers = User::where('facility_id', $facilityId)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'doctor');
                })
                ->get();
        }

        return view('pages.facility_admin.users.index')
            ->with('users', $facilityUsers);
    }

    public function edit(string $id = null)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        if ($id === null) {
            return view('pages.facility_admin.users.edit')
                ->with('user', new User());
        }

        $foundUser = User::with('roles')
            ->where('facility_id', $facilityId)
            ->where('id', $id)
            ->first();

        if ($foundUser) {
            return view('pages.facility_admin.users.edit')
                ->with('user', $foundUser);
        } else {
            flash()->error('User not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $userId = $request->input('id');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => $userId
                ? ['nullable', 'string', new Password]
                : ['required', 'string', new Password]
        ]);

        try {
            if ($userId) {
                $foundUser = User::where('facility_id', $facilityId)
                    ->where('id', $userId)
                    ->first();

                if (!$foundUser) {
                    flash()->error('User not found or unauthorized.', [
                        'position' => 'bottom-right',
                        'closeButton' => true
                    ]);
                    return redirect()->route('facility_admin.users.index');
                }
            } else {
                $foundUser = new User();
                $foundUser->facility_id = $facilityId;
            }

            $foundUser->name = $validated['name'];
            $foundUser->email = $validated['email'];

            if (!empty($validated['password'])) {
                $foundUser->password = bcrypt($validated['password']);
            }
            $foundUser->save();

            if ($user->hasRole('bcc_admin')) {
                $foundUser->syncRoles(['nurse']);
            } else {
                $foundUser->syncRoles(['doctor']);
            }

            flash()->success('User saved successfully!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('facility_admin.users.index');

        } catch (\Exception $e) {
            flash()->error('Error saving user.', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('facility_admin.users.edit', ['id' => $userId]);
        }
    }
}
