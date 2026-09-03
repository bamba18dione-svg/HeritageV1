<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CopieExamen;


class CalculNoteSimpleService implements CalculNoteInterface
{
    
    public function calculer(CopieExamen $copie): float
    {
        $noteBrute = $copie->getNoteBrute();
        $copie->setPenaliteAppliquee(0.0);
        $copie->setNoteFinale($noteBrute);

        return $noteBrute;
    }
}
