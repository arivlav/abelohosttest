<?php

namespace App\Http\Actions;

use App\Common\Config;
use App\Common\Request;
use App\Enums\OrderDirection;
use App\Repositories\PDOCategoryRepository;
use App\Services\ArticleService;

readonly class CategoriesShowAction implements IAction
{
    public function __construct(
        private PDOCategoryRepository $categoryRepository,
        private ArticleService $articleService,
        private Request $request,
    )
    {
    }

    public function execute(?int $id = null): array
    {
        if ($id === null) {
            return [];
        }

        $category = $this->categoryRepository->findById($id);
        if (!$category) {
            return [];
        }

        [$page, $perPage, $sort, $direction] = $this->getQueryParams();

        $result = $this->articleService->findByCategoryWithPagination(
            $id,
            $sort,
            $direction,
            $perPage,
            ($page - 1) * $perPage
        );

        $total = $result['total'];
        $pages = $total > 0 ? (int)\ceil($total / $perPage) : 1;

        return [
            'title' => $category['name'],
            'category' => [
                'id' => (int)$category['id'],
                'name' => $category['name'],
                'description' => $category['description'] ?? '',
            ],
            'articles' => $result['items'],
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'pages' => $pages,
                'sort' => $sort,
                'direction' => strtolower($direction) === 'asc' ? 'asc' : 'desc',
            ],
        ];
    }

    private function getQueryParams(): array
    {
        $requestParams = $this->request->getParams();
        return [
            $requestParams['page'] ?? 1,
            $requestParams['perPage'] ?? Config::get('pagination')['perPage'],
            $requestParams['sort'] ?? 'published_at',
            $requestParams['direction'] ?? OrderDirection::DESC->value
        ];
    }
}