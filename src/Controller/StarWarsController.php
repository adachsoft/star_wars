<?php

declare(strict_types = 1);

namespace App\Controller;

use App\DTO\CharacterDTO;
use App\Entity\Characters;
use App\Pagination\Exception\PaginatorException;
use App\Pagination\Factory\PaginatorFactory;
use App\Repository\CharactersRepository;
use App\Response\Factory\Model\ResponseFactoryInterface;
use App\Transformer\ArrayCharactersEntityToDTOTransformer;
use App\Transformer\Model\ArrayToEntityTransformerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class StarWarsController extends AbstractController
{
    /**
     * @var CharactersRepository
     */
    private $charactersRepository;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ArrayToEntityTransformerInterface
     */
    private $arrayToEntityTransformer;

    /**
     * @var PaginatorFactory
     */
    private $paginatorFactory;

    /**
     * @var ArrayCharactersEntityToDTOTransformer
     */
    private $arrayCharactersEntityToDTOTransformer;

    public function __construct(
        CharactersRepository $charactersRepository,
        ResponseFactoryInterface $responseFactory,
        ArrayToEntityTransformerInterface $arrayToEntityTransformer,
        PaginatorFactory $paginatorFactory,
        ArrayCharactersEntityToDTOTransformer $arrayCharactersEntityToDTOTransformer
    ) {
        $this->charactersRepository = $charactersRepository;
        $this->responseFactory = $responseFactory;
        $this->arrayToEntityTransformer = $arrayToEntityTransformer;
        $this->paginatorFactory = $paginatorFactory;
        $this->arrayCharactersEntityToDTOTransformer = $arrayCharactersEntityToDTOTransformer;
    }

    /**
     * @Route("/{page}", name="main_index")
     */
    public function index(int $page = 1)
    {
        $paginator = $this->paginatorFactory->create(Characters::class);
        try{
            $paginator->setNumberOfItemsPerPage($this->getParameter('pagination.number_of_items_per_page'));
            $paginator->setCurrentPage($page);
        }catch(PaginatorException $e){
            return $this->responseFactory->create(
                ['status' => $e->getMessage()], 
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        return $this->responseFactory->create(
            [
                'characters' => $this->arrayCharactersEntityToDTOTransformer->transform($paginator->getData()),
                'pagination' => [
                    'total' => $paginator->getTotalItems(),
                    'current_page' => $paginator->getCurrentPage(),
                    'number_of_pages' => $paginator->getNumberOfPages(),
                ],
            ],
            Response::HTTP_OK
        );
    }
}
