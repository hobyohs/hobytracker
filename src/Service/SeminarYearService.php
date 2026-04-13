<?php

namespace App\Service;

use App\Entity\Seminar;
use App\Repository\SeminarRepository;

class SeminarYearService
{
    public function __construct(private SeminarRepository $seminarRepository)
    {
    }

    /**
     * Returns the "active" seminar year based on the current date.
     *
     * Switchover is September 1: dates from Sep 1 of year N through Aug 31 of year N+1
     * resolve to the June N+1 seminar. This is a pure date calculation and does NOT
     * require a Seminar row to exist — it always returns an int so downstream
     * `seminar_year` filters keep working even if an admin hasn't added the row yet.
     */
    public function getActiveSeminarYear(): int
    {
        $month = (int) date('n');
        $year  = (int) date('Y');
        return $month >= 9 ? $year + 1 : $year;
    }

    /**
     * Returns the Seminar entity for the active year, or null if the admin
     * hasn't created it yet.
     */
    public function getActiveSeminar(): ?Seminar
    {
        return $this->seminarRepository->findByYear($this->getActiveSeminarYear());
    }

    /**
     * Returns the seminar end date for the active year, or null if not configured.
     */
    public function getSeminarEndDate(): ?\DateTimeImmutable
    {
        return $this->getActiveSeminar()?->getEndDate();
    }

    /**
     * Returns the seminar start date for the active year, or null if not configured.
     */
    public function getSeminarStartDate(): ?\DateTimeImmutable
    {
        return $this->getActiveSeminar()?->getStartDate();
    }

    /**
     * Returns true if the eval period is currently open.
     * Opens on the seminar end date (inclusive) and closes 7 days later (inclusive).
     *
     * Safe default: if no Seminar row exists for the active year, evals are considered
     * OPEN. This preserves prior behavior (where a missing SEMINAR_END_DATE env var
     * meant "always open") and avoids locking admins out during dev/pre-config.
     */
    public function isEvalPeriodOpen(): bool
    {
        $endDate = $this->getSeminarEndDate();
        if ($endDate === null) {
            return true;
        }

        $closeDate = $endDate->modify('+7 days');
        $now       = new \DateTimeImmutable();

        return $now >= $endDate && $now <= $closeDate;
    }

    /**
     * Returns the DateTime when the eval period closes, or null if not configured.
     */
    public function getEvalPeriodClose(): ?\DateTimeImmutable
    {
        return $this->getSeminarEndDate()?->modify('+7 days');
    }
}
