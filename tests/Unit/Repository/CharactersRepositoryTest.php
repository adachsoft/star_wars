<?php

declare(strict_types = 1);

namespace Tests\Unit\App\Repository;

use App\Entity\Characters;
use App\Repository\CharactersRepository;
use App\Repository\Model\CharactersRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use \Doctrine\ORM\EntityManager;

class CharactersRepositoryTest extends KernelTestCase
{
     /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testInstance(): void
    {
        $charactersRepository = $this->getCharactersRepository();
        $this->assertInstanceOf(CharactersRepositoryInterface::class, $charactersRepository);
    }

    public function testSearchByName(): void
    {
        $charactersRepository = $this->getCharactersRepository();
        $character = $charactersRepository->findOneBy(['name' => 'test']);

        $this->assertSame('test', $character->getName());
    }

    public function testCountAll(): void
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT count(*) FROM characters';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $numberOfCharacters = (int) $stmt->fetchColumn(0);

        $charactersRepository = $this->getCharactersRepository();
        
        $this->assertSame($numberOfCharacters, $charactersRepository->countAll());
    }

    /**
     * @dataProvider dataFindWithOffsetAndLimit
     */
    public function testFindWithOffsetAndLimit(int $expectedCount, int $offset, int $limit, int $startIndex): void
    {
        $charactersRepository = $this->getCharactersRepository();
        $result = $charactersRepository->findWithOffsetAndLimit($offset, $limit);

        $this->assertCount($expectedCount, $result);
        foreach($result as $key => $character){
            $i = $startIndex + $key;
            $this->assertSame("test {$i}", $character->getName());
        }
    }

    public function dataFindWithOffsetAndLimit(): array
    {
        return [
            ['expectedCount' => 3, 'offset' => 1, 'limit' => 3, 'startIndex' => 0],
            ['expectedCount' => 4, 'offset' => 3, 'limit' => 4, 'startIndex' => 2],
            ['expectedCount' => 2, 'offset' => 19, 'limit' => 5, 'startIndex' => 18],
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    private function getCharactersRepository(): CharactersRepository
    {
        /**
         * @var CharactersRepository $charactersRepository
         */
        return $this->entityManager->getRepository(Characters::class);
    }
}
