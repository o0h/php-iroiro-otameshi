<?php

declare(strict_types=1);
use App\DateTimeUtil;

$chronos = Cake\Chronos\Chronos::now();

$a = DateTimeUtil::startOfDay($chronos);
$a->addDays(10);
$b = DateTimeUtil::startOfDayNonTemplate($chronos);
$b->addDays(10);
