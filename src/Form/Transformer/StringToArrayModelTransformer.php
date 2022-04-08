<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToArrayModelTransformer implements DataTransformerInterface
{

    public function transform($arrayToString): string
    {
        if(empty($arrayToString)) {
            $arrayToString = [];
        }

        return implode(', ', $arrayToString);
    }

    public function reverseTransform($stringToArray): array
    {
        return array_map('trim', explode(',', $stringToArray));
    }
}
