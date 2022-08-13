<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PropertyType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class AdminPropertyController extends AbstractController
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
   * @Route("/admin", name="admin.property.index")
   * @return Reponse;
   */
  public function index()
  {
    $properties = $this->repository->findAll();
    //Here, we use compact instead of an array to gain time
    return $this->render('admin/property/index.html.twig', compact('properties'));
  }

  /**
   * @Route("/admin/{id}", name="admin.property.edit")
   * @param Property $property
   * @return \Symfony\Component\HttpFoundation\Response;
   */
  public function edit(Property $property, Request $request)
  {
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->doctrine->getManager();
      $entityManager->flush();
      return $this->redirectToRoute('admin.property.index');
    }
    return $this->render('admin/property/edit.html.twig', [
      'property' => $property,
      'form' => $form->createView()
    ]);
  }
}
