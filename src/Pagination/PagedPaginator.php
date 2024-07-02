<?php

namespace Esign\Plytix\Pagination;

use LogicException;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator as BasePagedPaginator;
use Saloon\Traits\Body\HasJsonBody;

class PagedPaginator extends BasePagedPaginator
{
    protected ?int $perPageLimit = 25;
    protected string $paginationOrder = '';

    protected function isLastPage(Response $response): bool
    {
        $paginationPageSize = (int) $response->json('pagination.page_size');
        $paginationTotalCount = (int) $response->json('pagination.total_count');
        $paginationPage = (int) $response->json('pagination.page');

        return $paginationPageSize * $paginationPage >= $paginationTotalCount;
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        return $response->json('data');
    }

    protected function applyPagination(Request $request): Request
    {
        if (! in_array(
            needle: HasJsonBody::class,
            haystack: class_uses_recursive($request)
        )) {
            throw new LogicException(sprintf(
                'The request must use the %s trait to be paginated using the PagedPaginator.',
                HasJsonBody::class,
            ));
        }

        $request->body()->add('pagination', [
            'page' => $this->currentPage,
            'page_size' => $this->perPageLimit,
            'order' => $this->paginationOrder,
        ]);

        return $request;
    }

    public function setPaginationOrder(string $order): static
    {
        $this->paginationOrder = $order;

        return $this;
    }
}
