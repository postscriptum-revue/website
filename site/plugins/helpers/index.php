<?php
function formatDate($date, $format = 'd.m.Y')
{
    if (!$date || $date == '') {
        return '';
    } else {
        return date($format, strtotime($date));
    }
}