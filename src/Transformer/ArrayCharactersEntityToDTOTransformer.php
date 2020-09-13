<?php

namespace App\Transformer;

class ArrayCharactersEntityToDTOTransformer
{
    /**
     * @var CharacterEntityToDTOTransformer
     */
    private $characterEntityToDTOTransformer;

    public function __construct(CharacterEntityToDTOTransformer $characterEntityToDTOTransformer)
    {
        $this->characterEntityToDTOTransformer = $characterEntityToDTOTransformer;
    }

    public function transform(iterable $characters): iterable
    {
        foreach($characters  as $character) {
            yield $this->characterEntityToDTOTransformer->transform($character);
        }
    }
}
