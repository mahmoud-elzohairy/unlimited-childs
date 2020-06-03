<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getParentCategories(Request $request)
    {
        try {
            $parentCategories = Category::whereNull('parent_id')->get();
            return view('categories', compact('parentCategories'));
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
}
