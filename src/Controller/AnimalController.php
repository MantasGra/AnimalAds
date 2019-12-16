<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animals", name="browse_animals")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $query = $queryBuilder->select('u')
            ->from('App:Animal', 'u')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('animal/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/animals/new", name="add_animal")
     */
    public function add(Request $request)
    {
        $animal = new Animal();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $animal->setCreatedBy($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('browse_animals');
        }
        return $this->render('animal/add.html.twig', [
            'animalForm' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/animals/{id}/remove", name="remove_animal")
     */
    public function remove($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ad = $this->getDoctrine()->getRepository(Animal::class)->find($id);

        $entityManager->remove($ad);
        $entityManager->flush();
        return $this->redirectToRoute('browse_animals');
    }

    /**
     * @Route(path="/animals/{id}/edit", name="edit_animal")
     */
    public function edit(Animal $animal, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
            $animal = $form->getData();
            $entityManager->persist($animal);
            $entityManager->flush();
         
            return $this->redirectToRoute('browse_animals');
        }
        return $this->render('animal/add.html.twig', [
            'animalForm' => $form->createView()
        ]);
    }
    
}
