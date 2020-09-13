<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Characters;
use App\Pagination\Exception\PaginatorException;
use App\Pagination\Factory\PaginatorFactory;
use App\Repository\CharactersRepository;
use App\Response\Factory\Model\ResponseFactoryInterface;
use App\Transformer\Model\ArrayToEntityTransformerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CharactersController extends AbstractController
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

    public function __construct(
        CharactersRepository $charactersRepository,
        ResponseFactoryInterface $responseFactory,
        ArrayToEntityTransformerInterface $arrayToEntityTransformer,
        PaginatorFactory $paginatorFactory
    ) {
        $this->charactersRepository = $charactersRepository;
        $this->responseFactory = $responseFactory;
        $this->arrayToEntityTransformer = $arrayToEntityTransformer;
        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * @Route("/characters/{page}", name="main")
     */
    public function index(int $page = 1): Response
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
                'characters' => $paginator->getData(),
                'pagination' => [
                    'total' => $paginator->getTotalItems(),
                    'current_page' => $paginator->getCurrentPage(),
                    'number_of_pages' => $paginator->getNumberOfPages(),
                ],
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/characters/one/{id}", name="app_character_one")
     */
    public function one(int $id): Response
    {
        $character = $this->charactersRepository->find($id);
        if (!$character instanceof Characters) {
            return $this->getRecordNotFoundResponse();
        }

        return $this->responseFactory->create($character, Response::HTTP_OK);
    }

    /**
     * @Route("/characters/add/", name="add_character", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = $request->get('data');
        $character = $this->arrayToEntityTransformer->transform($data);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($character);
        $entityManager->flush();

        return $this->responseFactory->create($character, Response::HTTP_CREATED);
    }

    /**
     * @Route("/characters/update/{id}", name="update_character", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $character = $this->charactersRepository->find($id);
        if (!$character instanceof Characters) {
            return $this->getRecordNotFoundResponse();
        }

        $data = $request->get('data');
        $character = $this->arrayToEntityTransformer->transform($data, $character);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($character);
        $entityManager->flush();

        return $this->responseFactory->create($character, Response::HTTP_OK);
    }

    /**
     * @Route("/characters/delete/{id}", name="delete_character", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $character = $this->charactersRepository->find($id);
        if (!$character instanceof Characters) {
            return $this->getRecordNotFoundResponse();
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($character);
        $entityManager->flush();

        //return new JsonResponse(['status' => 'Character deleted'], Response::HTTP_NO_CONTENT);
        return $this->responseFactory->create(['status' => 'Character deleted'], Response::HTTP_OK);
    }

    private function getRecordNotFoundResponse(): JsonResponse
    {
        return $this->responseFactory->create(
            ['status' => 'Record not found'], 
            Response::HTTP_NOT_FOUND
        );
    }
}
