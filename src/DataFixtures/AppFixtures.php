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

        for($i = 0; $i < 20; ++$i) {
            $characters = new Characters();
            $characters->setName("test {$i}");
    
            $manager->persist($characters);
        }
    }

    private function createEpisodes(ObjectManager $manager)
    {
        $episode = new Episodes();
        $episode->setName('NEWHOPE');

        $manager->persist($episode);
    }
}
