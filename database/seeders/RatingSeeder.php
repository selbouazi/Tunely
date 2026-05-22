<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->where('role', 'client')->pluck('id')->toArray();
        $instrumentIds = DB::table('instruments')->pluck('id')->toArray();

        if (empty($userIds) || empty($instrumentIds)) {
            return;
        }

        $reviews = [
            ['rating' => 5, 'comment' => 'Excelente calidad de construcción. El sonido es increíble, superó todas mis expectativas.'],
            ['rating' => 4, 'comment' => 'Muy buen producto por el precio. La entrega fue rápida y bien embalada.'],
            ['rating' => 5, 'comment' => 'Llevo 3 meses con él y funciona perfectamente. Muy recomendable para profesionales.'],
            ['rating' => 3, 'comment' => 'Está bien para empezar, pero se nota que es de gama de entrada. Cumple su función.'],
            ['rating' => 4, 'comment' => 'Buena relación calidad-precio. Los acabados son correctos y el sonido es limpio.'],
            ['rating' => 5, 'comment' => 'Impresionante. Compré el mismo modelo en otra tienda y este llegó en mejores condiciones.'],
            ['rating' => 2, 'comment' => 'Esperaba más calidad por el precio. El acabado no es tan bueno como en las fotos.'],
            ['rating' => 4, 'comment' => 'Muy contento con la compra. El envío fue rápido y el producto llegó perfectamente afinado.'],
            ['rating' => 5, 'comment' => 'Espectacular. Lo uso tanto en estudio como en directo y suena de maravilla.'],
            ['rating' => 3, 'comment' => 'Cumple con lo básico. Para iniciarse está bien, pero un profesional notará las limitaciones.'],
            ['rating' => 4, 'comment' => 'Muy buena compra. La atención al cliente de Tunely fue excelente resolviendo mis dudas.'],
            ['rating' => 5, 'comment' => 'No puedo estar más satisfecho. El instrumento es exactamente lo que buscaba.'],
            ['rating' => 1, 'comment' => 'Llegó con un pequeño golpe en la esquina. El embalaje no era el adecuado.'],
            ['rating' => 4, 'comment' => 'Buena calidad general. Lo recomendaría a cualquiera que busque un instrumento fiable.'],
            ['rating' => 5, 'comment' => 'Sonido profesional a un precio razonable. Sin duda volveré a comprar aquí.'],
        ];

        $inserted = 0;
        foreach ($userIds as $userId) {
            $numRatings = rand(1, 3);
            $usedInstruments = [];

            for ($i = 0; $i < $numRatings; $i++) {
                $instrumentId = $instrumentIds[array_rand($instrumentIds)];

                if (in_array($instrumentId, $usedInstruments)) {
                    continue;
                }
                $usedInstruments[] = $instrumentId;

                $exists = DB::table('ratings')
                    ->where('user_id', $userId)
                    ->where('instrument_id', $instrumentId)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $review = $reviews[array_rand($reviews)];
                $createdAt = now()->subDays(rand(1, 60));

                DB::table('ratings')->insert([
                    'user_id' => $userId,
                    'instrument_id' => $instrumentId,
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $inserted++;
            }
        }

        $this->command->info("Insertadas {$inserted} valoraciones nuevas.");
    }
}
