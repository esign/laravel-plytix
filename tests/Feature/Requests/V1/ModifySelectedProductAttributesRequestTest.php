<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\ModifySelectedProductAttributesRequest;
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
            'description' => '12345',
        ]));

        $product = $response->dto()[0];

        $mockClient->assertSent(ModifySelectedProductAttributesRequest::class);
        $this->assertEquals('5c4ed8002f0985001e233275', $product->id);
        $this->assertEquals('Sample - 001', $product->sku);
        $this->assertEquals('Product ~ 1 - patch example', $product->label);
        $this->assertEquals('Completed', $product->status);
        $this->assertEquals(0, $product->numVariations);

        $this->assertIsArray($product->attributes);
        $this->assertCount(2, $product->attributes);

        $this->assertIsArray($product->categories);
        $this->assertCount(1, $product->categories);

        $this->assertIsArray($product->assets);
        $this->assertCount(1, $product->assets);

        $this->assertEquals(["Blue"], $product->attributes["color_create_a_multiselect_type_attribute"]);
        $this->assertEquals('Kettles & Teapots', $product->categories[0]->name);
        $this->assertEquals('Kettle handle - White.png', $product->assets[0]->filename);
    }
}
