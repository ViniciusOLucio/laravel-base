<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Platform: string implements HasLabel
{
    case Admin = 'admin';
    case Advogado = 'advogado';
    case Cliente = 'cliente';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin => 'Painel do Admin',
            self::Advogado => 'Painel do Advogado',
            self::Cliente => 'Painel do Cliente',
        };
    }
}
