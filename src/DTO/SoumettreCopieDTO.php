<?php

declare(strict_types=1);

namespace App\DTO;

use App\Service\DateUtils;
use App\Service\NoteValidator;
use DateTimeImmutable;

readonly class SoumettreCopieDTO
{
    public function __construct(
        public float $noteBrute,
        public DateTimeImmutable $dateDepot,
        public DateTimeImmutable $dateLimite
    ) {
    }

    public static function fromArray(array $data): self
    {
        $noteBrute  = NoteValidator::validate($data['note_brute'] ?? $data['noteBrute'] ?? null);
        $dateDepot  = DateUtils::convertirDate($data['date_depot'] ?? $data['dateDepot'] ?? null, 'date de depot');
        $dateLimite = DateUtils::convertirDate($data['date_limite'] ?? $data['dateLimite'] ?? null, 'date limite');

        return new self($noteBrute, $dateDepot, $dateLimite);
    }

    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    public function getDateDepot(): DateTimeImmutable
    {
        return $this->dateDepot;
    }

    public function getDateLimite(): DateTimeImmutable
    {
        return $this->dateLimite;
    }
}
