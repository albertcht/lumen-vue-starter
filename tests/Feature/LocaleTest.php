<?php

namespace Tests\Feature;

use Tests\BaseTestCase;

class LocaleTest extends BaseTestCase
{
    /** @test */
    public function getTranslations()
    {
        $this->get('/api/translations/en')
            ->seeStatusCode(200)
            ->seeJson(['ok' => 'Ok']);
    }

    /** @test */
    public function setLocalFromHeader()
    {
        $this->json('post', '/api/login', [], ['Accept-Language' => 'zh-TW']);
        $this->assertEquals('zh-TW', config('app.locale'));

        $this->json('post', '/api/login', [], ['Accept-Language' => 'en-US']);
        $this->assertEquals('en', config('app.locale'));
    }
}
