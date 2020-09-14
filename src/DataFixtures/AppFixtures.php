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
class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['for_app'];
    }

    public function load(ObjectManager $manager)
    {
        //Planets
        $planetAlderaan = new Planet();
        $planetAlderaan->setName('Alderaan');
        $manager->persist($planetAlderaan);

        //Episodes
        $episodeNewhope = new Episodes();
        $episodeNewhope->setName('NEWHOPE');
        $manager->persist($episodeNewhope);

        $episodeEmpire = new Episodes();
        $episodeEmpire->setName('EMPIRE');
        $manager->persist($episodeEmpire);

        $episodeJedi = new Episodes();
        $episodeJedi->setName('JEDI');
        $manager->persist($episodeJedi);

        //Characters
        $lukeSkywalker = new Characters();
        $lukeSkywalker->setName('Luke Skywalker');
        $lukeSkywalker->addEpisode($episodeNewhope);
        $lukeSkywalker->addEpisode($episodeEmpire);
        $lukeSkywalker->addEpisode($episodeJedi);
        $manager->persist($lukeSkywalker);

        $darthVader = new Characters();
        $darthVader->setName('Darth Vader');
        $darthVader->addEpisode($episodeNewhope);
        $darthVader->addEpisode($episodeEmpire);
        $darthVader->addEpisode($episodeJedi);
        $manager->persist($darthVader);

        $hanSolo = new Characters();
        $hanSolo->setName('Han Solo');
        $hanSolo->addEpisode($episodeNewhope);
        $hanSolo->addEpisode($episodeEmpire);
        $hanSolo->addEpisode($episodeJedi);
        $manager->persist($hanSolo);

        $leiaOrgana = new Characters();
        $leiaOrgana->setName('Leia Organa');
        $leiaOrgana->addEpisode($episodeNewhope);
        $leiaOrgana->addEpisode($episodeEmpire);
        $leiaOrgana->addEpisode($episodeJedi);
        $leiaOrgana->setPlanet($planetAlderaan);
        $manager->persist($leiaOrgana);

        $wilhuffTarkin = new Characters();
        $wilhuffTarkin->setName('Wilhuff Tarkin');
        $wilhuffTarkin->addEpisode($episodeNewhope);
        $manager->persist($wilhuffTarkin);

        $c3PO = new Characters();
        $c3PO->setName('C-3PO');
        $c3PO->addEpisode($episodeNewhope);
        $c3PO->addEpisode($episodeEmpire);
        $c3PO->addEpisode($episodeJedi);
        $manager->persist($c3PO);

        $r2D2 = new Characters();
        $r2D2->setName('R2-D2');
        $r2D2->addEpisode($episodeNewhope);
        $r2D2->addEpisode($episodeEmpire);
        $r2D2->addEpisode($episodeJedi);
        $manager->persist($r2D2);

        //Friends
        $lukeSkywalker->addFriend($hanSolo);
        $lukeSkywalker->addFriend($leiaOrgana);
        $lukeSkywalker->addFriend($c3PO);
        $lukeSkywalker->addFriend($r2D2);

        $darthVader->addFriend($wilhuffTarkin);

        $hanSolo->addFriend($lukeSkywalker);
        $hanSolo->addFriend($leiaOrgana);
        $hanSolo->addFriend($r2D2);

        $leiaOrgana->addFriend($lukeSkywalker);
        $leiaOrgana->addFriend($hanSolo);
        $leiaOrgana->addFriend($c3PO);
        $leiaOrgana->addFriend($r2D2);

        $wilhuffTarkin->addFriend($darthVader);
        
        $c3PO->addFriend($lukeSkywalker);
        $c3PO->addFriend($hanSolo);
        $c3PO->addFriend($leiaOrgana);
        $c3PO->addFriend($r2D2);

        $r2D2->addFriend($lukeSkywalker);
        $r2D2->addFriend($hanSolo);
        $r2D2->addFriend($leiaOrgana);

        $manager->flush();
    }
}
