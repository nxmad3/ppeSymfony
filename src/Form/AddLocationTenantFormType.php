<?php

namespace App\Form;

use App\Entity\{Residence, User, Rent};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\{Form\AbstractType,
    Form\Extension\Core\Type\DateType,
    Form\FormBuilderInterface,
    OptionsResolver\OptionsResolver,
    Form\Extension\Core\Type\EmailType,
    Form\Extension\Core\Type\TextType};



class AddLocationTenantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('residence', EntityType::class, [
                'class' => Rent::class,
                'multiple' => true,
                'choice_label' => 'id',
            ])
            ->add('arrivalDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
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