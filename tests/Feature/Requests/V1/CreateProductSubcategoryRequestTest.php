<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\CreateProductCategoryRequest;
use Esign\Plytix\Requests\V1\CreateProductSubcategoryRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class CreateProductSubcategoryRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_create_product_subcategory_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/create-product-category.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/create-product-subcategory.json', status: 200),
        ]);

        $category = $plytix->send(new CreateProductCategoryRequest([
            'name' => 'Kitchen Sinks',
        ]))->dto()[0];

        $response = $plytix->send(new CreateProductSubcategoryRequest($category->id, [
            'name' => 'Apron',
        ]));

        $subcategory = $response->dto()[0];
        $mockClient->assertSent(CreateProductSubcategoryRequest::class);

        $this->assertEquals('5cfa0c1e3da03100019ba90f', $subcategory->id);
        $this->assertEquals('Apron', $subcategory->name);
        $this->assertEquals('1', $subcategory->order);
        $this->assertEquals('apron', $subcategory->slug);
        $this->assertEquals(0, $subcategory->nChildren);
        $this->assertIsArray($subcategory->parentsIds);
        $this->assertCount(1, $subcategory->parentsIds);
        $this->assertEquals('5cfa05273da03100019ba90e', $subcategory->parentsIds[0]);
        $this->assertIsArray($subcategory->path);
        $this->assertCount(2, $subcategory->path);
        $this->assertEquals('Kitchen Sinks', $subcategory->path[0]);
    }
}
