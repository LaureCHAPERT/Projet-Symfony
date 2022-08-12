<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
  /**
   * @var PropertyRepository
   */
  private $repository;

  public function __construct(PropertyRepository $repository, ManagerRegistry $doctrine)
  {
    $this->repository = $repository;
    $this->doctrine = $doctrine;
  }

  /**
   * @Route("/biens", name="property.index")
   * @return Response;
   */

  public function index(): Response
  {
    // Data recovery (Repository)
    $property = $this->repository->findAllVisible();
    $property[1]->setSold(true);
    $entityManager = $this->doctrine->getManager();
    $entityManager->flush();
    dump($property);

    return $this->render('property/index.html.twig', [
      'current_menu' => 'properties'
    ]);
  }
}
