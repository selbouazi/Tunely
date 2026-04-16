<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Instrument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_instruments_relationship(): void
    {
        $category = Category::factory()->create();
        Instrument::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->instruments);
    }

    public function test_category_can_have_zero_instruments(): void
    {
        $category = Category::factory()->create();

        $this->assertCount(0, $category->instruments);
    }

    public function test_category_instruments_returns_hasmany_instance(): void
    {
        $category = Category::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $category->instruments()
        );
    }

    public function test_category_instruments_related_to_instrument_model(): void
    {
        $category = Category::factory()->create();
        $instrument = Instrument::factory()->create(['category_id' => $category->id]);

        $relatedInstrument = $category->instruments->first();
        $this->assertInstanceOf(Instrument::class, $relatedInstrument);
        $this->assertEquals($instrument->id, $relatedInstrument->id);
    }
}
