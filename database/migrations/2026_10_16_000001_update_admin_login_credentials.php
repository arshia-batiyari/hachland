<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $email = 'mahbobe.jamali1970@gmail.com';

        $admin = User::where('email', 'admin@meydan.test')->first()
            ?? User::where('email', $email)->first()
            ?? User::where('role', 'admin')->orderBy('id')->first();

        if (! $admin) {
            User::create([
                'name' => 'مدیر سایت',
                'phone' => '09111775006',
                'email' => $email,
                'password' => Hash::make('42364236'),
                'role' => 'admin',
            ]);

            return;
        }

        $taken = User::where('email', $email)->where('id', '!=', $admin->id)->first();
        if ($taken) {
            $taken->email = 'former-'.$taken->id.'@hachland.local';
            $taken->save();
        }

        $admin->email = $email;
        $admin->password = Hash::make('42364236');
        $admin->role = 'admin';
        $admin->save();
    }

    public function down(): void
    {
        $admin = User::where('email', 'mahbobe.jamali1970@gmail.com')->where('role', 'admin')->first();

        if (! $admin) {
            return;
        }

        $legacy = 'admin@meydan.test';
        $taken = User::where('email', $legacy)->where('id', '!=', $admin->id)->exists();

        if (! $taken) {
            $admin->email = $legacy;
        }

        $admin->password = Hash::make('password');
        $admin->save();
    }
};
