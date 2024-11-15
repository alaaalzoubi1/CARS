<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::where('name', $validatedData['name'])->first();

        if ($request->hasFile('icon')) {
            $imageName =time() . '.' . $request->file('icon')->getClientOriginalExtension();
            $iconPath = $request->file('icon')->storeAs('icons', $imageName, 'public');
        } else {
            $iconPath = null;
        }

        if ($category) {
            if ($category->is_deleted) {
                $category->is_deleted = false;
                $category->icon = $iconPath;
                $category->save();
                $message = 'Category restored successfully';
            } else {
                $message = 'Category already exists';
            }
        } else {
            $category = Category::create([
                'name' => $validatedData['name'],
                'icon' => $iconPath,
            ]);
            $message = 'Category created successfully';
        }

        return response()->json([
            'message' => $message,
            'category' => $category,
        ], 201);
    }
    public function show()
    {
        $categories = Category::where('is_deleted', false)
            ->select('name', 'id','icon')
            ->get();
        return response()->json([
            'categories' => $categories
        ]);
    }
    public function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ],404);
        }
        $category->is_deleted = true;
        $category->save();
        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'sometimes|string|max:255',
            'icon' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::find($request->id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        if ($request->hasFile('icon')) {
            $imageName = time() . '.' . $request->file('icon')->getClientOriginalExtension();
            $iconPath = $request->file('icon')->storeAs('icons', $imageName, 'public');
            if ($category->icon) {
                Storage::disk('public')->delete($category->icon);
            }
            $category->icon = $iconPath;
        }

        if ($request->exists('name')) {
            $category->name = $validatedData['name']; }

        $category->save();

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => ['name' => $category->name ,'icon' => $category->icon,'id' => $category->id],
        ], 200);
    }


}
