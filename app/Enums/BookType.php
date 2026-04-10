<?php

namespace App\Enums;

enum BookType: string
{
    case GRAPHIC = 'graphic';
    case DIGITAL = 'digital';
    case PRINT = 'print';

    public function label(): string
    {
        return match ($this) {
            self::GRAPHIC => 'Graphic edition',
            self::DIGITAL => 'Digital edition',
            self::PRINT => 'Print edition',
        };
    }

    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }
}
