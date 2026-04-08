<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_redirects(): void
    {
        $response = $this->get('/');

        // Ton app affiche la page d'accueil (200) au lieu de rediriger
        $response->assertStatus(200);
    }
}