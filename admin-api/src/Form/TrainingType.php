<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Training;
use App\Entity\Trainer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DataTransformer\IdToObjectTransformer;
use Doctrine\ORM\EntityManagerInterface;

class TrainingType extends AbstractType
{
    private $transformer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->transformer = new IdToObjectTransformer($em, Trainer::class);
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm'
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm'
            ])
            ->add('location')
            ->add('trainers', ChoiceType::class, array(
                'choices' => $options['trainers'],
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'validation.missing'
            ))
            ->add('save', SubmitType::class);
        
        $builder->get('trainers')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'trainers' => []
        ]);
    }
}