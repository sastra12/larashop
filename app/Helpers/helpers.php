<?php

use Carbon\Carbon;

function getYear($date)
{
    $year = new Carbon($date);
    return $year->year;
}

function notification($message, $alert)
{
    $notification = array(
        'message' => $message,
        'alert-type' => $alert
    );

    return $notification;
}
