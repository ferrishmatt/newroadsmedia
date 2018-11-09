<?php

namespace NewRoadsMedia\FrontendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WordCount extends Constraint
{
    public $message = 'There is a %limit% word limit on this value.';

    public $limit = 0;
}