<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'addresses' => $request->user()->addresses()->orderBy('is_default', 'desc')->get(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Store a new saved address.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'         => ['required', 'in:home,work,other'],
            'address_line' => ['required', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'type'         => $validated['type'],
            'address_line' => $validated['address_line'],
            'is_default'   => $isFirst,
        ]);

        return Redirect::route('profile.edit')->with('success', 'Address added successfully.');
    }

    /**
     * Delete a saved address.
     */
    public function deleteAddress(UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextAddress = Auth::user()->addresses()->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        }

        return Redirect::route('profile.edit')->with('success', 'Address removed successfully.');
    }

    /**
     * Set a saved address as the default.
     */
    public function setDefaultAddress(UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return Redirect::route('profile.edit')->with('success', 'Default address updated.');
    }
}
