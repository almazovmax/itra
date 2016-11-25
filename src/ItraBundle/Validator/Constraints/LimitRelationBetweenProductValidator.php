<?php
namespace ItraBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LimitRelationBetweenProductValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (count($value) > 3) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();;
        }

    }
}