<?php

namespace Tests\Feature;

use Tests\BaseTestCase;
use AlbertCht\Lumen\Testing\Concerns\RefreshDatabase;

class LocaleTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function getTranslations()
    {
        $this->get('/api/translations/en')
            ->assertSuccessful()
            ->assertJson(['ok' => 'Ok']);
    }

    /** @test */
    public function setLocalFromHeader()
    {
        $this->withHeaders(['Accept-Language' => 'zh-TW'])
            ->postJson('/api/login');
        $this->assertEquals('zh-TW', config('app.locale'));

        $this->withHeaders(['Accept-Language' => 'en-US'])
            ->postJson('/api/login');
        $this->assertEquals('en', config('app.locale'));
    }
}
