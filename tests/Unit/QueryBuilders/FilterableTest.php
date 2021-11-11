<?php

namespace Tests\Unit\QueryBuilders;

use App\QueryBuilders\Filterable;
use Tests\TestCase;

/** @see \App\QueryBuilders\Filterable */
class FilterableTest extends TestCase
{
    /** @test */
    public function filter()
    {
        $filterable = $this->getMockForTrait(Filterable::class, [], '', true, true, true, [
            'search',
        ]);

        $filterable
            ->expects($this->once())
            ->method('search');

        $result = $filterable->filter(['search' => true]);

        $this->assertSame($result, $filterable);
    }

    /** @test */
    public function filter_array()
    {
        $filterable = $this->getMockForTrait(Filterable::class, [], '', true, true, true, [
            'search',
        ]);

        $filterable
            ->expects($this->once())
            ->method('search');

        $result = $filterable->filter(['search' => [true, true]]);

        $this->assertSame($result, $filterable);
    }

    /** @test */
    public function filter_method_does_not_exist()
    {
        $filterable = $this->getMockForTrait(Filterable::class, [], '', true, true, true, [
            'call',
        ]);

        $filterable
            ->expects($this->never())
            ->method('call');

        $result = $filterable->filter(['search' => true]);

        $this->assertSame($result, $filterable);
    }
}
