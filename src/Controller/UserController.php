<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="view_user")
     */
    public function view()
    {
        $user = $this->getUser();
        return $this->render('user/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/edit", name="edit_user")
     */
    public function edit(Request $request)
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success', 'Your profile has been updated');
            return $this->redirectToRoute('view_user');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
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

    /**
     * @param $token
     * @Route("/email/{token}", name="confirm_email")
     * @return RedirectResponse
     */
    public function confirmEmail($token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['tempToken' => $token]);
        if ($user) {
            $user->setEnabled(true);
            $em->flush();
        }
        $this->addFlash('success', 'Your email has been confirmed');
        return $this->redirectToRoute('home');
    }

}
