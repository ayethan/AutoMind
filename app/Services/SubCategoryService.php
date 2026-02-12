<?php

namespace App\Services;

use App\SubCategory;
use App\Repositories\SubCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubCategoryService
{
    protected $subCategoryRepository;

    public function __construct(SubCategoryRepository $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    /**
     * Get all sub-categories with pagination and relations.
     *
     * @return LengthAwarePaginator
     */
    public function paginateSubCategories(): LengthAwarePaginator
    {
        return $this->subCategoryRepository->paginateSubCategories();
    }

    /**
     * Create a new sub-category.
     *
     * @param array $data
     * @return SubCategory
     */
    public function createSubCategory(array $data): SubCategory
    {
        return $this->subCategoryRepository->createSubCategory($data);
    }

    /**
     * Find a sub-category by its model instance and load relations.
     *
     * @param SubCategory $subCategory
     * @return SubCategory
     */
    public function findSubCategory(SubCategory $subCategory): SubCategory
    {
        return $this->subCategoryRepository->findSubCategory($subCategory);
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
        return $this->subCategoryRepository->updateSubCategory($subCategory, $data);
    }

    /**
     * Delete a sub-category.
     *
     * @param SubCategory $subCategory
     * @return bool|null
     */
    public function deleteSubCategory(SubCategory $subCategory): ?bool
    {
        return $this->subCategoryRepository->deleteSubCategory($subCategory);
    }
}
