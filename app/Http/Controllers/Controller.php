<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * ایمیل را قبل از اعتبارسنجی به حروف کوچک یکدست می‌کند.
     * نشانی ایمیل به بزرگی و کوچکی حروف حساس نیست، پس کاربر می‌تواند
     * هر ترکیبی وارد کند و همان نشانی یکتا ذخیره و پیدا می‌شود.
     */
    protected function normalizeEmail(Request $request, string $field = 'email'): void
    {
        $email = $request->input($field);

        if (! is_string($email)) {
            return;
        }

        $email = trim($email);

        if ($email === '') {
            return;
        }

        $request->merge([$field => Str::lower($email)]);
    }
}
