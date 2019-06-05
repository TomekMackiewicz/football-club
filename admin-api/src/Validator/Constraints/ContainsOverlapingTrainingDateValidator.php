<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Training;

class ContainsOverlapingTrainingDateValidator extends ConstraintValidator
{   
    private $registry;
    private $repository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->repository = $this->registry->getManager()->getRepository(Training::class);
    }    
    
    /**
     * 
     * @param Training $currentTraining
     * @param Constraint $constraint
     * @return null
     * @throws UnexpectedTypeException
     * @throws UnexpectedValueException
     */
    public function validate($currentTraining, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsOverlapingTrainingDate) {
            throw new UnexpectedTypeException($constraint, ContainsOverlapingTrainingDate::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $currentTraining || '' === $currentTraining) {
            return;
        }

        if (!$currentTraining instanceof Training) {
            throw new UnexpectedValueException($currentTraining, 'Training');
        }
        
        $currentTrainers = $this->getTrainersIds($currentTraining);
        $trainings = $this->repository->findAllTrainingsExceptOne($currentTraining->getId());

        foreach ($trainings as $training) {
            $datesOverlaps = $this->datesOverlap(
                $currentTraining->getStartDate()->getTimestamp(), 
                $currentTraining->getEndDate()->getTimestamp(), 
                $training->getStartDate()->getTimestamp(), 
                $training->getEndDate()->getTimestamp(),
            );
            $trainers = $this->getTrainersIds($training);
            
            // Check overlaping dates for given location
            if ($this->trainingsHaveCommonLocation($currentTraining->getLocation(), $training->getLocation()) && $datesOverlaps) {                
                $this->context->buildViolation('validation.overlapingDateForLocation')
                    ->atPath('location')
                    ->addViolation();
            }

            // Check overlaping dates for trainer
            if ($this->trainingsHaveCommonTrainers($currentTrainers, $trainers) && $datesOverlaps) {                
                $this->context->buildViolation('validation.overlapingDateForTrainer')
                    ->atPath('trainers')
                    ->addViolation();
            }
        }
    }

    private function trainingsHaveCommonLocation($currentLocation, $location)
    {
        return $currentLocation === $location ? true : false;
    }    
    
    private function trainingsHaveCommonTrainers($currentTrainers, $trainers)
    {
        return !empty(array_intersect($currentTrainers, $trainers)) ? true : false;
    }
    
    private function datesOverlap($start1, $end1, $start2, $end2) 
    {   
        return ($start1 <= $end2 && $end1 >= $start2) ? true : false;
    }

    private function getTrainersIds($training)
    {
        $ids = [];
        foreach ($training->getTrainers() as $trainer) {
            $ids[] = $trainer->getId();
        }
        
        return $ids;
    }    
}
