<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NoteType extends Enum
{
    const Observation = "Observation";
    const Error = "Error";

    public static function ColorMap(): array
    {
        return [
            self::Observation => 'info',
            self::Error => 'danger',
        ];
    }

    public static function IconMap(): array
    {
        return [
            self::Observation => 'heroicon-o-eye',
            self::Error => 'heroicon-o-exclamation-circle',
        ];
    }
}
