<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{

    protected $catList = [];

    public function getParentCategories(Request $request)
    {
        try {
            $allCategories = Category::with('parentCategory')->paginate(10);
            return view('categories.index', compact('allCategories'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSubCategories(Request $request)
    {
        try {
            $subCategories = Category::with('subCategories')->find($request->id);
            return response()->json(['status' => true, 'data' => $subCategories]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create(Request $request)
    {
        try {
            $parentCategories = Category::whereNull('parent_id')->get();
            return view('categories.create', compact('parentCategories'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $category = Category::findOrFail($id);

            $parentCategories = Category::whereNull('parent_id')->get();
            $parentCategories = $parentCategories->transform(function ($item) use ($id) {
                if ($item->id == $id)
                    return $item->setAttribute('selected', true);
                else
                    return $item;
            });

            $cats = $this->getAllParentCategories($category->parent_id);
            $allParentCategoriesList = array_reverse($this->getAllCategoriesInSameLevel($cats));
            if (count($allParentCategoriesList) == 0)
                $allParentCategoriesList[0] = $parentCategories->toArray();

            return view('categories.edit', compact('category', 'allParentCategoriesList'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllParentCategories($parentId)
    {
        $cat = Category::find($parentId);

        if ($cat != null) {
            $this->catList[] = $cat->setAttribute('selected', true)->toArray();
            $parentsArr = $this->getAllParentCategories($cat->parent_id);
            if ($parentsArr)
                $this->catList = $parentsArr;
        }
        return $this->catList;
    }

    public function getAllCategoriesInSameLevel($parentCatsArr)
    {
        $cats = array_map(function ($parent) {

            $res = Category::where(function ($q) use ($parent) {
                $q->where('parent_id', $parent['parent_id']);
                $q->where('id', '!=', $parent['id']);
            })->get()->toArray();
            $res[] = $parent;
            return $res;

        }, $parentCatsArr);

        return $cats;
    }

    public function store(Request $request)
    {
        try {
            $categoriesList = json_decode($request->categories_list);
            $parentId = count($categoriesList) > 0 ? end($categoriesList) : null;
            $newCategory = Category::create([
                'name' => trim($request->name),
                'parent_id' => $parentId,
            ]);
            return response()->json(['status' => true, 'data' => $newCategory]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $category = Category::find($id);
            if (!$category)
                return response()->json(['status' => false, 'data' => null, 'message' => 'This category does not exist !!']);

            $categoriesList = json_decode($request->categories_list);
            $parentId = count($categoriesList) > 0 ? end($categoriesList) : null;

            if ($id == $parentId)
                return response()->json(['status' => false, 'data' => null, 'message' => 'The same section cannot be chosen as a parent category']);

            $category->update([
                'name' => trim($request->name),
                'parent_id' => $parentId,
            ]);
            return response()->json(['status' => true, 'data' => null]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $category = Category::find($id);

            if (!$category)
                return redirect()->back()->withErrors(['message' => 'This category does not exist !!']);

            if ($category->subCategories()->count() > 0)
                return redirect()->back()->withErrors(['message' => 'Category with sub categories can not be deleted !!']);

            $category->delete();

            DB::commit();
            return redirect()->back()->with(['successMessage' => 'Category deleted successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


}
