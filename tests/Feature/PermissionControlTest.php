<?php

use App\Enums\Can;
use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UsersSeeder};

use function Pest\Laravel\{actingAs, assertDatabaseHas, seed};

it('should be able to give an user a permission to do something', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    expect($user)
        ->hasPermissionTo(Can::BE_AN_ADMIN)
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where(['key' => Can::BE_AN_ADMIN])->first()->id,
    ]);
});

test('permission must have a seeder', function () {
    seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN,
    ]);
});

test('seed with an admin user', function () {
    seed([PermissionSeeder::class, UsersSeeder::class]);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->id,
        'permission_id' => Permission::where(['key' => Can::BE_AN_ADMIN])->first()?->id,
    ]);
});

it('should block the access to an admin page if the user does not have the permission to be an admin', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test("let's make sure that we are using cache to store user permissions", function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    $cacheKey = "user::{$user->id}::permissions";

    expect(Cache::has($cacheKey))->toBeTrue('Checking if cache key exists')
        ->and(Cache::get($cacheKey))->toBe($user->permissions, 'Checking if permissions are the same as the user');
});

test("let's make sure that we are using the cache the retrieve/check when the user has the given permission", function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    $hitOccurred = false;

    DB::listen(function ($query) use (&$hitOccurred) {
        $hitOccurred = true;
    });

    $user->hasPermissionTo(Can::BE_AN_ADMIN);

    $this->assertFalse($hitOccurred, 'Verifica se não houve hit no banco de dados');
});
