<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        // get the number of items per page from the request, or use a default (e.g., 15)
        $perPage = $request->input('per_page', 15);

        $tags = Tag::paginate($perPage); // pagination

        return TagResource::collection($tags);
    }

}
