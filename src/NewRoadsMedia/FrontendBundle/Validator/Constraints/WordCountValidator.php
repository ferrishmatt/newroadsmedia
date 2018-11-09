<?php

namespace NewRoadsMedia\FrontendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WordCountValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!empty($constraint->limit)) {
            $wordCount = str_word_count($value);
            if ($wordCount > $constraint->limit) {
                $this->context->addViolation($constraint->message, array('%limit%' => number_format($constraint->limit, 0, '.', ',')));
            }
        }
    }
}