<?php

namespace App\Form;

use App\Entity\Event;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType ;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('start_date', DateType::class)
            ->add('end_date', DateType::class)   
            ->add('schedule')
            ->add('distance')
            ->add('drivers_number')
            ->add('linkInformation')
            ->add('linkForm')
            // ->add('type'), multiple select
            ->add('workers_number')
            ->add('type')
            ->add('drivers_number')
            ->add('distance')
            // ->add('horario estimado') 
            // ->add('horario real')
            // 
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
