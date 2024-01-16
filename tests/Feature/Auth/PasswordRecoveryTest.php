<?php

use function Pest\Laravel\get;

it('deve ter uma rota de recuperar a senha', function () {
    get(route('auth.password.recovery'))
        ->assertOk();
});
