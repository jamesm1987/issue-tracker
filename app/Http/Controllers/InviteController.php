<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Issue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Models\Invite;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\InviteUserMail;
use Illuminate\Support\Facades\Mail;


class InviteController extends Controller
{


    public function store(Request $request)
    {
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        if (!auth()->user()->can('invite user')) {
            abort(403, 'Unauthorized');
        }
    
        $validated = $request->validate([
            'email'   => 'required|email|unique:invitations,email',
            'role_id'  => 'required|exists:roles,id',
            'invited_by'  => 'required|exists:App\Models\User,id',
        ]);

        $payload = [
            'token' => Str::random(10),
            'expires_at' => Carbon::now()->addDays(7)
        ];


        $payload = array_merge($validated, $payload);

        $invite = Invite::create($payload);

        $mail = Mail::to($validated['email'])->send(new InviteUserMail($invite));

        return response()->json(['message' => 'Invitation created successfully'], 201);
    }

    public function show(Request $request)
    {
        $invite = Invite::where('token', $request->token)->first();

        if ( !$invite ) {
            return response()->json(['message' => 'Invalid invitation token.'], 404);
        }

        if ( $invite->accepted_at ) {
            return response()->json(['message' => 'Invitation already accepted.'], 410);
        }

        if ($invite->expires_at && $invite->expires_at->isPast()) {
            return response()->json(['message' => 'Invitation has expired.'], 410);
        }

        return response()->json([
            'email' => $invite->email,
            'invited_by' => $invite->invited_by,
            'expires_at' => $invite->expires_at,
        ]);
    }

    public function destroy(Invite $invite)
    {
        $invite->delete();
    
        return response()->json(null, 204);
    }
}
