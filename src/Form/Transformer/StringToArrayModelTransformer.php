<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToArrayModelTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform($arrayToString)
    {
        if(empty($arrayToString)) {
            $arrayToString = [];
        }

        return implode(', ', $arrayToString);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($stringToArray)
    {
        return array_map('trim', explode(',', $stringToArray));
    }
}
