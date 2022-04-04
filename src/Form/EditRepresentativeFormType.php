<?php

namespace App\Form;

use App\Entity\{Residence, User};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\{Form\AbstractType,
    Form\FormBuilderInterface,
    OptionsResolver\OptionsResolver,
    Form\Extension\Core\Type\EmailType,
    Form\Extension\Core\Type\TextType};


class EditRepresentativeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('email', EmailType::class)
            ->add('representativeResidences', EntityType::class, [
                'class' => Residence::class,
                'multiple' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'label' => false,
        ]);
    }
}