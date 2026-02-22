<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ToolStatus extends Enum
{
    const Disponible = "Disponible";
    const NoDisponible = "No Disponible";
    const NoFunctionnal = "No Functionnal";
    const Archived = "Archived";




    public static function ColorMap(): array
    {
        return [
            self::Disponible => 'success',
            self::NoDisponible => 'danger',
            self::NoFunctionnal => 'danger',
            self::Archived => 'warning',
        ];
    }

    public static function IconMap(): array
    {
        return [
            self::Disponible => 'heroicon-o-check-circle',
            self::NoDisponible => 'heroicon-o-x-circle',
            self::NoFunctionnal => 'heroicon-o-x-circle',
            self::Archived => 'heroicon-o-archive-box',
        ];
    }
}


