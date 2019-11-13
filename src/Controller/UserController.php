<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'userController',
        ]);
    }

}
