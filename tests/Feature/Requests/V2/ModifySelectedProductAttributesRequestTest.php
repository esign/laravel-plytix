<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\ModifySelectedProductAttributesRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ModifySelectedProductAttributesRequestTest extends TestCase
{
    /** @test */
    public function it_can_patch_attribute_of_product(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/update-product-set-attribute.json', status: 201),
        ]);

        $response = $plytix->send(new ModifySelectedProductAttributesRequest('6797dcaeec3db037adfe04fd', [
            'description' => '12345',
        ]));

        $mockClient->assertSent(ModifySelectedProductAttributesRequest::class);
        $product = $response->dto();
        $this->assertEquals('6797dcaeec3db037adfe04fd', $product->id);
        $this->assertEquals('2025-01-27 19:49:28', $product->modified->format('Y-m-d H:i:s'));
        $this->assertEquals('2025-01-27 19:21:18', $product->created->format('Y-m-d H:i:s'));
    }
}
