<?php

namespace App\Database;

require_once __DIR__ . '/../src/Database/Database.php';

// Alias pour compatibilité si appelé depuis database/Database.php
class_alias(Database::class, 'Database');
