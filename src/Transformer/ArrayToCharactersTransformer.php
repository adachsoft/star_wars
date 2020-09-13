<?php

declare(strict_types = 1);

namespace App\Transformer;

use App\Entity\Characters;
use App\Entity\Episodes;
use App\Repository\EpisodesRepository;
use App\Transformer\Exception\TransformerException;
use App\Transformer\Model\ArrayToEntityTransformerInterface;

class ArrayToCharactersTransformer implements ArrayToEntityTransformerInterface
{
    /**
     * @var EpisodesRepository
     */
    private $episodesRepository;

    public function __construct(EpisodesRepository $episodesRepository)
    {
        $this->episodesRepository = $episodesRepository;
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

        return $character;
    }

    private function getEpisodes(array $data, Characters $character): void
    {
        if (empty($data['episodes'])) {
            return;
        }

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

    private function getName(array $data): ?string
    {
        if (empty($data['name'])) {
            return null;
        }

        return trim($data['name']);
    }
}
