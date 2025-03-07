<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\DataTransferObjects\V1\Relationship;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\RelationshipSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class RelationshipSearchRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_relationship_search_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/relationship-search.json', status: 200),
        ]);

        $response = $plytix->send(new RelationshipSearchRequest());

        $mockClient->assertSent(RelationshipSearchRequest::class);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $response->json('data.0.id'));
    }

    #[Test]
    public function it_can_generate_a_dto_from_a_response_with_minimum_attributes(): void
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/relationship-search.json', status: 200),
        ]);

        $response = $plytix->send(new RelationshipSearchRequest());
        $relationships = $response->dto();

        $this->assertIsArray($relationships);
        $this->assertCount(1, $relationships);
        $this->assertInstanceOf(Relationship::class, $relationships[0]);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $relationships[0]->id);
    }

    #[Test]
    public function it_can_generate_a_dto_from_a_response_with_all_attributes(): void
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/relationship-search-with-all-attributes.json', status: 200),
        ]);

        $response = $plytix->send(new RelationshipSearchRequest());
        $relationships = $response->dto();

        $this->assertIsArray($relationships);
        $this->assertCount(1, $relationships);
        $this->assertInstanceOf(Relationship::class, $relationships[0]);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $relationships[0]->id);
        $this->assertEquals('gift_pack', $relationships[0]->label);
        $this->assertEquals('Gift pack', $relationships[0]->name);
        $this->assertEquals('2023-07-11 08:06:01', $relationships[0]->created->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-07-11 08:06:01', $relationships[0]->modified->format('Y-m-d H:i:s'));
    }
}
