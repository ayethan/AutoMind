<?php

namespace App\Repositories;

use App\Category;
use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    /**
     * Get all categories with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCategories(): LengthAwarePaginator
    {
        return Category::paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Get all categories with their sub-categories.
     *
     * @return Collection
     */
    public function getAllCategoriesWithSubCategories(): Collection
    {
        return Category::with(["sub_categories"])->get();
    }

    /**
     * Create a new category and optionally its sub-categories.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            $category = Category::create($data);
            if (isset($data['sub_categories']) && is_array($data['sub_categories'])) {
                $category->sub_categories()->createMany($data['sub_categories']);
            }
            return $category;
        });
    }

    /**
     * Find a category by its model instance.
     *
     * @param Category $category
     * @return Category
     */
    public function findCategory(Category $category): Category
    {
        return $category;
    }

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $category->fill($data)->save();
        return $category;
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool|null
     */
    public function deleteCategory(Category $category): ?bool
    {
        return $category->delete();
    }
}
