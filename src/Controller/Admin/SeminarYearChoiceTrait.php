<?php

namespace App\Controller\Admin;

use App\Repository\SeminarRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Adds a reusable `seminarYearField()` helper that builds an EasyAdmin ChoiceField
 * populated from the Seminar table. Falls back to a free-text-style dropdown of
 * the computed active year (+/- 1) if no Seminar rows exist, so admins are never
 * locked out of creating records before the Seminars table is populated.
 */
trait SeminarYearChoiceTrait
{
    private SeminarRepository $seminarRepository;

    #[Required]
    public function setSeminarRepository(SeminarRepository $seminarRepository): void
    {
        $this->seminarRepository = $seminarRepository;
    }

    protected function seminarYearField(string $propertyName = 'seminarYear', string $label = 'Year'): ChoiceField
    {
        $seminars = $this->seminarRepository->findBy([], ['year' => 'DESC']);
        $choices  = [];
        foreach ($seminars as $seminar) {
            $choices[(string) $seminar->getYear()] = $seminar->getYear();
        }

        // Fallback for empty table: offer the active year and the one on either side.
        if (empty($choices)) {
            $month      = (int) date('n');
            $calYear    = (int) date('Y');
            $activeYear = $month >= 9 ? $calYear + 1 : $calYear;
            foreach ([$activeYear - 1, $activeYear, $activeYear + 1] as $y) {
                $choices[(string) $y] = $y;
            }
        }

        return ChoiceField::new($propertyName, $label)
            ->setChoices($choices)
            ->renderAsNativeWidget();
    }
}
