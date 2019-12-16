<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;

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
    public function add(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {


                $createdBy = $this->getUser();
                $category->setCreatedBy($createdBy);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);

                $entityManager->flush();
                return $this->redirectToRoute('browse_categories');

        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }
    /**
     * @Route("/categories/edit/{id}", name="edit_categories")
     *
     */
    public function edit(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
            'id' => $id
        ]);
        $form = $this->createForm(CategoryType::class, $category, [
            'action' => $this->generateUrl('edit_categories', [ 'id' => $id ])
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('browse_categories');

        }
        return $this->render('category/edit.html.twig', [
            'pageTitle' => 'Category edit',
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }
    /**
     * @Route("/category/{id}/remove", name="remove_category")
     */
    public function remove(Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($request->get('categoryId'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('browse_categories');
    }
    /**
     * @Route("/profile/{id}", name="view_category")
     */
    public function view(Request $request,$id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
            'id' => $id
        ]);

        return $this->render('category/view.html.twig', [
            'category' => $category
        ]);
    }

}
