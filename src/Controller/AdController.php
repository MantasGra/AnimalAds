<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route(path="/ads", name="browse_ads")
     */
    public function index()
    {
        return $this->render('ad/index.html.twig');
    }

    /**
     * @Route(path="/ads/1", name="view_ad")
     */
    public function view()
    {
        return $this->render('ad/view.html.twig');
    }

    /**
     * @Route(path="/ads/new", name="add_ad")
     */
    public function add()
    {
        return $this->render('ad/add.html.twig');
    }
}