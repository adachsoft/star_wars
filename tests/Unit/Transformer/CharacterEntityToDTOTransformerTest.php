<?php

namespace Tests\App\Transformer;

use App\DTO\CharacterDTO;
use App\Entity\Characters;
use App\Entity\Episodes;
use App\Entity\Planet;
use App\Transformer\CharacterEntityToDTOTransformer;
use PHPUnit\Framework\TestCase;

class CharacterEntityToDTOTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $planet = new Planet();
        $planet->setName('Planet X');

        $episode1 = new Episodes();
        $episode1->setName('Episode 1');

        $friend1 = new Characters();
        $friend1->setName('Friend name 1');

        $friend2 = new Characters();
        $friend2->setName('Friend name 2');

        $characters = new Characters();
        $characters->setName('test name');
        $characters->addFriend($friend1);
        $characters->addFriend($friend2);
        $characters->addEpisode($episode1);
        $characters->setPlanet($planet);

        $transformer = new CharacterEntityToDTOTransformer();
        $result = $transformer->transform($characters);
        $this->assertInstanceOf(CharacterDTO::class, $result);
        $this->assertSame($characters->getName(), $result->name);
        $this->assertSame($characters->getPlanet()->getName(), $result->planet);
        $this->assertEquals(['Friend name 1', 'Friend name 2'], $result->friends);
        $this->assertEquals(['Episode 1'], $result->episodes);
    }

    public function testTransformWithoutPlanet(): void
    {
        $episode1 = new Episodes();
        $episode1->setName('Episode 1');

        $friend1 = new Characters();
        $friend1->setName('Friend name 1');

        $friend2 = new Characters();
        $friend2->setName('Friend name 2');

        $characters = new Characters();
        $characters->setName('test name');
        $characters->addFriend($friend1);
        $characters->addFriend($friend2);
        $characters->addEpisode($episode1);

        $transformer = new CharacterEntityToDTOTransformer();
        $result = $transformer->transform($characters);
        $this->assertInstanceOf(CharacterDTO::class, $result);
        $this->assertSame($characters->getName(), $result->name);
        $this->assertNull($result->planet);
        $this->assertEquals(['Friend name 1', 'Friend name 2'], $result->friends);
        $this->assertEquals(['Episode 1'], $result->episodes);
    }
}
