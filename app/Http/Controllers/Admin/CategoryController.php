<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('descendants.courses')
            ->withCount('courses')
            ->orderBy('sort_order')
            ->get();

        $tree = $this->buildTree($categories);

        return Inertia::render('Admin/Categories', [
            'categories' => $tree,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back();
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
        ]);

        // Prevent setting parent to self or descendant
        if ($request->parent_id) {
            $descendantIds = $category->getAllDescendantIds();
            if (in_array($request->parent_id, $descendantIds)) {
                return back()->withErrors(['parent_id' => 'Cannot set a descendant as parent.']);
            }
        }

        $category->update([
            'name' => $request->name,
            'slug' => $category->name !== $request->name ? $this->generateUniqueSlug($request->name, $category->id) : $category->slug,
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back();
    }

    private function buildTree($categories, $depth = 0): array
    {
        $result = [];
        foreach ($categories as $cat) {
            $coursesCount = $cat->courses_count ?? $cat->courses->count();
            $result[] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'parent_id' => $cat->parent_id,
                'sort_order' => $cat->sort_order,
                'depth' => $depth,
                'courses_count' => $coursesCount,
                'children_count' => $cat->descendants->count(),
            ];
            if ($cat->descendants->count()) {
                $result = array_merge($result, $this->buildTree($cat->descendants, $depth + 1));
            }
        }
        return $result;
    }

    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;
        $query = Category::where('slug', $slug);
        if ($excludeId) $query->where('id', '!=', $excludeId);
        while ($query->exists()) {
            $slug = $original . '-' . $count++;
            $query = Category::where('slug', $slug);
            if ($excludeId) $query->where('id', '!=', $excludeId);
        }
        return $slug;
    }
}
