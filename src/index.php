<?php

declare(strict_types=1);

namespace App;

require_once 'vendor/autoload.php';

use InitPHP\CLITable\Table;
use Yasumi\Provider\France;
use Yasumi\Provider\Germany;
use Yasumi\Provider\Poland;
use Yasumi\Yasumi;

/**
 * @param class-string[] $countries
 * @return array{string, string, array<string, array<string, string>>}
 */
function getHolidaysMatrix(array $countries, int $year): array
{
    /** @var array<string, array<string, array<string>>> $holidaysMatrix */
    $holidaysMatrix = [];

    foreach ($countries as $country) {
        foreach (Yasumi::create($country, $year)->getHolidays() as $holiday) {
            $holidaysMatrix[$holiday->format('Y-m-d')][$country] = $holiday->getName();
        }
    }

    ksort($holidaysMatrix);

    return [
        $countries,
        $holidaysMatrix,
    ];
}

function displayTable(array $countries, array $matrix): void
{
    $table = new Table();
    foreach ($matrix as $date => $holidays) {
        $rowData = [
            'date' => $date,
        ];

        foreach ($countries as $country) {
            $countryOneHolidayName = $holidays[$country] ?? '';

            $rowData[$country] = $countryOneHolidayName;
        }

        $table->row($rowData);
    }

    echo $table;
}

displayTable(...getHolidaysMatrix([France::class, France\Moselle::class, France\BasRhin::class, France\HautRhin::class], 2025));
displayTable(...getHolidaysMatrix([Germany::class, Germany\Berlin::class], 2025));
displayTable(...getHolidaysMatrix([Poland::class], 2025));
