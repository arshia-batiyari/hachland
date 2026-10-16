<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $phone = '09111775006';

        $admin = User::where('email', 'mahbobe.jamali1970@gmail.com')->first()
            ?? User::where('email', 'admin@meydan.test')->first()
            ?? User::where('role', 'admin')->orderBy('id')->first();

        if (! $admin) {
            return;
        }

        $holder = User::where('phone', $phone)->where('id', '!=', $admin->id)->first();
        if ($holder) {
            $holder->phone = null;
            $holder->save();
        }

        $admin->phone = $phone;
        $admin->save();
    }

    public function down(): void
    {
        User::where('email', 'mahbobe.jamali1970@gmail.com')
            ->where('role', 'admin')
            ->update(['phone' => '09120000000']);
    }
};
