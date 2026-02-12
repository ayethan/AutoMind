<?php

namespace App\Services;

use App\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCategories(): LengthAwarePaginator
    {
        return $this->categoryRepository->paginateCategories();
    }

    /**
     * Get all categories with their sub-categories.
     *
     * @return Collection
     */
    public function getAllCategoriesWithSubCategories(): Collection
    {
        return $this->categoryRepository->getAllCategoriesWithSubCategories();
    }

    /**
     * Create a new category and optionally its sub-categories.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return $this->categoryRepository->createCategory($data);
    }

    /**
     * Find a category by its model instance.
     *
     * @param Category $category
     * @return Category
     */
    public function findCategory(Category $category): Category
    {
        return $this->categoryRepository->findCategory($category);
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
        return $this->categoryRepository->updateCategory($category, $data);
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool|null
     */
    public function deleteCategory(Category $category): ?bool
    {
        return $this->categoryRepository->deleteCategory($category);
    }
}
