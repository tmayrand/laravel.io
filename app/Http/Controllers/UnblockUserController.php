<?php

namespace App\Http\Controllers;

use App\Jobs\UnblockUser;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnblockUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function __invoke(Request $request, User $user): RedirectResponse
    {
        $this->authorize(UserPolicy::BLOCK, $user);

        $this->dispatchSync(new UnblockUser($request->user(), $user));

        $this->success('User successfully unblocked.');

        return redirect()->route('profile', $user->username());
    }
}
