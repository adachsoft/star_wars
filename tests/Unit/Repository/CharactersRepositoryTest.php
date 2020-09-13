<?php

declare(strict_types = 1);

namespace Tests\Unit\App\Repository;

use App\Entity\Characters;
use App\Repository\CharactersRepository;
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

    public function testSearchByName(): void
    {
        $character = $this->entityManager
            ->getRepository(Characters::class)
            ->findOneBy(['name' => 'test'])
        ;

        $this->assertSame('test', $character->getName());
    }

    public function testCount(): void
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT count(*) FROM characters';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $numberOfCharacters = (int) $stmt->fetchColumn(0);

        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $this->entityManager->getRepository(Characters::class);
        
        $this->assertSame($numberOfCharacters, $charactersRepository->countAll());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
