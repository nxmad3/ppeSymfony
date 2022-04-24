<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use App\Entity\{Residence, User, Rent};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\{Form\AbstractType,
    Form\Extension\Core\Type\DateType,
    Form\FormBuilderInterface,
    OptionsResolver\OptionsResolver,
    Form\Extension\Core\Type\EmailType,
    Form\Extension\Core\Type\TextType};



class AddLocationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('arrivalDate', DateType::class, [
                'widget' => 'single_text',
                'label'=>'Début de la location',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label'=>'Fin de la location',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
            'label' => false,
        ]);
    }
}