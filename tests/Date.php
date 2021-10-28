<?php

namespace gq_group\Tests;

use DateTime;

class Date {
    public static function get(string $day, string $month, string $year) : DateTime {
        return DateTime::createFromFormat("Y-m-d", "$year-$month-$day");
    }
}