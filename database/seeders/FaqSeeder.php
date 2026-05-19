<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => '¿Tienen instrumentos nuevos y usados?',
                'answer' => 'Sí, en Tunely vendemos tanto instrumentos musicales nuevos como de segunda mano. Cada instrumento usado pasa por nuestro control de calidad.',
                'order' => 0,
                'active' => true,
            ],
            [
                'question' => '¿Cuál es la política de devoluciones?',
                'answer' => 'Puedes devolver cualquier instrumento en un plazo de 14 días desde la compra, siempre que esté en perfecto estado y con su embalaje original.',
                'order' => 1,
                'active' => true,
            ],
            [
                'question' => '¿Ofrecen garantía en los instrumentos?',
                'answer' => 'Sí, todos nuestros instrumentos nuevos incluyen garantía de fabricante. Los instrumentos usados tienen garantía de 6 meses.',
                'order' => 2,
                'active' => true,
            ],
            [
                'question' => '¿Cómo puedo vender mi instrumento?',
                'answer' => 'Contáctanos a través de nuestro formulario indicando los datos del instrumento. Te ofreceremos una valoración gratuita en 24-48 horas.',
                'order' => 3,
                'active' => true,
            ],
            [
                'question' => '¿Realizan envíos a toda España?',
                'answer' => 'Sí, realizamos envíos a toda España peninsular. Los gastos de envío son gratuitos para pedidos superiores a 100€.',
                'order' => 4,
                'active' => true,
            ],
            [
                'question' => '¿Qué formas de pago aceptan?',
                'answer' => 'Aceptamos Visa, Mastercard, Bizum y transferencia bancaria. Todas las transacciones están securizadas.',
                'order' => 5,
                'active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->insert($faq);
        }
    }
}
