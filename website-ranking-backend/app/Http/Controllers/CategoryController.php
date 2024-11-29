<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Website;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function listCategories(Request $request)
    {
        $searchTerm = '%' . $request->input('search', '') . '%';
        $categories = Category::where('name', 'LIKE', $searchTerm)
            ->orderBy('name', 'asc')
            ->paginate($request->input('limit', 20));

        return $this->json(200, 'Categories retrieved successfully.', $categories);
    }

    public function index(Request $request)
    {
        $searchTerm = '%' . $request->input('search', '') . '%';
        $categories = Category::where('name', 'LIKE', $searchTerm)
            ->orderBy('name', 'asc')
            ->paginate($request->input('limit', 20));

        foreach ($categories as $key => $cat) {
            $cat['websiteCount'] = Website::where('category_id', $cat->id)->count();
        }

        return $this->json(200, 'Categories retrieved successfully.', $categories);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create($validatedData);

        return $this->json(201, 'Category created successfully.', $category);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return $this->json(200, 'Category retrieved successfully.', $category);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validatedData);

        return $this->json(200, 'Category updated successfully.', $category);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return $this->json(200, 'Category deleted successfully.');
    }
}
