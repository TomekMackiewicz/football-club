<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('startDate')
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm'
            ])
            //->add('endDate')
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm'
            ])
            ->add('location');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}