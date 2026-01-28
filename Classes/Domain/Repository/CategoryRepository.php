<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Repository;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\Repository;

class CategoryRepository extends Repository
{
    public function findAllAsArray(?Category $selectedCategory = null): array
    {
        $localCategories = $this->findAll();
        $categories = [];
        // Transform categories to array
        foreach ($localCategories as $localCategory) {
            if (($localCategory instanceof Category) === false) {
                continue;
            }

            $newCategory = [
                'uid' => $localCategory->getUid(),
                'title' => $localCategory->getTitle(),
                'parent' => $localCategory->getParent() ? $localCategory->getParent()->getUid() : null,
                'subcategories' => null,
                'isSelected' => $selectedCategory == $localCategory,
            ];
            $categories[] = $newCategory;
        }
        return $categories;
    }

    public function findSubcategoriesRecursiveAsArray(Category $parentCategory): array
    {
        $categories = [];
        $localCategories = $this->findAllAsArray();
        foreach ($localCategories as $category) {
            if ($category['uid'] === $parentCategory->getUid()) {
                $this->getSubcategoriesIds(
                    $localCategories,
                    $category,
                    $categories
                );
            }
        }
        return $categories;
    }

    protected function getSubcategoriesIds(
        array $categoriesArray,
        array $parentCategory,
        array &$subcategoriesArray
    ): void {
        $subcategoriesArray[] = $parentCategory['uid'];
        foreach ($categoriesArray as $category) {
            if ($category['parent'] === $parentCategory['uid']) {
                $this->getSubcategoriesIds(
                    $categoriesArray,
                    $category,
                    $subcategoriesArray
                );
            }
        }
    }
}
