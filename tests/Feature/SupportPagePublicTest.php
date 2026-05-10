<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportPagePublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_page_is_reachable_without_site_password_when_protection_enabled(): void
    {
        config(['site.password_protection_enabled' => true]);

        $response = $this->get('/support');

        $response->assertOk();
        $response->assertSee('الدعم الفني', false);
    }

    public function test_home_still_redirects_to_site_password_when_protection_enabled_and_not_verified(): void
    {
        config(['site.password_protection_enabled' => true]);

        $response = $this->get('/');

        $response->assertRedirect(route('site.password.show'));
    }
}
