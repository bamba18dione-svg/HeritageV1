<?php

declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;

final class NoteValidator
{
    private function __construct()
    {
    }

    public static function validate(float|string|null $noteBrute): float
    {
        if ($noteBrute === null || $noteBrute === '') {
            throw new InvalidArgumentException('La note brute doit etre une valeur numerique valide.');
        }

        if (is_string($noteBrute)) {
            $noteBrute = str_replace(',', '.', trim($noteBrute));
        }

        if (!is_numeric($noteBrute)) {
            throw new InvalidArgumentException('La note brute doit etre une valeur numerique valide.');
        }

        $noteFloat = (float) $noteBrute;
        if ($noteFloat < 0.0 || $noteFloat > 20.0) {
            throw new InvalidArgumentException(
                sprintf('La note brute doit etre comprise entre 0 et 20 (valeur recue : %.2f).', $noteFloat)
            );
        }

        return $noteFloat;
    }
}
