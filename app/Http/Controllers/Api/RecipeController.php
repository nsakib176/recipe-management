<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    // Show all the recipes
    public function index(Request $request)
    {
        try {
            $recipes = Recipe::query();

            // Optional filtering or sorting
            if ($request->has('category')) {
                $recipes->where('category_id', $request->category);
            }
            if ($request->has('sort_by')) {
                $recipes->orderBy($request->sort_by);
            }

            $recipes = $recipes->paginate(10);  // Adjust pagination as needed

            return response()->json([
                'Recipes' => $recipes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching recipes',
                'error' => $e->getMessage()
            ]);
        }
    }

    // Show a specific recipe
    public function show(Request $request, $id)
    {
        try {
            $recipe = Recipe::findOrFail($id);

            return response()->json([
                'Recipe' => $recipe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching recipe',
                'error' => $e->getMessage()
            ]);
        }
    }

    // Create a new recipe
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string',
                'instructions' => 'required|string',
                'dietary_restrictions' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Optional image validation
            ]);

            $recipe = new Recipe();
            $recipe->fill($request->only([
                // Fields to fill from request data
                'name',
                'description',
                'ingredients',
                'instructions',
                'dietary_restrictions',
                'image',
            ]));

            // Associate user
            $recipe->user_id = Auth::user()->id;

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/recipe_images');  // Adjust path as needed
                $recipe->image = $imagePath;  // Set the image path in the recipe model
            }

            $recipe->save();

            return response()->json([
                'message' => 'Recipe created successfully',
                'Recipe' => $recipe
            ], 201);  // Return 201 Created status code
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating recipe',
                'error' => $e->getMessage()
            ], 500);  // Return 500 Internal Server Error status code
        }
    }
}
