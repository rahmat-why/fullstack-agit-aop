<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function view()
    {
        return view('categories');
    }

    public function index()
    {
        // Only return non-deleted categories
        $categories = Category::whereNull('deleted_at')
            ->select('id', 'name', 'created_at', 'updated_at')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function getDeleted()
    {
        $categories = Category::whereNotNull('deleted_at')
            ->select('id', 'name', 'deleted_at')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json(['data' => $category], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'updated_at' => now()
        ]);

        return response()->json(['data' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'deleted_at' => now()
        ]);

        return response()->json(['data' => $category]);
    }
}