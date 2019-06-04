<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Training;

class ContainsOverlapingTrainingDateForLocationValidator extends ConstraintValidator
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
     * @param Training $training
     * @param Constraint $constraint
     * @return null
     * @throws UnexpectedTypeException
     * @throws UnexpectedValueException
     */
    public function validate($training, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsOverlapingTrainingDateForLocation) {
            throw new UnexpectedTypeException($constraint, ContainsOverlapingTrainingDateForLocation::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $training || '' === $training) {
            return;
        }

        if (!$training instanceof Training) {
            throw new UnexpectedValueException($training, 'Training');
        }

        $otherTrainings = $this->repository->findAllTrainingsExceptOne($training->getId());

        foreach ($otherTrainings as $otherTraining) {
            $datesOverlaps = $this->datesOverlap(
                $training->getStartDate()->getTimestamp(), 
                $training->getEndDate()->getTimestamp(), 
                $otherTraining->getStartDate()->getTimestamp(), 
                $otherTraining->getEndDate()->getTimestamp(),
            );            
            if ($otherTraining->getLocation() === $training->getLocation() && $datesOverlaps) {                
                $this->context->buildViolation($constraint->message)
                    ->atPath('location')
                    ->addViolation();
                return;
            }
        }
    }
    
    private function datesOverlap($start1, $end1, $start2, $end2) 
    {
        if ($start1 <= $end2 && $end1 >= $start2) {
             return true;
        }

        return false;
    }    
}
