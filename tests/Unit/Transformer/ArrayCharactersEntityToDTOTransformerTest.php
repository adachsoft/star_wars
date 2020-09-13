<?php

namespace Tests\Unit\App\Transformer;

use App\DTO\CharacterDTO;
use App\Entity\Characters;
use App\Transformer\ArrayCharactersEntityToDTOTransformer;
use App\Transformer\CharacterEntityToDTOTransformer;
use PHPUnit\Framework\TestCase;

class ArrayCharactersEntityToDTOTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $characters = new Characters();

        $collection = [
            $characters,
        ];

        $characterDTO = new CharacterDTO();

        $characterEntityToDTOTransformer = $this->createMock(CharacterEntityToDTOTransformer::class);
        $characterEntityToDTOTransformer->expects($this->once())->method('transform')->willReturn($characterDTO)->with($characters);

        $transformer = new ArrayCharactersEntityToDTOTransformer($characterEntityToDTOTransformer);
        $result = $transformer->transform($collection);
        $this->assertIsIterable($result);
        $result = iterator_to_array($result);
        $this->assertCount(1, $result);
    }
}
