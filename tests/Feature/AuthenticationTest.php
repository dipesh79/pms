<?php

test('it registers a user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'user@gmail.com',
        'phone' => '+1234567890',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'role',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);
    $this->assertDatabaseHas('users',
        [
            'email' => 'user@gmail.com',
            'name' => 'Test User',
            'phone' => '+1234567890',
            'role' => 'user'
        ]
    );
});

test('it logins a user', function () {
    $user = \App\Models\User::factory()->create(
        [
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'role',
                'token',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);
});

test('logs out a user', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);

    $response = $this->postJson('/api/logout');
    $response->assertStatus(200)
        ->assertJson([
            'data' => [],
            'message' => 'Logout successful',
        ]);
});

test('returns authenticated user', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);

    $response = $this->getJson('/api/me');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'role',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);
});
