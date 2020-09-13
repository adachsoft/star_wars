<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Characters;
use App\Entity\Episodes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->createCharacters($manager);
        $this->createEpisodes($manager);

        $manager->flush();
    }

    private function createCharacters(ObjectManager $manager): void
    {
        $characters = new Characters();
        $characters->setName('test');

        $manager->persist($characters);

        $characters2 = new Characters();
        $characters2->setName('test2');

        $manager->persist($characters2);
    }

    private function createEpisodes(ObjectManager $manager)
    {
        $episode = new Episodes();
        $episode->setName('NEWHOPE');

        $manager->persist($episode);
    }
}
