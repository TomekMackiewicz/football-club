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

        // Ignore null and empty values to allow other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $currentTraining || '' === $currentTraining) {
            return;
        }

        if (!$currentTraining instanceof Training) {
            throw new UnexpectedValueException($currentTraining, 'Training');
        }

        $overlapingTraining = $this->repository->findTrainingWithOverlapingDates($currentTraining);
        
        if (!$overlapingTraining instanceof Training) {
            return;
        }
        
        $datesOverlaps = $this->datesOverlaps($overlapingTraining);        
        $currentTrainers = $this->getTrainersIds($currentTraining);
        $trainers = $this->getTrainersIds($overlapingTraining);
            
        // Check overlaping dates for training location
        if ($this->trainingsHaveCommonLocation($currentTraining->getLocation(), $overlapingTraining->getLocation()) && $datesOverlaps) {                
            $this->context->buildViolation('validation.overlapingDateForLocation')->atPath('location')->addViolation();
        }

        // Check overlaping dates for trainer
        if ($this->trainingsHaveCommonTrainers($currentTrainers, $trainers) && $datesOverlaps) {                
            $this->context->buildViolation('validation.overlapingDateForTrainer')->atPath('trainers')->addViolation();
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
    
    private function datesOverlaps($overlapingTraining) 
    {   
        return $overlapingTraining === null ? false : true;
    }

    private function getTrainersIds($overlapingTraining)
    {
        $ids = [];
        foreach ($overlapingTraining->getTrainers() as $trainer) {
            $ids[] = $trainer->getId();
        }
        
        return $ids;
    }    
}
