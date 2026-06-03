<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                'unique:'.User::class,
                'unique:employees,email',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the employee record first so the new user appears in the "Empleados" directory
        // and can be assigned tasks in Kanban. This way "crear usuario desde la página" integrates
        // with the rest of the system (visible to admin and other users in the list).
        $employee = Employee::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'position'   => 'Colaborador',
            'department' => 'General',
            'phone'      => null,
            'salary'     => null,
            'hire_date'  => now()->toDateString(),
            'status'     => 'active',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'empleado',
            'employee_id'  => $employee->id,
        ]);

        // Ensure the user can access protected routes immediately (matches seeder behavior)
        if (empty($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
