<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function store(Request $request)
    {

        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        if (!auth()->user()->can('create project')) {
            abort(403, 'Unauthorized');
        }
    
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
        ]);

        $project = Project::create([
            'name'     => $validated['name'],
        ]);

        return response()->json([
            'project'  => $project,
        ], 201);
    }
}
