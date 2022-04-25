<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address',TextType::class,['required' => true,'label'=>'Adresse'])
            ->add('addressComplement',TextType::class,['required' => false,'label'=>'Complément d\'adresse'])
            ->add('postalCode',TextType::class,['required' => true,'label'=>'Code postal'])
            ->add('city',TextType::class,['required' => true,'label'=>'Ville'])
            ->add('phone',TextType::class,['required' => true,'label'=>'Téléphone','attr' => ['maxlength' => 10]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'label' => false,
            'required' => false,
        ]);
    }
}