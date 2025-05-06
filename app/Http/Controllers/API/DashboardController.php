<?php

namespace App\Http\Controllers\API;

use App\Models\Fact;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FactResource;
use App\Http\Resources\CategoryResource;

class DashboardController extends Controller
{
    public function index()
    {

        $factOfTheDay = Fact::all()->random();

        $categories = CategoryResource::collection(Category::all());

        $user = auth('sanctum')->user();


        return response()->json([
            'fact_of_the_day' => FactResource::make($factOfTheDay),
            'categories' => $categories,
            'user' => $user,
            'status' => true,
            'message' => 'Dashboard found successfully'
        ]);
    }
}
