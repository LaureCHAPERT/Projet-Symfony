<?php

namespace App\Controller;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PropertyController extends AbstractController
{
  /**
   * @Route("/biens", name="property.index")
   * @return Response;
   */
  public function index(ManagerRegistry $doctrine): Response
  {
    $property = new Property();
    $property->setTitle('Mon premier bien')
      ->setPrice(200000)
      ->setRooms(4)
      ->setBedrooms(3)
      ->setDescription('petite description')
      ->setSurface(60)
      ->setFloor(4)
      ->setHeat(1)
      ->setCity('Montpellier')
      ->setAdress('rue de la démo')
      ->setPostalCode('34000');
    $entityManager = $doctrine->getManager();
    $entityManager->persist($property);
    $entityManager->flush();
    return $this->render('property/index.html.twig', [
      'current_menu' => 'properties'
    ]);
  }
}
