<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\LinkAssetToProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class LinkAssetToProductRequestTest extends TestCase
{
    #[Test]
    public function it_can_link_asset_to_product(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/link-asset-to-product.json', status: 201),
        ]);

        $response = $plytix->send(new LinkAssetToProductRequest('665db0db744c81d899bb315e', [
            'id' => '5c4ed8002f0985001e233275',
            'attribute_label' => 'thumbnail',
        ]));

        $asset = $response->dto()[0];
        $mockClient->assertSent(LinkAssetToProductRequest::class);
        $this->assertEquals('5c4ed8002f0985001e233275', $asset->id);
    }
}
