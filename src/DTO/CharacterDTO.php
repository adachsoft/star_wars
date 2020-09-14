<?php

declare(strict_types = 1);

namespace App\DTO;

use App\DTO\Model\AbstractDTO;

class CharacterDTO extends AbstractDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string[]
     */
    public $episodes = [];

    /**
     * @var string
     */
    public $planet;

    /**
     * @var string[]
     */
    public $friends = [];
}
