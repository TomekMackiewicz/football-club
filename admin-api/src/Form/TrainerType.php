<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Trainer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TrainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('status', ChoiceType::class, array(
                'choices'  => [
                    'active' => true,
                    'inactive' => false,
                ],
                'multiple' => false,
                'expanded' => false,
                'invalid_message' => 'validation.missing'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trainer::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
