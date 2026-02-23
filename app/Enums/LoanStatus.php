<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LoanStatus extends Enum
{
    const Pending = "Pending";
    const Completed = "Completed";

    public static function ColorMap(): array
    {
        return [
            self::Pending => 'warning',
            self::Completed => 'success',
        ];
    }

    public static function IconMap(): array
    {
        return [
            self::Completed => 'heroicon-o-eye',
            self::Pending => 'heroicon-o-exclamation-circle',
        ];
    }
}
