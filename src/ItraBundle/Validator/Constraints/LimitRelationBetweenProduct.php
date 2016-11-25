<?php
namespace ItraBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LimitRelationBetweenProduct extends Constraint
{
    public $message = 'Too many related products.';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}