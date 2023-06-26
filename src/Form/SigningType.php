<?php

namespace App\Form;

use App\Entity\Signing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SigningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkin', null, [
                'widget' => 'single_text',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('checkout', null, [
                'widget' => 'single_text',
                'years' => range(date('Y') - 100, date('Y')),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signing::class,
        ]);
    }
}
