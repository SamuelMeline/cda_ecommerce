<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categories;
use App\Entity\References;
use App\Entity\Distributeurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
            ])
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false, // Indique à Symfony de ne pas mapper ce champ à une propriété de l'entité
                'required' => false, // Le champ n'est pas obligatoire
            ])
            ->add('photos', CollectionType::class, [
                'entry_type' => PhotosType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Prix du produit',
            ])
            ->add('slug', TextType::class, [
                'label' => 'URL du produit',
            ])
            ->add('Reference', ReferencesType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
            ])
            ->add('distributeur', EntityType::class, [
                'class' => Distributeurs::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
