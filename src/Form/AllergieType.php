<?php

namespace App\Form;

use App\Entity\Allergie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllergieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, [
                'attr' => [
                    'placeholder' => 'Nom_Allergie',
                ]])
            ->add('description',null, [
                'attr' => [
                    'placeholder' => 'Description allergie',
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Allergie::class,
        ]);
    }
}
