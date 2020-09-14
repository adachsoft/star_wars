<?php

namespace Tests\Unit\App\Transformer;

use App\Entity\Characters;
use App\Entity\Episodes;
use App\Entity\Planet;
use App\Repository\CharactersRepository;
use App\Repository\EpisodesRepository;
use App\Repository\PlanetRepository;
use App\Transformer\ArrayToCharactersTransformer;
use App\Transformer\Exception\TransformerException;
use App\Transformer\Model\ArrayToEntityTransformerInterface;
use PHPUnit\Framework\TestCase;

class ArrayToCharactersTransformerTest extends TestCase
{
    public function testInstance(): void
    {
        $this->assertInstanceOf(
            ArrayToEntityTransformerInterface::class, 
            $this->createTransformer()
        );
    }

    public function testTransform(): void
    {
        $data = [
            'name' => 'test',
        ];

        $transformer = $this->createTransformer();
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data);

        $this->assertInstanceOf(Characters::class, $result);
        $this->assertSame($data['name'], $result->getName());
    }

    public function testTransformUpdate(): void
    {
        $character = new Characters();
        $character->setName('old name');

        $data = [
            'name' => 'new name',
        ];

        $transformer = $this->createTransformer();
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data, $character);

        $this->assertSame($character, $result);
        $this->assertSame($data['name'], $result->getName());
    }

    public function testTransformWithPlanet(): void
    {
        $planet = new Planet();
        $planet->setName('Planet-X');

        $episodesRepository = $this->createMock(EpisodesRepository::class);
        $planetRepository = $this->createMock(PlanetRepository::class);
        $charactersRepository = $this->createMock(CharactersRepository::class);
        $planetRepository->expects($this->once())->method('findOneBy')->willReturn($planet);

        $data = [
            'name' => 'test',
            'planet' => 'Planet-X'
        ];

        $transformer = new ArrayToCharactersTransformer($episodesRepository, $planetRepository, $charactersRepository);
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data);

        $this->assertInstanceOf(Characters::class, $result);
        $this->assertSame($data['name'], $result->getName());
        $this->assertInstanceOf(Planet::class, $result->getPlanet());
        $this->assertSame($data['planet'], $result->getPlanet()->getName());
    }

    public function testTransformWithEpisodes(): void
    {
        $episode1 = $this->createMock(Episodes::class);
        $episode2 = $this->createMock(Episodes::class);
        $episodesRepository = $this->createMock(EpisodesRepository::class);
        $planetRepository = $this->createMock(PlanetRepository::class);
        $charactersRepository = $this->createMock(CharactersRepository::class);

        $data = [
            'name' => 'test',
            'episodes' => [['name' => 'episodeName1'], ['name' => 'episodeName2']],
        ];

        $episodesRepository->expects($this->at(0))->method('findOneBy')->willReturn($episode1);
        $episodesRepository->expects($this->at(1))->method('findOneBy')->willReturn($episode2);

        $transformer = new ArrayToCharactersTransformer($episodesRepository, $planetRepository, $charactersRepository);
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data);

        $this->assertInstanceOf(Characters::class, $result);
        $this->assertSame($data['name'], $result->getName());

        $episodes = $result->getEpisodes();
        $this->assertCount(2, $episodes);
    }

    public function testTransformWithEpisodefs(): void
    {
        $friend1 = $this->createMock(Characters::class);
        $friend2 = $this->createMock(Characters::class);
        $episodesRepository = $this->createMock(EpisodesRepository::class);
        $planetRepository = $this->createMock(PlanetRepository::class);
        $charactersRepository = $this->createMock(CharactersRepository::class);

        $data = [
            'name' => 'test',
            'friends' => [['name' => 'friend 1'], ['name' => 'friend 2']],
        ];

        $charactersRepository->expects($this->at(0))->method('findOneBy')->willReturn($friend1);
        $charactersRepository->expects($this->at(1))->method('findOneBy')->willReturn($friend2);

        $transformer = new ArrayToCharactersTransformer($episodesRepository, $planetRepository, $charactersRepository);
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data);

        $this->assertInstanceOf(Characters::class, $result);
        $this->assertSame($data['name'], $result->getName());

        $friends = $result->getFriends();
        $this->assertCount(2, $friends);
    }

    public function testShouldThrowExceptionWhenThisIsNotCharacters(): void
    {
        $this->expectException(TransformerException::class);
        $thisisNotcharacter = new Episodes();

        $data = [
            'name' => 'test name',
        ];

        $transformer = $this->createTransformer();
        $transformer->transform($data, $thisisNotcharacter);
    }

    public function testShouldThrowExceptionWhenEpisodeNotFound(): void
    {
        $this->expectException(TransformerException::class);
        
        $episodesRepository = $this->createMock(EpisodesRepository::class);
        $planetRepository = $this->createMock(PlanetRepository::class);
        $charactersRepository = $this->createMock(CharactersRepository::class);

        $data = [
            'name' => 'test',
            'episodes' => [['name' => 'non-existent episode']],
        ];

        $episodesRepository->expects($this->at(0))->method('findOneBy')->willReturn(null);

        $transformer = new ArrayToCharactersTransformer($episodesRepository, $planetRepository, $charactersRepository);
        $transformer->transform($data);
    }

    /**
     * @dataProvider dataShouldThrowExceptionWhenTransformWrongData
     */
    public function testShouldThrowExceptionWhenTransformWrongData(array $data): void
    {
        $this->expectException(TransformerException::class);

        $transformer = $this->createTransformer();
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data);

        $this->assertInstanceOf(Characters::class, $result);
        $this->assertSame($data['name'], $result->getName());
    }

    public function dataShouldThrowExceptionWhenTransformWrongData(): array
    {
        return [
            [['name' => '']],
            [['name' => '            ']],
            [[]],
            [['name' => 'abc', 'episodes' => [['wrong_key_name' => 'zxcv']]]],
            [['name' => 'abc', 'episodes' => [['name' => '']]]],
            [['name' => 'abc', 'episodes' => [['name' => '   ']]]],
            [['name' => 'abc', 'planet' => '    ']],
            [['name' => 'abc', 'planet' => 'nonexistent planet']],
            [['name' => 'abc', 'friends' => [['name' => '   ']]]],
            [['name' => 'abc', 'friends' => [['name' => 'nonexistent friend']]]],
        ];
    }

    private function createTransformer(): ArrayToCharactersTransformer
    {
        $episodesRepository = $this->createMock(EpisodesRepository::class);
        $planetRepository = $this->createMock(PlanetRepository::class);
        $charactersRepository = $this->createMock(CharactersRepository::class);

        return new ArrayToCharactersTransformer($episodesRepository, $planetRepository, $charactersRepository);
    }
}
