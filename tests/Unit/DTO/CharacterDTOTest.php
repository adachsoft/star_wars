<?php

declare(strict_types = 1);

namespace Tests\Unit\App\DTO;

use App\DTO\CharacterDTO;
use App\DTO\Model\AbstractDTO;
use PHPUnit\Framework\TestCase;

class CharacterDTOTest extends TestCase
{
    /*public function testInstance(): void
    {
        $characterDTO = new CharacterDTO();
        $this->assertInstanceOf(AbstractDTO::class, $characterDTO);
    }*/

    public function testSetValue(): void
    {
        $characterDTO = new CharacterDTO();
        $characterDTO->value1 = 'test';

        $this->assertSame('test', $characterDTO->value1);
        //var_dump($characterDTO);
    }
}
