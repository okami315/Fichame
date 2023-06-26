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
            ->add('name')
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'years' => range(date('Y') - 100, date('Y')), // Rango de años desde 1900 hasta el año actual
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'years' => range(date('Y') - 100, date('Y')), // Rango de años desde 1900 hasta el año actual
            ])
            ->add('workers_number')
            ->add('drivers_number')
            ->add('distance')
            ->add('schedule', TextareaType::class, [ // Utilizar TextareaType en lugar de TextType
                'attr' => ['rows' => 8], // Ajustar el número de filas según tus necesidades
            ])
            ->add('estimated_hours')
            ->add('linkForm')
            ->add('link')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
