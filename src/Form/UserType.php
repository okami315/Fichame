<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => 'Nombre de usuario',
            ])
            ->add('fullName', null, [
                'label' => 'Nombre completo',
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Admin' => 'ROLE_ADMIN',
                    'Usuario' => 'ROLE_USER',
                    'Almacen' => 'ROLE_ALMACEN',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Roles',
            ])
            ->add('email', null, [
                'label' => 'Correo electrónico',
            ])
            ->add('address', null, [
                'label' => 'Dirección',
            ])
            ->add('phoneNumber', null, [
                'label' => 'Número de teléfono',
            ])
            ->add('dni', null, [
                'label' => 'DNI',
            ])
            ->add('naf', null, [
                'label' => 'NAF',
            ])
            ->add('driver', null, [
                'label' => 'Conductor',
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Activo' => 1,
                    'Inactivo' => 2,
                    'Borrado' => 0,
                ],
                'label' => 'Estado',
            ])
            ->add('agreement', null, [
                'label' => 'Contrato',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
