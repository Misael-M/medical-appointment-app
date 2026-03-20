<?php

use App\Models\User; 
use Illuminate\Foundation\Testing\RefreshDatabase;

//Refresca la base de datos entre prueba
uses(RefreshDatabase::class);
test('un usuario no puede eliminarse a si mismo', function () {

    //Crear un usuario en la BD de pruebas
    $user = User::factory()->create(
        [
        'email_verified_at' => now()
        ]        
    );

    //Simular que el usuario esta logeado
    $this->actingAs($user, 'web');

    //Simular que intenta borrar un usuario
    $response = $this->delete(route('admin.usuarios.destroy', $user));

    //Esperar a que el servidor bloquee esta acción
    $response->assertStatus(403);

    //Verificamos que el usuario siga existiendo en la BD
    $this->assertDatabaseHas('users', [
        'id' => $user->id, 
    ]);
});
