<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Instrument;
use Tests\TestCase;

class ViewContentTest extends TestCase
{
    public function test_catalogo_view_contains_iva_text(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'precio' => 100,
            'disponible' => true,
        ]);

        $response = $this->get('/catalogo');
        $response->assertStatus(200);
        $response->assertSee('IVA incluido');
    }

    public function test_welcome_view_contains_iva_text(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'precio' => 100,
            'disponible' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('IVA incluido');
    }

    public function test_contacto_view_contains_payment_methods(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
        $response->assertSee('Visa');
        $response->assertSee('Mastercard');
        $response->assertSee('Bizum');
        $response->assertSee('Transferencia');
    }

    public function test_faq_view_renders(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
        $response->assertSee('Preguntas Frecuentes');
    }

    public function test_faq_view_contains_expected_questions(): void
    {
        $response = $this->get('/faq');
        $response->assertSee('¿Tienen instrumentos nuevos y usados?');
        $response->assertSee('¿Cuál es la política de devoluciones?');
        $response->assertSee('¿Ofrecen garantía en los instrumentos?');
    }

    public function test_detalle_instrumento_view_shows_correct_data(): void
    {
        $category = Category::factory()->create([
            'nombre' => 'Guitarras',
        ]);

        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'marca' => 'Fender',
            'modelo' => 'Stratocaster',
            'precio' => 1200,
            'stock' => 5,
            'disponible' => true,
        ]);

        $response = $this->get("/catalogo/{$instrument->id}");
        $response->assertStatus(200);
        $response->assertSee('Fender');
        $response->assertSee('Stratocaster');
        $response->assertSee('1200€');
        $response->assertSee('IVA incluido');
    }

    public function test_detalle_view_shows_agotado_when_no_stock(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'stock' => 0,
            'disponible' => true,
        ]);

        $response = $this->get("/catalogo/{$instrument->id}");
        $response->assertStatus(200);
        $response->assertSee('Agotado');
    }

    public function test_detalle_view_shows_stock_when_available(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'disponible' => true,
        ]);

        $response = $this->get("/catalogo/{$instrument->id}");
        $response->assertStatus(200);
        $response->assertSee('10 unidades');
    }
}
