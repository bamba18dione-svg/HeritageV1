<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeImmutable;
use DateTimeInterface;
use DateTime;
use InvalidArgumentException;

class DateUtils
{
    public static function convertirDate(DateTimeInterface|string|null $date, string $nomChamp): DateTimeImmutable
    {
        if ($date === null || $date === '') {
            if ($nomChamp === 'date de depot') {
                return new DateTimeImmutable();
            }
            throw new InvalidArgumentException(sprintf("Le champ '%s' est obligatoire.", $nomChamp));
        }

        if ($date instanceof DateTimeImmutable) {
            return $date;
        }

        if ($date instanceof DateTime) {
            return DateTimeImmutable::createFromMutable($date);
        }

        if (is_string($date)) {
            try {
                return new DateTimeImmutable($date);
            } catch (\Exception $e) {
                throw new InvalidArgumentException(sprintf("Le champ '%s' doit être une date valide.", $nomChamp));
            }
        }

        throw new InvalidArgumentException(sprintf("Le champ '%s' doit être une date valide.", $nomChamp));
    }
}
