<?php

namespace Esign\Plytix\Tests\Feature\DataTransferObjects\Casts;

use Esign\Plytix\DataTransferObjects\Casts\PlytixDateTimeCast;
use Esign\Plytix\Tests\TestCase;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class PlytixDateTimeCastTest extends TestCase
{
    /** @test */
    public function it_can_cast_plytix_date_times()
    {
        $class = new class extends Data {
            #[WithCast(PlytixDateTimeCast::class)]
            public Carbon $date;
        };

        $this->assertEquals(
            new Carbon('2024-03-05 11:59:56.348000'),
            $class::from(['date' => '2024-03-05T11:59:56.348000+00:00'])->date
        );

        $this->assertEquals(
            new Carbon('2024-02-19 10:21:45'),
            $class::from(['date' => '2024-02-19T10:21:45+00:00'])->date
        );
    }
}
