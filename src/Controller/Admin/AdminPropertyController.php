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
   * @Route("/admin/property/create", name="admin.property.new")
   * @return Reponse;
   */
  public function new(Request $request)
  {
    $property = new Property();
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->doctrine->getManager();
      $entityManager->persist($property);
      $entityManager->flush();
      $this->addFlash('success', 'Bien ajouté avec succès');
      return $this->redirectToRoute('admin.property.index');
    }
    return $this->render('admin/property/new.html.twig', [
      'property' => $property,
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
   * @param Property $property
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response;
   */
  public function edit(Property $property, Request $request)
  {
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->doctrine->getManager();
      $entityManager->flush();
      $this->addFlash('success', 'Bien modifié avec succès');
      return $this->redirectToRoute('admin.property.index');
    }
    return $this->render('admin/property/edit.html.twig', [
      'property' => $property,
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
   * @param Property $property
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response;
   */
  public function delete(Property $property, Request $request)
  {
    if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
      $entityManager = $this->doctrine->getManager();
      $entityManager->remove($property);
      $entityManager->flush();
      $this->addFlash('success', 'Bien supprimé avec succès');
    }
    return $this->redirectToRoute('admin.property.index');
  }
}
