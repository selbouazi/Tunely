<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────────
        // 1. USERS (additional clients)
        // ──────────────────────────────────────────────
        $clients = [
            [
                'name' => 'Carlos García López',
                'email' => 'carlos@example.com',
                'password' => Hash::make('12341234'),
                'role' => 'client',
                'foto' => '/img/profiles/default.webp',
                'email_verified_at' => now()->subDays(30),
                'fecha_nacimiento' => '1990-05-15',
                'telefono' => '+34 612 345 678',
                'direccion' => 'C/ Mayor 45, 3º 2ª',
                'ciudad' => 'Madrid',
                'provincia' => 'Madrid',
                'codigo_postal' => '28001',
                'direccion_facturacion' => 'C/ Mayor 45, 3º 2ª',
                'ciudad_facturacion' => 'Madrid',
                'provincia_facturacion' => 'Madrid',
                'codigo_postal_facturacion' => '28001',
                'instrumento_preferido' => 'guitarra',
                'nivel_experiencia' => 'intermedio',
            ],
            [
                'name' => 'María Rodríguez Pérez',
                'email' => 'maria@example.com',
                'password' => Hash::make('12341234'),
                'role' => 'client',
                'foto' => '/img/profiles/default.webp',
                'email_verified_at' => now()->subDays(25),
                'fecha_nacimiento' => '1988-11-22',
                'telefono' => '+34 645 987 123',
                'direccion' => 'Av/ Diagonal 234, Àtic 1',
                'ciudad' => 'Barcelona',
                'provincia' => 'Barcelona',
                'codigo_postal' => '08018',
                'direccion_facturacion' => 'Av/ Diagonal 234, Àtic 1',
                'ciudad_facturacion' => 'Barcelona',
                'provincia_facturacion' => 'Barcelona',
                'codigo_postal_facturacion' => '08018',
                'instrumento_preferido' => 'teclado',
                'nivel_experiencia' => 'avanzado',
            ],
            [
                'name' => 'Antonio Martínez Ruiz',
                'email' => 'antonio@example.com',
                'password' => Hash::make('12341234'),
                'role' => 'client',
                'foto' => '/img/profiles/default.webp',
                'email_verified_at' => now()->subDays(20),
                'fecha_nacimiento' => '1995-03-08',
                'telefono' => '+34 678 123 456',
                'direccion' => 'C/ Valencia 78, 1º',
                'ciudad' => 'Valencia',
                'provincia' => 'Valencia',
                'codigo_postal' => '46002',
                'direccion_facturacion' => 'C/ Valencia 78, 1º',
                'ciudad_facturacion' => 'Valencia',
                'provincia_facturacion' => 'Valencia',
                'codigo_postal_facturacion' => '46002',
                'instrumento_preferido' => 'bateria',
                'nivel_experiencia' => 'principiante',
            ],
            [
                'name' => 'Laura Sánchez Fernández',
                'email' => 'laura@example.com',
                'password' => Hash::make('12341234'),
                'role' => 'client',
                'foto' => '/img/profiles/default.webp',
                'email_verified_at' => now()->subDays(15),
                'fecha_nacimiento' => '1992-07-30',
                'telefono' => '+34 698 741 258',
                'direccion' => 'C/ San Fernando 15, Bajo B',
                'ciudad' => 'Sevilla',
                'provincia' => 'Sevilla',
                'codigo_postal' => '41001',
                'direccion_facturacion' => 'C/ San Fernando 15, Bajo B',
                'ciudad_facturacion' => 'Sevilla',
                'provincia_facturacion' => 'Sevilla',
                'codigo_postal_facturacion' => '41001',
                'instrumento_preferido' => 'viento',
                'nivel_experiencia' => 'intermedio',
            ],
            [
                'name' => 'David López Torres',
                'email' => 'david@example.com',
                'password' => Hash::make('12341234'),
                'role' => 'client',
                'foto' => '/img/profiles/default.webp',
                'email_verified_at' => now()->subDays(10),
                'fecha_nacimiento' => '1985-12-14',
                'telefono' => '+34 612 987 654',
                'direccion' => 'C/ Gran Vía 56, 5º D',
                'ciudad' => 'Bilbao',
                'provincia' => 'Vizcaya',
                'codigo_postal' => '48001',
                'direccion_facturacion' => 'C/ Gran Vía 56, 5º D',
                'ciudad_facturacion' => 'Bilbao',
                'provincia_facturacion' => 'Vizcaya',
                'codigo_postal_facturacion' => '48001',
                'instrumento_preferido' => 'indiferente',
                'nivel_experiencia' => 'profesional',
            ],
        ];

        $existingClient = DB::table('users')->where('email', 'client@tunely.com')->first();
        if (!$existingClient) {
            array_unshift($clients, [
                'name' => 'Client Tunely',
                'email' => 'client@tunely.com',
                'password' => Hash::make('12341234'),
                'role' => 'client',
                'foto' => '/img/profiles/default.webp',
                'email_verified_at' => now()->subDays(30),
                'fecha_nacimiento' => '1996-09-01',
                'telefono' => '+34 600 000 000',
                'direccion' => 'C/ Mayor 1',
                'ciudad' => 'Barcelona',
                'provincia' => 'Barcelona',
                'codigo_postal' => '08001',
                'direccion_facturacion' => 'C/ Mayor 1',
                'ciudad_facturacion' => 'Barcelona',
                'provincia_facturacion' => 'Barcelona',
                'codigo_postal_facturacion' => '08001',
                'instrumento_preferido' => 'guitarra',
                'nivel_experiencia' => 'principiante',
            ]);
        }

        $userIds = [];
        foreach ($clients as $client) {
            $userIds[] = DB::table('users')->insertGetId($client);
        }

        // ──────────────────────────────────────────────
        // 2. ORDERS
        // ──────────────────────────────────────────────
        $statuses = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
        $instrumentIds = DB::table('instruments')->pluck('id')->toArray();

        $ordersData = [
            // [user_index, days_ago, status, [[instrument_index, qty], ...]]
            [0, 35, 'entregado', [[0, 1], [3, 1]]],
            [0, 28, 'entregado', [[5, 1]]],
            [1, 30, 'entregado', [[12, 1], [14, 2]]],
            [2, 25, 'entregado', [[18, 1]]],
            [3, 22, 'entregado', [[20, 1], [21, 1]]],
            [4, 20, 'entregado', [[7, 1], [8, 1]]],
            [0, 18, 'enviado', [[1, 1], [2, 1]]],
            [1, 15, 'enviado', [[22, 1]]],
            [2, 12, 'pagado', [[15, 1], [16, 1]]],
            [3, 10, 'pagado', [[4, 1]]],
            [4, 8, 'pagado', [[10, 1], [11, 1], [25, 1]]],
            [0, 5, 'pendiente', [[6, 1], [9, 1]]],
            [1, 3, 'pendiente', [[17, 1], [19, 1]]],
            [2, 1, 'pendiente', [[24, 1]]],
            [3, 0, 'pendiente', [[26, 1], [27, 1], [28, 1]]],
        ];

        $orderIds = [];
        foreach ($ordersData as $i => [$userIdx, $daysAgo, $status, $items]) {
            $createdAt = now()->subDays($daysAgo);
            $total = 0;
            $itemRows = [];

            foreach ($items as [$instIdx, $qty]) {
                $instId = $instrumentIds[$instIdx % count($instrumentIds)];
                $instrument = DB::table('instruments')->where('id', $instId)->first();
                $unitPrice = $instrument->precio;
                $total += $unitPrice * $qty;
                $itemRows[] = [
                    'instrument_id' => $instId,
                    'cantidad' => $qty,
                    'precio_unitario' => $unitPrice,
                ];
            }

            $userId = $userIds[$userIdx % count($userIds)];
            $user = DB::table('users')->where('id', $userId)->first();

            $ciudades = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Bilbao'];
            $provincias = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Vizcaya'];
            $cps = ['28001', '08001', '46001', '41001', '48001'];
            $idx = $userIdx % 5;

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'total' => round($total, 2),
                'estado' => $status,
                'shipping_name' => $user->name,
                'shipping_address' => $user->direccion ?? 'C/ Principal 1',
                'shipping_city' => $ciudades[$idx],
                'shipping_province' => $provincias[$idx],
                'shipping_postal_code' => $cps[$idx],
                'shipping_phone' => $user->telefono ?? '+34 600 000 000',
                'billing_same_as_shipping' => true,
                'billing_name' => $user->name,
                'billing_address' => $user->direccion ?? 'C/ Principal 1',
                'billing_city' => $ciudades[$idx],
                'billing_province' => $provincias[$idx],
                'billing_postal_code' => $cps[$idx],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $orderIds[] = $orderId;

            foreach ($itemRows as $row) {
                $row['order_id'] = $orderId;
                $row['created_at'] = $createdAt;
                $row['updated_at'] = $createdAt;
                DB::table('order_items')->insert($row);
            }

            // Decrement stock
            foreach ($itemRows as $row) {
                DB::table('instruments')
                    ->where('id', $row['instrument_id'])
                    ->where('stock', '>=', $row['cantidad'])
                    ->decrement('stock', $row['cantidad']);
            }

            // ──────────────────────────────────────────────
            // 3. PENDING COMMENTS (for delivered/shipped/paid orders)
            // ──────────────────────────────────────────────
            if (in_array($status, ['entregado', 'enviado', 'pagado'])) {
                foreach ($itemRows as $row) {
                    $hasCommented = $status === 'entregado' && rand(0, 1) === 0;
                    DB::table('pending_comments')->insert([
                        'user_id' => $userId,
                        'order_id' => $orderId,
                        'instrument_id' => $row['instrument_id'],
                        'has_commented' => $hasCommented,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }

        // ──────────────────────────────────────────────
        // 4. RATINGS (for delivered/paid orders that have_commented)
        // ──────────────────────────────────────────────
        $comments = [
            'Excelente calidad de sonido, muy recomendable.',
            'Buena relación calidad-precio. Me ha sorprendido gratamente.',
            'El envío llegó rápido y bien embalado. El instrumento perfecto.',
            'Muy buen acabado. Se nota que es de una marca de confianza.',
            'Es mi segunda compra en Tunely y siempre cumplen.',
            'El sonido es espectacular para el precio que tiene.',
            'Ideal para empezar. Muy buena opción para principiantes.',
            'La atención al cliente fue excelente cuando tuve una duda.',
            'Llevo un mes con el instrumento y funciona perfectamente.',
            'Lo compré usado y parece nuevo. Muy bien restaurado.',
        ];

        $pendingCompleted = DB::table('pending_comments')
            ->where('has_commented', true)
            ->get();

        foreach ($pendingCompleted as $pc) {
            // Check if rating already exists (unique constraint)
            $exists = DB::table('ratings')
                ->where('user_id', $pc->user_id)
                ->where('instrument_id', $pc->instrument_id)
                ->exists();

            if (!$exists) {
                DB::table('ratings')->insert([
                    'user_id' => $pc->user_id,
                    'instrument_id' => $pc->instrument_id,
                    'rating' => rand(3, 5),
                    'comment' => $comments[array_rand($comments)],
                    'created_at' => now()->subDays(rand(1, 10)),
                    'updated_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }

        // ──────────────────────────────────────────────
        // 4b. Extra PENDING COMMENTS (has_commented=false for banner demo)
        // ──────────────────────────────────────────────
        $usersWithFewPending = DB::table('users')
            ->where('role', 'client')
            ->whereNotIn('id', function ($q) {
                $q->select('user_id')->from('pending_comments')->where('has_commented', false);
            })
            ->pluck('id')
            ->toArray();

        $extraInstruments = DB::table('instruments')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(6)
            ->pluck('id')
            ->toArray();

        foreach ($usersWithFewPending as $uid) {
            $order = DB::table('orders')->where('user_id', $uid)->first();
            $oid = $order ? $order->id : DB::table('orders')->inRandomOrder()->value('id');

            foreach ($extraInstruments as $instId) {
                $exists = DB::table('pending_comments')
                    ->where('user_id', $uid)
                    ->where('instrument_id', $instId)
                    ->exists();
                if (!$exists) {
                    DB::table('pending_comments')->insert([
                        'user_id' => $uid,
                        'order_id' => $oid,
                        'instrument_id' => $instId,
                        'has_commented' => false,
                        'created_at' => now()->subDays(rand(1, 5)),
                        'updated_at' => now()->subDays(rand(1, 5)),
                    ]);
                }
            }
        }

        // ──────────────────────────────────────────────
        // 5. CONTACT MESSAGES
        // ──────────────────────────────────────────────
        $messages = [
            [
                'name' => 'Pedro Sánchez',
                'email' => 'pedro@example.com',
                'subject' => 'Consulta sobre guitarra Fender Stratocaster',
                'message' => 'Buenas, me gustaría saber si la Fender Stratocaster Player está disponible en color sunburst. Gracias.',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@example.com',
                'subject' => 'Vender mi bajo Fender Precision',
                'message' => 'Hola, tengo un bajo Fender Precision Bass del 2019 en perfecto estado que me gustaría vender. ¿Cómo funciona el proceso de valoración?',
            ],
            [
                'name' => 'Javier Ruiz',
                'email' => 'javier@example.com',
                'subject' => 'Devolución pedido #1024',
                'message' => 'Recibí el teclado Yamaha PSR-E373 pero no funciona la tecla Mi central. Quiero solicitar la devolución.',
            ],
            [
                'name' => 'Marta López',
                'email' => 'marta@example.com',
                'subject' => 'Información sobre envío a Canarias',
                'message' => 'Buenos días, estoy interesada en comprar una batería electrónica Roland TD-17KVX pero vivo en Tenerife. ¿Hacen envíos a Canarias? ¿Cuánto tardaría?',
            ],
            [
                'name' => 'Roberto Díaz',
                'email' => 'roberto@example.com',
                'subject' => 'Garantía del saxofón Selmer',
                'message' => 'Compré un saxofón Selmer SS600 usado y me gustaría saber cuánto tiempo de garantía tiene y qué cubre exactamente.',
            ],
        ];

        foreach ($messages as $msg) {
            DB::table('contact_messages')->insert([
                'name' => $msg['name'],
                'email' => $msg['email'],
                'subject' => $msg['subject'],
                'message' => $msg['message'],
                'created_at' => now()->subDays(rand(1, 20)),
                'updated_at' => now()->subDays(rand(1, 20)),
            ]);
        }
    }
}
