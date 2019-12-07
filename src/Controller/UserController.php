<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="view_user")
     */
    public function view()
    {
        return $this->render('user/view.html.twig', [
            'controller_name' => 'userController',
        ]);
    }

    /**
     * @Route("/edit-user", name="edit_user")
     */
    public function edit()
    {
        return $this->render('user/edit.html.twig', [
            'controller_name' => 'userController',
        ]);
    }
    /**
     * @Route("/users", name="browse_users")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $query = $queryBuilder->select('u')
                    ->from('App:User', 'u')
                    ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("/users/remove", name="remove_user")
     */
    public function remove(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('userId'));
        if ($request->get('save'))
        {
            $deletedManager = $this->getDoctrine()->getManager('deleted_users');
            $user->persistCreatedAds();
            $deletedManager->persist($user);
            $deletedManager->flush();
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('browse_users');
    }

}
