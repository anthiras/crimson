<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectionTest extends TestCase
{
    public function testVerifyTypeSuccess()
    {
        $collection = collect([new MyCollectionTestClass(), new MyCollectionTestClass()]);
        $collection->verifyType(MyCollectionTestClass::class);
        $this->assertCount(2, $collection);
    }

    public function testVerifyTypeException()
    {
        $this->expectException(\Exception::class);
        collect([new MyCollectionTestClass(), "1234"])->verifyType(MyCollectionTestClass::class);
    }
}

class MyCollectionTestClass
{

}