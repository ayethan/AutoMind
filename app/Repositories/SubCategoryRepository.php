<?php

namespace App\Repositories;

use App\SubCategory;
use App\Utils\Helpers;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubCategoryRepository
{
    /**
     * Get all sub-categories with pagination and relations.
     *
     * @return LengthAwarePaginator
     */
    public function paginateSubCategories(): LengthAwarePaginator
    {
        return SubCategory::with(["category"])->paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Create a new sub-category.
     *
     * @param array $data
     * @return SubCategory
     */
    public function createSubCategory(array $data): SubCategory
    {
        return SubCategory::create($data);
    }

    /**
     * Find a sub-category by its model instance and load relations.
     *
     * @param SubCategory $subCategory
     * @return SubCategory
     */
    public function findSubCategory(SubCategory $subCategory): SubCategory
    {
        return $subCategory->load(["category"]);
    }

    /**
     * Update an existing sub-category.
     *
     * @param SubCategory $subCategory
     * @param array $data
     * @return SubCategory
     */
    public function updateSubCategory(SubCategory $subCategory, array $data): SubCategory
    {
        $subCategory->fill($data)->save();
        return $subCategory;
    }

    /**
     * Delete a sub-category.
     *
     * @param SubCategory $subCategory
     * @return bool|null
     */
    public function deleteSubCategory(SubCategory $subCategory): ?bool
    {
        return $subCategory->delete();
    }
}
