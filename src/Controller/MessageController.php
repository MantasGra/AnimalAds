<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route(path="/messages", name="browse_messages")
     */
    public function index()
    {
        return $this->render('message/index.html.twig');
    }

}