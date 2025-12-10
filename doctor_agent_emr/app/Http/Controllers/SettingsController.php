<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Authenticated;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __invoke(#[Authenticated] Authenticatable|null $user): View
    {
        return view('settings', ['user' => $user]);
    }
}
