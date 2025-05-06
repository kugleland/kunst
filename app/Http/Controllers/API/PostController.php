<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactResource;
use App\Models\Fact;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Cache;

class FactController extends Controller
{

    public function index(Request $request)
    {

        $facts = FactResource::collection(
            QueryBuilder::for(Fact::class)
                ->allowedFilters([
                    AllowedFilter::exact('category_id'), // Filter facts by exact category IDs
                ])
                ->allowedIncludes(['categories']) // Allow including related categories
                ->get()
        );

        return response()->json([
            'data' => $facts,
            'status' => true,
            'message' => 'Facts found successfully'
        ]);
    }


    public function show(Fact $fact)
    {
        return response()->json([
            'status' => true,
            'message' => 'Fact found successfully',
            'data' => new FactResource($fact),
        ], 200);
    }


    public function upvote(Request $request, Fact $fact)
    {
        $request->user()->upvote($fact);
        return response()->json(['message' => 'Fact upvoted successfully!', 'fact' => $fact]);
    }

    public function downvote(Request $request, Fact $fact)
    {
        $request->user()->downvote($fact);
        return response()->json(['message' => 'Fact downvoted successfully!', 'fact' => $fact]);
    }

    public function unvote(Request $request, Fact $fact)
    {
        $request->user()->cancelVote($fact);
        return response()->json(['message' => 'Vote removed successfully!', 'fact' => $fact]);
    }

    public function toggleVote(Request $request, Fact $fact)
    {
        $request->user()->vote($fact);
        return response()->json(['message' => 'Vote toggled successfully!', 'fact' => $fact]);
    }


    public function toggleBookmark(Request $request, Fact $fact)
    {
        $status = $request->user()->toggleBookmark($fact);
        return response()->json(['bookmarked' => $status, 'fact' => $fact]);
    }

    public function dailyFact()
    {
        $dailyFact = Cache::remember('daily_fact', now()->endOfDay(), function () {
            return Fact::inRandomOrder()->first();
        });

        return response()->json([
            'status' => true,
            'message' => 'Daily fact fetched successfully',
            'data' => new FactResource($dailyFact),
        ]);
    }

    public function trendingFacts()
    {
        $trendingFacts = Cache::remember('trending_facts', 3600, function () {
            return Fact::trending()->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'Trending facts fetched successfully',
            'data' => FactResource::collection($trendingFacts),
        ]);
    }

    public function newFacts()
    {
        $newFacts = Cache::remember('new_facts', 3600, function () {
            return Fact::newFacts()->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'New facts fetched successfully',
            'data' => FactResource::collection($newFacts),
        ]);
    }
}
