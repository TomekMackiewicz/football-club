<?php declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsStartDateGreaterThanEndDateValidator extends ConstraintValidator
{   
    /**
     * @param object $object
     * @param Constraint $constraint
     * @return null
     * @throws UnexpectedTypeException
     * @throws UnexpectedValueException
     */
    public function validate($object, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsStartDateGreaterThanEndDate) {
            throw new UnexpectedTypeException($constraint, ContainsStartDateGreaterThanEndDate::class);
        }

        // Ignore null and empty values to allow other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $object || '' === $object) {
            return;
        }

        if (!is_object($object) && (!property_exists($object, 'startDate') || !property_exists($object, 'endDate'))) {
            throw new UnexpectedValueException($object, 'object');
        }

        if ($this->startDateGreaterThanEndDate($object->getStartDate(), $object->getEndDate())) {                
            $this->context->buildViolation('validation.startDateGreaterThanEndDate')->atPath('startDate')->addViolation();
        }
    }

    private function startDateGreaterThanEndDate($startDate, $endDate)
    {
        return $startDate > $endDate ? true : false;
    }
}
