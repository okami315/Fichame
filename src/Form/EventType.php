<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nombre',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Tipo',
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'years' => range(date('Y') - 100, date('Y')),
                'label' => 'Fecha de inicio',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'years' => range(date('Y') - 100, date('Y')),
                'label' => 'Fecha de fin',
            ])
            ->add('workers_number', null, [
                'label' => 'Número de trabajadores',
            ])
            ->add('drivers_number', null, [
                'label' => 'Número de conductores',
            ])
            ->add('distance', null, [
                'label' => 'Distancia',
            ])
            ->add('schedule', TextareaType::class, [
                'attr' => ['rows' => 8],
                'label' => 'Horario',
                'required' => false,
            ])
            ->add('estimated_hours', null, [
                'label' => 'Horas estimadas',
            ])
            ->add('linkForm', null, [
                'label' => 'Enalce formulario',
            ])
            ->add('link', null, [
                'label' => 'Enlace formulario opcional',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
