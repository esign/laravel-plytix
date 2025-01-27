<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\AssignProductsToRelationshipRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class AssignProductsToRelationshipRequestTest extends TestCase
{
    /** @test */
    public function it_can_assign_products_to_relationship()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'assign-products-to-relationship.json', status: 201),
        ]);

        $response = $plytix->send(new AssignProductsToRelationshipRequest('productid', 'relationshipid', [
            'name' => '12345',
        ]));

        $relationshipInformation = $response->dto()[0];

        $mockClient->assertSent(AssignProductsToRelationshipRequest::class);
        $this->assertEquals('5d8a50b547397aea2a603079', $relationshipInformation->relationshipId);
        $this->assertEquals('contains', $relationshipInformation->relationshipLabel);
        $this->assertIsArray($relationshipInformation->relatedProducts);
        $this->assertCount(4, $relationshipInformation->relatedProducts);
        $this->assertEquals(9, $relationshipInformation->relatedProducts[1]->quantity);
    }
}
