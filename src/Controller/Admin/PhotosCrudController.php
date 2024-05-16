<?php 

namespace App\Controller\Admin;

use App\Entity\Photos;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PhotosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Photos::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('name', 'Photos')
            ->setUploadDir('public/uploads/images')
                ->setBasePath('uploads/images')
        ];
    }
}