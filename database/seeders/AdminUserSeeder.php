<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * حساب مدیر سایت. اگر از قبل ساخته شده باشد، ایمیل و رمز همین حساب
     * به‌روز می‌شود تا ورود پنل با مشخصات فعلی باز شود.
     */
    public function run(): void
    {
        $email = 'mahbobe.jamali1970@gmail.com';

        $admin = User::where('email', 'admin@meydan.test')->first()
            ?? User::where('email', $email)->first()
            ?? User::where('role', 'admin')->orderBy('id')->first();

        if ($admin) {
            $taken = User::where('email', $email)->where('id', '!=', $admin->id)->first();
            if ($taken) {
                $taken->email = 'former-'.$taken->id.'@hachland.local';
                $taken->save();
            }

            $admin->email = $email;
            $admin->password = Hash::make('42364236');
            $admin->role = 'admin';
            if (! $admin->name) {
                $admin->name = 'مدیر سایت';
            }
            $admin->save();

            return;
        }

        User::create([
            'name' => 'مدیر سایت',
            'phone' => '09120000000',
            'email' => $email,
            'password' => Hash::make('42364236'),
            'role' => 'admin',
        ]);
    }
}
