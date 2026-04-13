<?php

namespace App\Service;

class SeminarYearService
{
    public function getActiveSeminarYear(): int
    {
        $month = (int) date('n');
        $year  = (int) date('Y');
        return $month >= 9 ? $year + 1 : $year;
    }

    /**
     * Returns true if the eval period is currently open.
     * Opens on SEMINAR_END_DATE and closes 7 days later.
     * If SEMINAR_END_DATE is not configured, evals are always open
     * (safe default for dev / pre-seminar configuration).
     */
    public function isEvalPeriodOpen(): bool
    {
        $endDateStr = $_ENV['SEMINAR_END_DATE'] ?? '';
        if (empty($endDateStr)) {
            return true;
        }

        $endDate   = new \DateTimeImmutable($endDateStr);
        $closeDate = $endDate->modify('+7 days');
        $now       = new \DateTimeImmutable();

        return $now >= $endDate && $now <= $closeDate;
    }

    /**
     * Returns the DateTime when the eval period closes, or null if not configured.
     */
    public function getEvalPeriodClose(): ?\DateTimeImmutable
    {
        $endDateStr = $_ENV['SEMINAR_END_DATE'] ?? '';
        if (empty($endDateStr)) {
            return null;
        }
        return (new \DateTimeImmutable($endDateStr))->modify('+7 days');
    }

    /**
     * Returns the seminar end date, or null if not configured.
     */
    public function getSeminarEndDate(): ?\DateTimeImmutable
    {
        $endDateStr = $_ENV['SEMINAR_END_DATE'] ?? '';
        if (empty($endDateStr)) {
            return null;
        }
        return new \DateTimeImmutable($endDateStr);
    }
}
