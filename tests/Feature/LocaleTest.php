<?php

namespace Tests\Feature;

use Tests\BaseTestCase;
use AlbertCht\Lumen\Testing\Concerns\RefreshDatabase;

class LocaleTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function testSetLocalFromHeader()
    {
        $this->withHeaders(['Accept-Language' => 'zh-TW'])
            ->postJson('/api/login');
        $this->assertEquals('zh-TW', config('app.locale'));

        $this->withHeaders(['Accept-Language' => 'en-US'])
            ->postJson('/api/login');
        $this->assertEquals('en', config('app.locale'));
    }
}
