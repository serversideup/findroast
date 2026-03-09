<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $users = User::orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        return Inertia::render('Platform/Users/Index', [
            'users' => $users
        ]);
    }
}
