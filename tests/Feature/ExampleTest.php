<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_redirects(): void
    {
        $response = $this->get('/');

        // Ton app redirige (302) depuis "/"
        $response->assertStatus(302);
    }
}
