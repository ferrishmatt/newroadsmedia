<?php

namespace NewRoadsMedia\FrontendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToBooleanTransformer implements DataTransformerInterface
{
    /** @var string */
    protected $trueValue;

    /** @var string */
    protected $falseValue;

    /**
     * @param string $trueValue
     * @param string $falseValue
     */
    public function __construct($trueValue, $falseValue)
    {
        $this->trueValue = $trueValue;
        $this->falseValue = $falseValue;
    }

    public function transform($string)
    {
        return $string == $this->trueValue;
    }

    public function reverseTransform($boolean)
    {
        return $boolean ? $this->trueValue : $this->falseValue;
    }
}