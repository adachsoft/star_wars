<?php

namespace App\Transformer;

use App\DTO\CharacterDTO;
use App\Entity\Characters;
use App\Entity\Planet;

class CharacterEntityToDTOTransformer
{
    public function transform(Characters $characters): CharacterDTO
    {
        $characterDTO = new CharacterDTO();
        $characterDTO->name = $characters->getName();
        $planet = $characters->getPlanet();
        if ($planet instanceof Planet) {
            $characterDTO->planet = $characters->getPlanet()->getName();
        }

        foreach($characters->getFriends() as $friend) {
            $characterDTO->friends[] = $friend->getName();
        }

        foreach($characters->getEpisodes() as $episode) {
            $characterDTO->episodes[] = $episode->getName();
        }

        return $characterDTO;
    }
}
