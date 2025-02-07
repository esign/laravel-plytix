<?php

namespace Esign\Plytix\Tests\Support;

use Saloon\Http\Faking\MockResponse;

class MockResponseFixture
{
    public static function make($fixtureName, $status = 200, $headers = []): MockResponse
    {
        return new MockResponse(
            body: file_get_contents(__DIR__ . "/../Fixtures/Saloon/{$fixtureName}"),
            status: $status,
            headers: $headers
        );
    }

    public static function makeEmpty(): MockResponse
    {
        return new MockResponse(body: '', status: 204);
    }
}
