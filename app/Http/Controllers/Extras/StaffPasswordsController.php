<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\User;

class StaffPasswordsController extends Controller
{
    public function index()
    {
        $staff = User::whereHas('roles', fn($q)=>$q->where('name','!=','Super Admin'))->orderBy('name')->get();
        return view('staff.passwords', compact('staff'));
    }
}
