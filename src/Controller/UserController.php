<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'userController',
        ]);
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
