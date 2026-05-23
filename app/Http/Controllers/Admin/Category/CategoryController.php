<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
  public function index(): View
  {
    return view('admin.categories.list');
  }

  public function create(): View
  {
    return view('admin.categories.create');
  }

  public function store(CategoryRequest $request): RedirectResponse
  {
    $data = $request->validated();
    Category::create($data);
    Cache::forget('sidebar_data');
    Cache::forget('all_categories');

    return to_route('category.index')->with('success', 'Category has been created.');
  }

  public function edit(string $id): View
  {
    $category = Category::findOrFail($id);

    return view('admin.categories.edit', compact('category'));
  }

  public function update(CategoryRequest $request, string $id): RedirectResponse
  {
    $category = Category::findOrFail($id);
    $data = $request->validated();
    $category->update($data);
    Cache::forget('sidebar_data');
    Cache::forget('all_categories');

    return to_route('category.index')->with('info', 'Category has been updated.');
  }

  public function destroy(Request $request, string $id): JsonResponse|RedirectResponse
  {
    $category = Category::findOrFail($id);
    $reassignToId = $request->input('reassign_to');

    try {
      DB::transaction(function () use ($category, $reassignToId) {
        if ($category->posts()->count() > 0) {
          if (!$reassignToId) {
            throw new \Exception('Please select a replacement category for the existing articles.');
          }

          $category->posts()->update(['category_id' => $reassignToId]);
        }

        $category->delete();
      });

      if ($request->expectsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'Category removed and articles reassigned successfully.',
        ]);
      }

      Cache::forget('sidebar_data');
      Cache::forget('all_categories');

      return to_route('category.index')->with('danger', 'Category has been deleted.');
    } catch (\Exception $e) {
      if ($request->expectsJson()) {
        return response()->json(
          [
            'success' => false,
            'message' => $e->getMessage(),
          ],
          422,
        );
      }

      return back()->with('warning', $e->getMessage());
    }
  }
}
