<?php

function ofuscar_email(?string $email = null)
{
    if (!$email) {
        return '';
    }

    $split = explode('@', $email);

    if(sizeof($split) != 2) {
        return '';
    }

    $parte1          = $split[0];
    $qtd             = (int)floor(strlen($parte1) * 0.75);
    $restante        = strlen($parte1) - $qtd;
    $parte1Mascarada = substr($parte1, 0, $restante) . str_repeat('*', $qtd);

    $parte2          = $split[1];
    $qtd             = (int)floor(strlen($parte2) * 0.75);
    $restante        = strlen($parte2) - $qtd;
    $parte2Mascarada = str_repeat('*', $qtd) . substr($parte2, $restante * -1, $restante);

    return $parte1Mascarada . '@' . $parte2Mascarada;
}
