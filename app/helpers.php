<?php

function convertToTimeZone($datetime, $fromTimeZone, $toTimeZone = 'UTC')
{
    $fromTimeZoneObj = new DateTimeZone($fromTimeZone);
    $toTimeZoneObj = new DateTimeZone($toTimeZone);

    $dateTimeObj = new DateTime($datetime, $fromTimeZoneObj);
    $dateTimeObj->setTimeZone($toTimeZoneObj);

    return $dateTimeObj->format('Y-m-d H:i:s');
}