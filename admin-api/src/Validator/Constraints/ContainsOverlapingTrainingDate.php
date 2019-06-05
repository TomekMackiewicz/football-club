<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsOverlapingTrainingDate extends Constraint
{
    public $message = 'validation.overlapingDate';
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    } 
}