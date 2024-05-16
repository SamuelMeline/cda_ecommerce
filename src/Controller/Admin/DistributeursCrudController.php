<?php

namespace App\Controller\Admin;

use App\Entity\Distributeurs;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DistributeursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Distributeurs::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            AssociationField::new('produits')->autocomplete(),
        ];
    }

}
