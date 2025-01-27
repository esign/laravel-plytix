<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\CreateAssetRequest;
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
        $asset = $response->dto()[0];

        $mockClient->assertSent(CreateAssetRequest::class);
        $this->assertEquals('5c4c6e10ca59f4061b800ffa', $asset->id);
        $this->assertEquals(false, $asset->assigned);
        $this->assertEquals('jpg', $asset->extension);
        $this->assertEquals('kettle_blue.jpg', $asset->filename);
        $this->assertEquals('image/jpeg', $asset->contentType);
        $this->assertEquals(31885, $asset->fileSize);
        $this->assertEquals('IMAGES', $asset->fileType);
        $this->assertEquals(0, $asset->nCatalogs);
        $this->assertEquals(0, $asset->nProducts);
        $this->assertEquals(true, $asset->public);
        $this->assertEquals('ACTIVE', $asset->status);
        $this->assertEquals('https://files.plytix.com/api/v1.1/thumb/kettle_blue.jpg', $asset->thumbnail);
        $this->assertEquals('https://files.plytix.com/api/v1.1/file/public_files/assets/kettle_blue.jpg', $asset->url);
    }
}
