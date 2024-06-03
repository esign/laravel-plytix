<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\CreateAssetRequest;
use Esign\Plytix\Requests\CreateProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateAssetRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_create_asset_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'create-asset.json', status: 201),
        ]);

        $response = $plytix->send(new CreateAssetRequest([
            'filename' => 'kettle_blue.jpg',
            'url' => 'https://www.plytix.com/hubfs/Kitchen%20and%20Home%20Products/Enjoy%20Mug%20-%20Blue.jpg',
        ]));

        $mockClient->assertSent(CreateAssetRequest::class);
        $this->assertEquals('kettle_blue.jpg', $response->json('data.0.filename'));
    }
}
