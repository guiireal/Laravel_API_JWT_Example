<?php

namespace App\Utils;

class Document
{
    public static function formatBrazilianCPF(?string $cpf): string
    {
        if (empty($cpf)) return '';

        return substr($cpf, 0, 3) . '.'
            . substr($cpf, 3, 3) . '.'
            . substr($cpf, 6, 3) . '-'
            . substr($cpf, 9, 2);
    }

    public static function formatBrazilianCNPJ(?string $cnpj): string
    {
        if (empty($cnpj)) return '';

        return substr($cnpj, 0, 2) . '.'
            . substr($cnpj, 2, 3) . '.'
            . substr($cnpj, 5, 3) . '-'
            . substr($cnpj, 7, 4) . '/'
            . substr($cnpj, 12, 2);
    }
}
