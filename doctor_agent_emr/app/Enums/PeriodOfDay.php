<?php

declare(strict_types=1);

namespace App\Enums;

enum PeriodOfDay: string
{
    case Morning = 'morning';
    case Afternoon = 'afternoon';
    case Evening = 'evening';

    public static function fromTime(\DateTimeInterface $time): self
    {
        $hour = (int) $time->format('H');

        return match (true) {
            $hour >= 5 && $hour < 12 => self::Morning,
            $hour >= 12 && $hour < 17 => self::Afternoon,
            default => self::Evening,
        };
    }

}
