<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\AssignAssetToProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class AssignAssetToProductRequestTest extends TestCase
{
    /** @test */
    public function it_can_assign_products_to_relationship()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'assign-asset-to-product.json', status: 201),
        ]);

        $response = $plytix->send(new AssignAssetToProductRequest('665db0db744c81d899bb315e', [
            'id' => '5c4ed8002f0985001e233275',
            'attribute_label' => 'thumbnail'
        ]));

        $assetId = $response->dto()[0];
        
        $mockClient->assertSent(AssignAssetToProductRequest::class);
        $this->assertEquals('5c4ed8002f0985001e233275', $assetId->id);
    }
}
