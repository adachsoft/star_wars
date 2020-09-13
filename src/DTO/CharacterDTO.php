<?php

declare(strict_types = 1);

namespace App\DTO;

use App\DTO\Model\AbstractDTO;

class CharacterDTO //extends AbstractDTO
{
    public $name;
    public $episodes = [];
    public $planet;
    public $friends = [];
}
