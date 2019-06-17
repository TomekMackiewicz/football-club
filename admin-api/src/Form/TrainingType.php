<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Training;
use App\Entity\Trainer;
use App\Entity\Team;
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
    private $trainerTransformer;
    private $teamTransformer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->trainerTransformer = new IdToObjectTransformer($em, Trainer::class);
        $this->teamTransformer = new IdToObjectTransformer($em, Team::class);
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm'
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm'
            ])
            ->add('location')
            ->add('trainers', ChoiceType::class, array(
                'choices' => $options['trainers'],
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'validation.missing'
            ))
            ->add('teams', ChoiceType::class, array(
                'choices' => $options['teams'],
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'validation.missing'
            ))
            ->add('save', SubmitType::class);
        
        $builder->get('trainers')->addModelTransformer($this->trainerTransformer);
        $builder->get('teams')->addModelTransformer($this->teamTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'trainers' => [],
            'teams' => []
        ]);
    }
}