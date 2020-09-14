<?php

declare(strict_types = 1);

namespace Tests\Unit\App\DTO;

use App\DTO\CharacterDTO;
use App\DTO\Model\AbstractDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CharacterDTOTest extends TestCase
{
    public function testInstance(): void
    {
        $characterDTO = new CharacterDTO();
        $this->assertInstanceOf(AbstractDTO::class, $characterDTO);
    }

    public function testSetValue(): void
    {
        $characterDTO = new CharacterDTO();
        $characterDTO->name = 'test';

        $this->assertSame('test', $characterDTO->name);
    }

    public function testSetUnavailableProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $characterDTO = new CharacterDTO();
        $characterDTO->zxcvbnm = 2160;
    }

    public function testGetUnavailableProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $characterDTO = new CharacterDTO();
        $abc = $characterDTO->zxcvbnm;
    }
}
