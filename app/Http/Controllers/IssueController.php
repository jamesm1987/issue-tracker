<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Issue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

use App\Http\Resources\IssueResource;

class IssueController extends Controller
{

    public function show(Project $project, Issue $issue)
    {

        return response()->json([
            'data' => new IssueResource($issue),
        ]);
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
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'status'      => 'required|string',
            'priority'    => 'required|string',
            'created_by'  => 'required|exists:App\Models\User,id', 
            'created_by_name'  => 'required|exists:App\Models\User,name',
            'fix_by'  => 'required|exists:App\Models\User,id',
            'test_by'  => 'required|exists:App\Models\User,id',
        ]);

        $project = $project->issues()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'created_by' => auth()->user()->id,
            'created_by_name' => auth()->user()->name,
            'fix_by'  => $validated['fix_by'],
            'test_by'  => $validated['test_by'],
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
