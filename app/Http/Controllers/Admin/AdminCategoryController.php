<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::ordered()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'icone' => 'nullable|string|max:50',
            'ordre' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['nom']);

        Category::create($validated);

        return back()->with('success', 'Catégorie créée');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'icone' => 'nullable|string|max:50',
            'ordre' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['nom']);

        $category->update($validated);

        return back()->with('success', 'Catégorie mise à jour');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie supprimée');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            Category::where('id', $item['id'])->update(['ordre' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }
}