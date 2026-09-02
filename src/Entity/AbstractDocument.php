<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use DateTimeImmutable;
use DateTime;
use InvalidArgumentException;

abstract class AbstractDocument
{
    protected ?int $id;
    protected DateTimeImmutable $dateDepot;

    public function __construct(DateTimeInterface|string $dateDepot = new DateTimeImmutable(), ?int $id = null)
    {
        $this->id = $id;
        $this->setDateDepot($dateDepot);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getDateDepot(): DateTimeImmutable
    {
        return $this->dateDepot;
    }

    public function setDateDepot(DateTimeInterface|string $dateDepot): static
    {
        if (is_string($dateDepot)) {
            try {
                $dateDepot = new DateTimeImmutable($dateDepot);
            } catch (\Exception $e) {
                throw new InvalidArgumentException("Format de date de depot invalide : " . $e->getMessage());
            }
        } elseif ($dateDepot instanceof DateTime) {
            $dateDepot = DateTimeImmutable::createFromMutable($dateDepot);
        }

        $this->dateDepot = $dateDepot;
        return $this;
    }
}
