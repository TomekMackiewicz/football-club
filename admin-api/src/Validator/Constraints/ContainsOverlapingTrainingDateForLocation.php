<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsOverlapingTrainingDateForLocation extends Constraint
{
    public $message = 'validation.overlapingDateForLocation';
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    } 
}