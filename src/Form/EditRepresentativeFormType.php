<?php

namespace App\Form;

use App\Entity\{Residence, User};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\{Form\AbstractType,
    Form\FormBuilderInterface,
    OptionsResolver\OptionsResolver,
    Form\Extension\Core\Type\EmailType,
    Form\Extension\Core\Type\TextType
};


class EditRepresentativeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('lastname', TextType::class, ['label' => 'Prénom'])
            ->add('email', EmailType::class, ['label' => 'Email']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'label' => false,
        ]);
    }
}