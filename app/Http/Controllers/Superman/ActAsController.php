<?php

namespace App\Http\Controllers\Superman;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ActAsController extends Controller
{
    /**
     * Display the "act as" form.
     *
     * @return \Illuminate\View\View
     */
    public function showActAsForm()
    {
        $users = User::all();
        return view('superman.actas.form', compact('users'));
    }

    /**
     * Handle the "act as" functionality.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actas($id)
    {

        $user = User::findOrFail($id);

        // Store the current user's ID in the session
        Session::put('original_user_id', Auth::id());

        // Log in as the selected user
        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    /**
     * Stop "acting as" and return to the original user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stopActingAs()
    {
        // Get the original user's ID from the session
        $originalUserId = Session::get('original_user_id');

        // Log in as the original user
        Auth::loginUsingId($originalUserId);

        // Clear the original user's ID from the session
        Session::forget('original_user_id');

        return redirect()->intended('/dashboard');
    }
}
