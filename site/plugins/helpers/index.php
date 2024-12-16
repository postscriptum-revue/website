<?php
function formatDate($date, $format = 'dd.MM.yyyy', $ucfirst = true)
{
    if (!$date || $date == '') {
        return '';
    } else {
        $dateObj = new DateTime($date);
        
        $formatter = new IntlDateFormatter(
            'fr_FR', // Locale set to French (France)
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null, // Use the default timezone (or specify one if necessary)
            IntlDateFormatter::GREGORIAN, // Calendar type
            $format // Custom format parameter
        );
        $fmt_date = $formatter->format($dateObj);
        return $ucfirst ? ucfirst($fmt_date) : $fmt_date;
    }
}