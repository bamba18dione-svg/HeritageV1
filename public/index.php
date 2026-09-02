<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\CopieExamen;
use App\Database\Database;

$copie = new CopieExamen(
    new \DateTime('2026-09-01 10:00:00'),
    15.0,
    new \DateTime('2026-09-05 23:59:59')
);

echo "Note brute de la copie créée : " . $copie->getNoteBrute() . "\n";
