<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\AssignProductsToRelationshipRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class AssignProductsToRelationshipRequestTest extends TestCase
{
    #[Test]
    public function it_can_assign_products_to_relationship(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::makeEmpty(),
        ]);

        $response = $plytix->send(new AssignProductsToRelationshipRequest('productid', 'relationshipid', [
            'name' => '12345',
        ]));

        $mockClient->assertSent(AssignProductsToRelationshipRequest::class);
        $this->assertEquals('', $response->body());
        $this->assertEquals(null, $response->dto());
    }
}
