<?php

namespace App\Form;

use App\Entity\UserEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisponibilidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('disponibility', ChoiceType::class, [
                'choices' => [
                    'Pendiente de disponibilidad' => null,
                    'No disponible' => 0,
                    'Disponible' => 1,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserEvent::class,
        ]);
    }
}
