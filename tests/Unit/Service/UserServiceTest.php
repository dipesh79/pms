<?php

use App\Models\User;
use App\Service\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new UserService();
});

it('can create a user', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
        'phone' => '1234567890',
    ];

    $user = $this->service->createUser($data);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->email)->toBe('john@example.com')
        ->and(User::count())->toBe(1);
});
