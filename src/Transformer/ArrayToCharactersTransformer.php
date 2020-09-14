<?php

declare(strict_types = 1);

namespace App\Transformer;

use App\Entity\Characters;
use App\Entity\Episodes;
use App\Entity\Planet;
use App\Repository\CharactersRepository;
use App\Repository\EpisodesRepository;
use App\Repository\PlanetRepository;
use App\Transformer\Exception\TransformerException;
use App\Transformer\Model\ArrayToEntityTransformerInterface;

class ArrayToCharactersTransformer implements ArrayToEntityTransformerInterface
{
    /**
     * @var EpisodesRepository
     */
    private $episodesRepository;

    /**
     * @var PlanetRepository
     */
    private $planetRepository;

    /**
     * @var CharactersRepository
     */
    private $charactersRepository;

    public function __construct(
        EpisodesRepository $episodesRepository,
        PlanetRepository $planetRepository,
        CharactersRepository $charactersRepository
    ) {
        $this->episodesRepository = $episodesRepository;
        $this->planetRepository = $planetRepository;
        $this->charactersRepository = $charactersRepository;
    }

    public function transform(array $data, ?object $entity = null): object
    {
        $name = $this->getName($data);
        if (empty($name)) {
            throw new TransformerException('The name cannot be empty.');
        }

        if (is_object($entity) && !$entity instanceof Characters) {
            throw new TransformerException('Only the Characters object is allowed.');
        }

        $character = is_object($entity) ? $entity : new Characters();
        $character->setName($name);
        $this->getEpisodes($data, $character);
        $this->getPlanet($data, $character);
        $this->getFriends($data, $character);

        return $character;
    }

    private function getPlanet(array $data, Characters $character): void
    {
        if (!isset($data['planet'])) {
            return;
        }

        $planetName = $this->getName($data, 'planet');
        if (empty($planetName)) {
            throw new TransformerException('The planet cannot be empty.');
        }

        $planet = $this->planetRepository->findOneBy(['name' => $planetName]);
        if (!$planet instanceof Planet) {
            throw new TransformerException("No such planet was found: {$planetName}");
        }

        $character->setPlanet($planet);
    }

    private function getEpisodes(array $data, Characters $character): void
    {
        if (empty($data['episodes'])) {
            return;
        }

        $character->removeEpisodes();
        foreach($data['episodes'] as $item){
            $episodeName = $this->getName($item);
            if (empty($episodeName)) {
                throw new TransformerException('The episode name cannot be empty');
            }

            $episode = $this->episodesRepository->findOneBy(['name' => $episodeName]);
            if (!$episode instanceof Episodes) {
                throw new TransformerException("No such episode was found: {$episodeName}");
            }
            $character->addEpisode($episode);
        }
    }

    private function getFriends(array $data, Characters $character): void
    {
        if (empty($data['friends'])) {
            return;
        }

        $character->removeFriends();
        foreach($data['friends'] as $item){
            $friendName = $this->getName($item);
            if (empty($friendName)) {
                throw new TransformerException('The friend name cannot be empty');
            }

            $friend = $this->charactersRepository->findOneBy(['name' => $friendName]);
            if (!$friend instanceof Characters) {
                throw new TransformerException("No such friend was found: {$friendName}");
            }
            $character->addFriend($friend);
        }
    }

    private function getName(array $data, string $key = 'name'): ?string
    {
        if (empty($data[$key])) {
            return null;
        }

        return trim($data[$key]);
    }
}
