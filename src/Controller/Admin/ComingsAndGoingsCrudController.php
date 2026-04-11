<?php

namespace App\Controller\Admin;

use App\Entity\ComingsAndGoings;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;


class ComingsAndGoingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ComingsAndGoings::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Coming and Going')
            ->setEntityLabelInPlural('Comings and Goings')
        ;

        return $crud;
    }
    
    
    public function configureFields(string $pageName): iterable
    {
        
        yield AssociationField::new('ambassador');
        yield DateTimeField::new('departure');
        yield DateTimeField::new('arrival');
        yield BooleanField::new('checked_out');
        yield AssociationField::new('checkedOutBy')->setRequired(false);
        yield BooleanField::new('checked_in');
        yield AssociationField::new('checkedInBy')->setRequired(false);
        yield TextareaField::new('notes')->hideOnIndex();
        yield BooleanField::new('active');
        
    }
    
}
