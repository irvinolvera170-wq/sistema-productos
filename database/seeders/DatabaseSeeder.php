<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => 'password',   // el cast "hashed" del modelo la cifra
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@test.com',
            'password' => 'password',
            'role' => 'empleado',
        ]);

        $categorias = collect(['Electrónica', 'Papelería', 'Hogar', 'Oficina', 'Accesorios'])
            ->map(fn ($nombre) => Categoria::create(['nombre' => $nombre]));

        $nombres = ['Laptop', 'Mouse', 'Teclado', 'Monitor', 'Audífonos', 'Silla',
                    'Escritorio', 'Lámpara', 'Cuaderno', 'Pluma', 'Mochila', 'Botella'];
        $sufijos = ['Pro', 'Lite', 'Max', 'Plus', 'Básico'];

        foreach ($nombres as $nombre) {
            foreach ($sufijos as $sufijo) {
                $existencias = random_int(0, 100) < 15 ? 0 : random_int(1, 100);

                $producto = Producto::create([
                    'nombre' => "$nombre $sufijo",
                    'descripcion' => "$nombre $sufijo para uso diario, con garantía de 6 meses.",
                    'categoria_id' => $categorias->random()->id,
                    'precio' => random_int(2000, 500000) / 100,
                    'existencias' => $existencias,
                    'estado' => $existencias > 0 ? 'disponible' : 'agotado',
                ]);

                // Fechas de registro variadas para probar el orden por fecha
                $producto->forceFill(['created_at' => now()->subDays(random_int(0, 90))])->save();
            }
        }
    }
}