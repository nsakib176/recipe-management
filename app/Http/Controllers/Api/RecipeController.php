<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            // TODO: Make reusable API response and implement
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
    public function show($id)
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

    // Update an existing recipe
    public function update(Request $request, $id)
    {
        try {
            $recipe = Recipe::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string',
                'instructions' => 'required|string',
                'dietary_restrictions' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Optional image validation
            ]);

            // TODO: Create authorize with AuthServiceProvider.php file and implement
            // check if the user is authorize to update the recipe
            if ($recipe->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Unauthorized to update this recipe'
                ], 401);  // Return 401 Unauthorized status code
            }

            $recipe->fill($request->only([
                // Fields to fill from request data
                'name',
                'description',
                'ingredients',
                'instructions',
                'dietary_restrictions',
                'image',
            ]));

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                //delete the old file
                if ($recipe->image != null) {
                    Storage::delete($recipe->image);  // Adjust path as needed
                    $recipe->image = null;  // Set the image path in the recipe model
                }
                //save new image
                $imagePath = $request->file('image')->store('public/recipe_images');  // Adjust path as needed
                $recipe->image = $imagePath;  // Set the image path in the recipe model
            }
            $recipe->save();
            return response()->json([
                'message' => 'Recipe updated successfully',
                'Recipe' => $recipe
            ], 201);  // Return 201 Created status code
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating recipe',
                'error' => $e->getMessage()
            ], 500);  // Return 500 Internal Server Error status code

        }
    }

    // Delete a recipe
    public function destroy(Request $request, $id)
    {
        try {
            $recipe = Recipe::findOrFail($id);

            // check if the user is authorize to delete the recipe
            if ($recipe->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Unauthorized to delete this recipe'
                ], 401);  // Return 401 Unauthorized status code
            }

            // Delete image if present
            if ($recipe->image) {
                Storage::delete($recipe->image);  // Delete image file
            }

            $recipe->delete();

            return response()->json([
                'message' => 'Recipe deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating recipe',
                'error' => $e->getMessage()
            ], 500);  // Return 500 Internal Server Error status code
        }
    }
}
