<?php

namespace App\Controller\Admin;

use App\Entity\Applicant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApplicantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Applicant::class;
    }
    

    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(250)
            ->setDefaultSort(['lastName' => 'ASC'])
        ;
    
    }
    
        public function configureFields(string $pageName): iterable
    {
            
            
        
            yield IdField::new('id')->onlyOnIndex();
            yield Field::new('firstName')->hideOnForm()->setColumns(6);
            yield Field::new('lastName')->hideOnForm()->setColumns(6);
            yield Field::new('prefName', 'Preferred Name')->onlyOnDetail()->setColumns(6);
            yield Field::new('pronouns')->onlyOnDetail()->setColumns(6);
            yield Field::new('videoLink', 'Video')->onlyOnDetail()->setColumns(6);
            
            yield ChoiceField::new('reviewer1Rating', 'Katie Rating')->onlyOnForms()->setColumns(6)->setChoices([
                'Strong Hire' => 4,
                'Hire' => 3,
                'No Hire' => 2,
                'Strong No Hire' => 1,
            ]);
            
            yield TextareaField::new('reviewer1Notes', 'Katie Notes')->onlyOnForms()->setColumns(6);
            yield ChoiceField::new('reviewer2Rating', 'Jacob Rating')->onlyOnForms()->setColumns(6)->setChoices([
                'Strong Hire' => 4,
                'Hire' => 3,
                'No Hire' => 2,
                'Strong No Hire' => 1,
            ]);
            
            yield TextareaField::new('reviewer2Notes', 'Jacob Notes')->onlyOnForms()->setColumns(6);



    }
}
