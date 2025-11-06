<?php

namespace Esign\Plytix\Tests\Feature\Pagination;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\ProductCategoriesSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Illuminate\Support\Arr;
use LogicException;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class PagedPaginationTest extends TestCase
{
    #[Test]
    public function it_can_loop_through_each_page_until_no_results_are_left()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-1.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-2.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-3.json', status: 200),
        ]);

        $paginator = $plytix->paginate(new ProductCategoriesSearchRequest());

        foreach ($paginator as $response) {
            // Let's loop so the paginator fetches all pages
        }

        $mockClient->assertSentCount(3, ProductCategoriesSearchRequest::class);
    }

    #[Test]
    public function it_can_throw_an_exception_when_the_request_class_does_not_implement_the_has_json_body_trait()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
        ]);

        $request = new class () extends Request implements Paginatable {
            protected Method $method = Method::GET;

            public function resolveEndpoint(): string
            {
                return 'fake-endpoint';
            }
        };

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf(
            'The request must use the %s trait to be paginated using the PagedPaginator.',
            HasJsonBody::class,
        ));

        foreach ($plytix->paginate($request) as $response) {
            // Let's loop so the paginator fetches all pages
        }
    }

    #[Test]
    public function it_can_set_the_pagination_order()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-1.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-2.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-3.json', status: 200),
        ]);

        $paginator = $plytix->paginate(new ProductCategoriesSearchRequest());
        $paginator->setPaginationOrder('order');

        foreach ($paginator as $response) {
            // Let's loop so the paginator fetches all pages
        }

        $mockClient->assertSent(function (Request $request, Response $response) {
            if ($request instanceof ProductCategoriesSearchRequest) {
                return Arr::get($request->body()->all(), 'pagination.order') === 'order';
            }
        });
    }

    #[Test]
    public function it_can_use_an_empty_string_as_pagination_order_default()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-1.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-2.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-categories-search-page-3.json', status: 200),
        ]);

        $paginator = $plytix->paginate(new ProductCategoriesSearchRequest());

        foreach ($paginator as $response) {
            // Let's loop so the paginator fetches all pages
        }

        $mockClient->assertSent(function (Request $request, Response $response) {
            if ($request instanceof ProductCategoriesSearchRequest) {
                return Arr::get($request->body()->all(), 'pagination.order') === '';
            }
        });
    }
}
