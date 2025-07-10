<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = $user->tasks()->with('tags')->latest();

        // Filter by title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter by tags (match all)
        if ($request->filled('tags')) {
            $tagNames = explode(',', $request->tags);
            foreach ($tagNames as $tagName) {
                $query->whereHas('tags', function ($q) use ($tagName, $user) {
                    $q->where('name', $tagName)
                        ->where('user_id', $user->id);
                });
            }
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title,NULL,id,user_id,' . auth()->id(),
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
            'tags' => 'array',
            'tags.*' => 'string|max:50'
        ]);

        $task = auth()->user()->tasks()->create($data);

        if (!empty($data['tags'])) {
            $tagIds = collect($data['tags'])->map(function ($tagName) {
                return \App\Models\Tag::firstOrCreate(
                    ['name' => $tagName, 'user_id' => auth()->id()]
                )->id;
            });

            $task->tags()->sync($tagIds);
        }

        return response()->json($task->load('tags'), 201);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255|unique:tasks,title,' . $task->id . ',id,user_id,' . auth()->id(),
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ]);

        if (array_key_exists('tags', $data)) {
            $tagIds = collect($data['tags'])->map(function ($tagName) {
                return \App\Models\Tag::firstOrCreate(
                    ['name' => $tagName, 'user_id' => auth()->id()]
                )->id;
            });

            $task->tags()->sync($tagIds);
        }

        $task->update($data);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('view', $task);

        $task->delete();

        return response()->json(null, 204);
    }
}
