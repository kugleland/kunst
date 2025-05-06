<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactResource;
use App\Models\Fact;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class BookmarkController extends Controller
{
    public function index()
    {

        $user = auth('sanctum')->user();

        #$bookmarks = FactResource::collection(Fact::query()->whereBookmarkedBy($user)->get());

        $facts = FactResource::collection(
            QueryBuilder::for(Fact::query()->whereBookmarkedBy($user))
                ->allowedFilters([
                    AllowedFilter::exact('category_id'), // Filter facts by exact category IDs
                ])
                ->allowedIncludes(['categories']) // Allow including related categories
                ->get()
        );



        return response()->json([
            'data' => FactResource::collection($facts),
            'status' => true,
            'message' => 'Facts found successfully'
        ]);
    }
}
