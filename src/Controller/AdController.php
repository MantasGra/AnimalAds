<?php


namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class AdController extends AbstractController
{
    /**
     * @Route(path="/ads", name="browse_ads")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $query = $queryBuilder->select('u')
                    ->from('App:Ad', 'u')
                    ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('ad/index.html.twig', [
            'pagination' => $pagination
        ]);
       
    }

    /**
     * @Route(path="/ads/{id}", name="view_ad")
     */
    public function view($id)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        return $this->render('ad/view.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * @Route(path="/ads/new", name="add_ad")
     */
    public function add(Request $request)
    {
        $ad = new Ad();


        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setReportCount(0);
            $ad->setViewCount(0);
            $ad->setCreatedAt(new \DateTime());
            $ad->setCreatedBy( $this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ad);
            $entityManager->flush();

            return $this->render('ad/index.html.twig');
        }
        return $this->render('ad/add.html.twig', [
            'adForm' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/ads/report", name="report_ad")
     */
    public function report()
    {
        return $this->render('ad/report.html.twig');
    }

    /**
     * @Route(path="/ads/boost", name="boost_ad")
     */
    public function boost()
    {
        return $this->render('ad/boost.html.twig');
    }
}
