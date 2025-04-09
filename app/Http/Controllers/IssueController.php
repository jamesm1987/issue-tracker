<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Issue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class IssueController extends Controller
{

    public function show(Project $project, Issue $issue)
    {
        
    }

    public function store(Request $request, Project $project)
    {

        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        if (!auth()->user()->can('create issue')) {
            abort(403, 'Unauthorized');
        }
    
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'status'    => 'required|string',
            'priority'  => 'required|string',
        ]);

        $project = $project->issues()->create([
            'issue'     => $validated['name'],
        ]);

        return response()->json([
            'issue'  => $issue,
        ], 201);
    }

    public function update(Request $request, Project $project, Issue $issue)
    {
        $issue->update($request->only(['name']));
    
        return response()->json(['issue' => $issue], 200);
    }

    public function destroy(Project $project, Issue $issue)
    {
        $issue->delete();
    
        return response()->json(null, 204);
    }
}
