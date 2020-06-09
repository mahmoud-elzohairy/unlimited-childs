<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'parent_id'];

    // this relationship will only return one level of child items
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // recursive, loads all descendants
    public function childrenRecursive()
    {
        return $this->subCategories()->with('childrenRecursive');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

}
