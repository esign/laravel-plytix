<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\CreateRelationshipRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class CreateRelationshipRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_create_relationship_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/create-relationship.json', status: 201),
        ]);

        $response = $plytix->send(new CreateRelationshipRequest([
            'name' => '12345',
        ]));

        $relationship = $response->dto()[0];

        $mockClient->assertSent(CreateRelationshipRequest::class);
        $this->assertEquals('12345', $relationship->name);
        $this->assertEquals('12345', $relationship->label);
        $this->assertEquals('665d88e1ffa3da952052dbf0', $relationship->id);
    }
}
