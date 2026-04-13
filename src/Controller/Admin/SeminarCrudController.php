<?php

namespace App\Controller\Admin;

use App\Entity\Seminar;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class SeminarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Seminar::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Seminar')
            ->setEntityLabelInPlural('Seminars')
            ->setDefaultSort(['year' => 'DESC'])
            ->setHelp(
                'index',
                'One row per seminar year. The "active" seminar is determined by the current date: '
                . 'September 1 is the switchover. Dates from Sep 1 of year N through Aug 31 of year N+1 resolve to the June N+1 seminar.'
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('year', 'Year')
            ->setHelp('The calendar year the seminar takes place (e.g. 2026 for June 2026).');
        yield DateField::new('startDate', 'Start Date');
        yield DateField::new('endDate', 'End Date')
            ->setHelp('Used for the post-seminar eval window (opens on this date, closes 7 days later).');
    }
}
