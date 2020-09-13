<?php

namespace Tests\Unit\App\Transformer;

use App\Entity\Characters;
use App\Entity\Episodes;
use App\Repository\EpisodesRepository;
use App\Transformer\ArrayToCharactersTransformer;
use App\Transformer\Exception\TransformerException;
use App\Transformer\Model\ArrayToEntityTransformerInterface;
use PHPUnit\Framework\TestCase;

class ArrayToCharactersTransformerTest extends TestCase
{
    public function testInstance(): void
    {
        $episodesRepository = $this->createMock(EpisodesRepository::class);

        $transformer = new ArrayToCharactersTransformer($episodesRepository);
        $this->assertInstanceOf(ArrayToEntityTransformerInterface::class, $transformer);
    }

    public function testTransform(): void
    {
        $episodesRepository = $this->createMock(EpisodesRepository::class);

        $data = [
            'name' => 'test',
        ];

        $transformer = new ArrayToCharactersTransformer($episodesRepository);
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
        $episodesRepository = $this->createMock(EpisodesRepository::class);

        $data = [
            'name' => 'new name',
        ];

        $transformer = new ArrayToCharactersTransformer($episodesRepository);
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data, $character);

        $this->assertSame($character, $result);
        $this->assertSame($data['name'], $result->getName());
    }

    public function testTransformWithEpisodes(): void
    {
        $episode1 = $this->createMock(Episodes::class);
        $episode2 = $this->createMock(Episodes::class);
        $episodesRepository = $this->createMock(EpisodesRepository::class);

        $data = [
            'name' => 'test',
            'episodes' => [['name' => 'episodeName1'], ['name' => 'episodeName2']],
        ];

        $episodesRepository->expects($this->at(0))->method('findOneBy')->willReturn($episode1);
        $episodesRepository->expects($this->at(1))->method('findOneBy')->willReturn($episode2);

        $transformer = new ArrayToCharactersTransformer($episodesRepository);
        /**
         * @var Characters $result
         */
        $result = $transformer->transform($data);

        $this->assertInstanceOf(Characters::class, $result);
        $this->assertSame($data['name'], $result->getName());

        $episodes = $result->getEpisodes();
        $this->assertCount(2, $episodes);
    }

    public function testShouldThrowExceptionWhenThisIsNotCharacters(): void
    {
        $this->expectException(TransformerException::class);
        $thisisNotcharacter = new Episodes();
        
        $episodesRepository = $this->createMock(EpisodesRepository::class);

        $data = [
            'name' => 'test name',
        ];

        $transformer = new ArrayToCharactersTransformer($episodesRepository);
        $transformer->transform($data, $thisisNotcharacter);
    }

    public function testShouldThrowExceptionWhenEpisodeNotFound(): void
    {
        $this->expectException(TransformerException::class);
        
        $episodesRepository = $this->createMock(EpisodesRepository::class);

        $data = [
            'name' => 'test',
            'episodes' => [['name' => 'non-existent episode']],
        ];

        $episodesRepository->expects($this->at(0))->method('findOneBy')->willReturn(null);

        $transformer = new ArrayToCharactersTransformer($episodesRepository);
        $transformer->transform($data);
    }

    /**
     * @dataProvider dataShouldThrowExceptionWhenTransformWrongData
     */
    public function testShouldThrowExceptionWhenTransformWrongData(array $data): void
    {
        $this->expectException(TransformerException::class);

        $episodesRepository = $this->createMock(EpisodesRepository::class);
        $transformer = new ArrayToCharactersTransformer($episodesRepository);
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
        ];
    }
}
