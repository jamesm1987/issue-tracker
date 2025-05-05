<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Http\Resources\UserProjectResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class UserProjectController extends Controller
{

    public function index() 
    {
        $projects = $user->projects()->get();

        return response()->json([
            'data' => UserProjectResource::collection($projects),
        ]);
    }

    public function store(Request $request, User $user)
    {

        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        if (!auth()->user()->can('edit all users')) {
            abort(403, 'Unauthorized');
        }


        $validated = $request->validate([
            'project_ids' => ['array'],
            'project_ids.*' => ['exists:projects,id'],
        ]);

        $user->projects()->sync($validated['project_ids']);

        return response()->json([
            'message' => 'Projects updated successfully.',
        ], 201);
    }

    public function update(Request $request, Project $project)
    {
    
        return response()->json(['projects' => $projects], 200);
    }

    public function destroy(Project $project)
    {
        return response()->json(null, 204);
    }
}
