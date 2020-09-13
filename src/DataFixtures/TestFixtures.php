<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Characters;
use App\Entity\Episodes;
use App\Entity\Planet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class TestFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['for_test'];
    }

    public function load(ObjectManager $manager)
    {
        $this->createCharacters($manager);
        $this->createEpisodes($manager);

        $manager->flush();
    }

    private function createCharacters(ObjectManager $manager): void
    {
        //Planets
        $planetX = new Planet();
        $planetX->setName('Planet-X');
        $manager->persist($planetX);

        $episode = new Episodes();
        $episode->setName('Episode1');
        $manager->persist($episode);

        $character1 = new Characters();
        $character1->setName('test');
        $character1->setPlanet($planetX);
        $character1->addEpisode($episode);

        $manager->persist($character1);

        for ($i = 0; $i < 20; ++$i) {
            $characters = new Characters();
            $characters->setName("test {$i}");
            if ($i < 3) {
                $characters->addfriend($character1);
            }
    
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
