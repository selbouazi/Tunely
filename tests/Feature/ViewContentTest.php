<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Instrument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogo_renders_with_instruments(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'marca' => 'Fender',
            'modelo' => 'Stratocaster',
            'precio' => 100,
            'stock' => 5,
            'disponible' => true,
        ]);

        $response = $this->get('/catalogo');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Catalogo')
            ->has('instruments', 1)
            ->where('instruments.0.marca', 'Fender')
            ->where('instruments.0.modelo', 'Stratocaster')
        );
    }

    public function test_welcome_renders_with_instruments(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create([
            'category_id' => $category->id,
            'marca' => 'Fender',
            'modelo' => 'Stratocaster',
            'precio' => 100,
            'stock' => 5,
            'disponible' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Welcome')
            ->has('instruments', 1)
            ->where('instruments.0.marca', 'Fender')
        );
    }

    public function test_contacto_view_renders(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Contacto'));
    }

    public function test_faq_view_renders(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('FAQ'));
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
        $response->assertInertia(fn ($page) => $page
            ->component('InstrumentoDetalle')
            ->where('instrument.marca', 'Fender')
            ->where('instrument.modelo', 'Stratocaster')
            ->where('instrument.precio', 1200)
        );
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
        $response->assertInertia(fn ($page) => $page
            ->component('InstrumentoDetalle')
            ->where('instrument.stock', 0)
        );
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
        $response->assertInertia(fn ($page) => $page
            ->component('InstrumentoDetalle')
            ->where('instrument.stock', 10)
        );
    }
}
