<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{

    public function index() 
    {
        $projects = Project::all();

        return response()->json([
            'data' => ProjectResource::collection($projects),
        ]);
    }

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

    public function update(Request $request, Project $project)
    {
        $project->update($request->only(['name']));
    
        return response()->json(['project' => $project], 200);
    }

    public function destroy(Project $project)
    {
        $project->delete();
    
        return response()->json(null, 204);
    }
}
