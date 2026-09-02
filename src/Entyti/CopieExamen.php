<?php

namespace App\Entity;

class CopieExamen extends AbstractDocument
{
    private float $noteBrute;
    private float $noteFinale;
    private bool $penaliteAppliquee;
    private \DateTime $dateLimite;

    public function __construct(
        \DateTime $dateDepot,
        float $noteBrute,
        \DateTime $dateLimite,
        ?int $id = null
    ) {
        parent::__construct($dateDepot, $id);

        $this->setNoteBrute($noteBrute);
        $this->dateLimite = $dateLimite;
        $this->penaliteAppliquee = false;
        $this->noteFinale = $noteBrute;
    }

    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    public function setNoteBrute(float $noteBrute): void
    {
        if ($noteBrute < 0 || $noteBrute > 20) {
            throw new \InvalidArgumentException(
                'La note doit être comprise entre 0 et 20.'
            );
        }

        $this->noteBrute = $noteBrute;
    }

    public function getNoteFinale(): float
    {
        return $this->noteFinale;
    }

    public function setNoteFinale(float $noteFinale): void
    {
        if ($noteFinale < 0 || $noteFinale > 20) {
            throw new \InvalidArgumentException(
                'La note finale doit être comprise entre 0 et 20.'
            );
        }

        $this->noteFinale = $noteFinale;
    }

    public function getPenaliteAppliquee(): bool
    {
        return $this->penaliteAppliquee;
    }

    public function setPenaliteAppliquee(bool $penaliteAppliquee): void
    {
        $this->penaliteAppliquee = $penaliteAppliquee;
    }

    public function getDateLimite(): \DateTime
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTime $dateLimite): void
    {
        $this->dateLimite = $dateLimite;
    }
}
