<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Instrument;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_catalogo_returns_successful_response(): void
    {
        $response = $this->get('/catalogo');
        $response->assertStatus(200);
    }

    public function test_contacto_returns_successful_response(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
    }

    public function test_faq_returns_successful_response(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
    }

    public function test_quien_somos_returns_successful_response(): void
    {
        $response = $this->get('/quien-somos');
        $response->assertStatus(200);
    }

    public function test_detalle_instrumento_returns_successful_response(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create(['category_id' => $category->id]);

        $response = $this->get("/catalogo/{$instrument->id}");
        $response->assertStatus(200);
    }

    public function test_detalle_instrumento_returns_404_for_invalid_id(): void
    {
        $response = $this->get('/catalogo/99999');
        $response->assertStatus(404);
    }
}
