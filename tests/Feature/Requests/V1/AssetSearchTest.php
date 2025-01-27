<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\DataTransferObjects\V1\Asset;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\AssetSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class AssetSearchTest extends TestCase
{
    /** @test */
    public function it_can_send_an_asset_search_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'asset-search.json', status: 200),
        ]);

        $response = $plytix->send(new AssetSearchRequest());

        $mockClient->assertSent(AssetSearchRequest::class);
        $this->assertEquals('5c483ee8eb9139000154dd5e', $response->json('data.0.id'));
    }

    /** @test */
    public function it_can_generate_a_dto_from_a_response_with_minimum_attributes()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'asset-search.json', status: 200),
        ]);

        $response = $plytix->send(new AssetSearchRequest());
        $assets = $response->dto();

        $this->assertIsArray($assets);
        $this->assertCount(5, $assets);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertEquals('5c483ee8eb9139000154dd5e', $assets[0]->id);
    }

    /** @test */
    public function it_can_generate_a_dto_from_a_response_with_all_attribute()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'asset-search-with-all-attributes.json', status: 200),
        ]);

        $response = $plytix->send(new AssetSearchRequest());
        $assets = $response->dto();

        $this->assertIsArray($assets);
        $this->assertCount(5, $assets);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertEquals('5c483ee8eb9139000154dd5e', $assets[0]->id);
        $this->assertEquals(false, $assets[0]->assigned);
        $this->assertEquals([], $assets[0]->categories);
        $this->assertEquals('image/png', $assets[0]->contentType);
        $this->assertEquals('k9189-1.jpg', $assets[0]->filename);
        $this->assertEquals('jpg', $assets[0]->extension);
        $this->assertEquals(169232, $assets[0]->fileSize);
        $this->assertEquals('IMAGES', $assets[0]->fileType);
        $this->assertEquals(false, $assets[0]->hasCustomThumb);
        $this->assertEquals(0, $assets[0]->nCatalogs);
        $this->assertEquals(0, $assets[0]->nProducts);
        $this->assertEquals(true, $assets[0]->public);
        $this->assertEquals('ACTIVE', $assets[0]->status);
        $this->assertEquals('https://files.plytix.com/api/v1.1/thumb/public_files/pim/assets/d1/6d/8e/5b/5b8e6dd17f7f46000c7e9629/images/d8/c7/fa/5b/5c483ee8eb9139000154dd5e/k9189-1.jpg', $assets[0]->thumbnail);
        $this->assertEquals('https://files.plytix.com/api/v1.1/file/public_files/pim/assets/d1/6d/8e/5b/5b8e6dd17f7f46000c7e9629/images/d8/c7/fa/5b/5c483ee8eb9139000154dd5e/k9189-1.jpg', $assets[0]->url);
        $this->assertEquals('2019-01-23 10:16:08', $assets[0]->created->format('Y-m-d H:i:s'));
        $this->assertEquals('2019-01-23 10:16:08', $assets[0]->modified->format('Y-m-d H:i:s'));
    }
}
