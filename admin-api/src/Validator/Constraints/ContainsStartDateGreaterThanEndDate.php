<?php declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsStartDateGreaterThanEndDate extends Constraint
{
    public $message = 'validation.startDateGreaterThanEndDate';
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    } 
}