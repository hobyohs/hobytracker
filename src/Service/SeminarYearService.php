<?php

namespace App\Service;

class SeminarYearService
{
    public function getActiveSeminarYear(): int
    {
        $month = (int) date('n');
        $year = (int) date('Y');
        return $month >= 9 ? $year + 1 : $year;
    }
}
