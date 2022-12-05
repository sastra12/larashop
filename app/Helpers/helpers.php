<?php

use Carbon\Carbon;

function getYear($date)
{
    $year = new Carbon($date);
    return $year->year;
}
