<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Transporteurs')
            ->setEntityLabelInPlural('Transporteurs');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel("Nom du transporteur"),
            TextareaField::new('description')->setLabel("Description du transporteur"),
            NumberField::new('price')->setLabel('Prix T.T.C')->setHelp('Prix T.T.C sans sigle'),
        ];
    }

}
