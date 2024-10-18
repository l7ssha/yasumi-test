<?php

declare(strict_types=1);

namespace App;

require_once 'vendor/autoload.php';

use InitPHP\CLITable\Table;
use Yasumi\Provider\France;
use Yasumi\Provider\France\Moselle;
use Yasumi\Yasumi;

$franceProvider = Yasumi::create(France::class, 2025);
$moselleProvider = Yasumi::create(Moselle::class, 2025);

/** @var array<string, array<string, array<string>>> $holidaysMatrix */
$holidaysMatrix = [];
foreach ($franceProvider->getHolidays() as $holiday) {
    $holidaysMatrix[$holiday->format('Y-m-d')]['France'] = $holiday->getName();
}

foreach ($moselleProvider->getHolidays() as $holiday) {
    $holidaysMatrix[$holiday->format('Y-m-d')]['Moselle'] = $holiday->getName();
}

ksort($holidaysMatrix);

$table = new Table();

foreach ($holidaysMatrix as $date => $holidays) {
    $franceHolidayName = $holidays['France'] ?? '';
    $moselleHolidayName = $holidays['Moselle'] ?? '';

    $table->row([
        'date' => $date,
        'France' => $franceHolidayName,
        'Moselle' => $moselleHolidayName
    ]);
}

echo $table;
