<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route(path="/login", name="login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route(path="/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route(path="/register", name="register")
     */
    public function register()
    {
        return $this->render('security/register.html.twig');
    }

    /**
     * @Route(path="/reset-password", name="forgot-password")
     */
    public function reset()
    {
        return $this->render('security/forgot-password.html.twig');
    }

}