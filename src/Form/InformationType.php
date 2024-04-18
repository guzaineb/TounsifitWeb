<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Allergie;
use App\Entity\InformationEducatif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', null, [
            'constraints' => [new NotBlank()],
        ])
        ->add('symptome', null, [
            'constraints' => [new NotBlank()],
        ])
        ->add('causes', null, [
            'constraints' => [new NotBlank()],
        ])
        ->add('traitement', null, [
            'constraints' => [new NotBlank()],
        ])
        ->add('image', FileType::class, [
            'label' => 'Image (JPEG, PNG, GIF)',
            'required' => false, // Rendre le champ facultatif si nécessaire
            'mapped' => false, // Ne pas mapper ce champ à l'entité directement
            'constraints' => [
                new File([
                    'maxSize' => '1024k', // Limite de taille du fichier
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
                ])
            ],
        ])
 
        ->add('idAllergie', null, [
            'constraints' => [new NotBlank()],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InformationEducatif::class,
        ]);
    }
}
