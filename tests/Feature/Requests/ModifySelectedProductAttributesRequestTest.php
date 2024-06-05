<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\CreateProductCategoryRequest;
use Esign\Plytix\Requests\CreateRelationshipRequest;
use Esign\Plytix\Requests\ModifySelectedProductAttributesRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ModifySelectedProductAttributesRequestTest extends TestCase
{
    /** @test */
    public function it_can_patch_attribute_of_product()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'update-product-set-attribute.json', status: 201),
        ]);

        $response = $plytix->send(new ModifySelectedProductAttributesRequest('5c4ed8002f0985001e233275', [
            'description' => '12345'
        ]));

        $product = $response->dto()[0];
        
        $mockClient->assertSent(ModifySelectedProductAttributesRequest::class);
        $this->assertEquals('5c4ed8002f0985001e233275', $product->id);
    }
}
