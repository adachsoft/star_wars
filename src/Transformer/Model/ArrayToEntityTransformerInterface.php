<?php

namespace App\Transformer\Model;

interface ArrayToEntityTransformerInterface
{
    public function transform(array $data, ?object $entity = null): object;
}
