<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="browse_categories")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $query = $queryBuilder->select('c')
            ->from('App:Category', 'c')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('category/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/categories/new", name="add_categories")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {


                $createdBy = $this->getUser();
                $category->setCreatedBy($createdBy);
                $entityManager->persist($category);

                $entityManager->flush();
                return $this->redirectToRoute('browse_categories');

        }
        return $this->render('category/form.html.twig', [
            'pageTitle' => 'Create category',
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }
    /**
     * @Route("/categories/{id}/edit", name="edit_categories")
     *
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('browse_categories');

        }
        return $this->render('category/form.html.twig', [
            'pageTitle' => 'Category edit',
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }
    /**
     * @Route("/category/remove", name="remove_category")
     */
    public function remove(Request $request, EntityManagerInterface $entityManager)
    {
        $id = $request->get('categoryId');
        $category = $entityManager->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('browse_categories');
    }

}
