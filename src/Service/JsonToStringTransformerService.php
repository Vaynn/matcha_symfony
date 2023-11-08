<?php
namespace App\Service;
use Symfony\Component\Form\DataTransformerInterface;

class JsonToStringTransformerService implements DataTransformerInterface
{

    public function transform(mixed $value)
    {
        return implode(',', $value);
    }

    public function reverseTransform(mixed $value)
    {
        return explode(',', $value);
    }
}