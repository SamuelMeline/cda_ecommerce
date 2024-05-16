<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du produit'),
            TextEditorField::new('description'),
            CollectionField::new('photos', 'Photos')
                ->useEntryCrudForm(PhotosCrudController::class)
                ->setFormTypeOptions([
                    'required' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
                ->onlyOnForms(),
            ImageField::new('photos[0]', 'Photos')
                ->setBasePath('uploads/images')
                ->setUploadDir('public/uploads/images')
                ->onlyOnIndex(),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('slug'),
            AssociationField::new('categorie', 'Catégorie')->autocomplete(),
            AssociationField::new('distributeur', 'Distributeur')->autocomplete()->onlyOnForms(),
            ArrayField::new('distributeur', 'Distributeur')->onlyOnIndex(),
            AssociationField::new('user', 'Propriétaire')->autocomplete(),
            AssociationField::new('reference', 'Référence')
        ];
    }
}
