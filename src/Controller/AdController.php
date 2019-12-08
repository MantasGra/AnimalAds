<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReportType;
use App\Entity\Ad;
use App\Entity\Boost;
use App\Form\BoostType;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route(path="/ads/{id}", name="view_ad")
     */
    public function view($id)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        return $this->render('ad/view.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route(path="/ads/new", name="add_ad")
     */
    public function add()
    {
        return $this->render('ad/add.html.twig');
    }

    /**
     * @Route(path="/ads/{id}/report", name="report_ad")
     */
    public function report($id, Request $request, \Swift_Mailer $mailer)
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $form = $this->createForm(ReportType::class);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();
            $emailMessage = (new \Swift_Message('An ad has been reported!'))
            ->setFrom('bemantelio@gmail.com')
            ->setTo('bemantelio@gmail.com')
            ->setBody(
                $this->renderView(
                    'ad/report-message.html.twig',
                    ['ad' => $ad, 'user' => $user, 'message' => $form->getData()['reportMessage']]
                ),
                'text/html'
            );
        $mailer->send($emailMessage);
            $this->addFlash('success', 'Thank you!');
            return $this->redirectToRoute('view_ad', ['id' => $id]);
        }
        return $this->render('ad/report.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route(path="/ads/{id}/boost", name="boost_ad")
     */
    public function boost($id, Request $request)
    {
        $boost = new Boost();
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $form = $this->createForm(BoostType::class, $boost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $totalPrice = 0;
            if ($boost->getType() == "Premium")
            {
                $totalPrice = 1;
            }
            else if ($boost->getType() == "Basic")
            {
                $totalPrice = 0.75;
            }
            switch($boost->getDuration())
            {
                case 3:
                    $totalPrice *= 2.5;
                    break;
                case 7:
                    $totalPrice *= 5.5;
                    break;
                case 30:
                    $totalPrice *= 24;
                    break;
                default:
                    break;
            }

            return $this->render('ad/pay.html.twig', [
                'ad' => $ad,
                'boost' => $boost,
                'totalPrice' => $totalPrice
            ]);
        }

        return $this->render('ad/boost.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
            'errors' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/pay", name="pay_ad")
     */
    public function pay($id, Request $request)
    {
        $boost = new Boost();
        $boost->setType($request->get('type'));
        $boost->setDateFrom(new \DateTime($request->get('dateFrom')));
        $boost->setDuration($request->get('duration'));
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $boost->setAd($ad);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($boost);
        $entityManager->flush();
        $this->addFlash('success', 'Your add has been boosted. Thank you for choosing animal ads!');
        return $this->redirectToRoute('view_ad', ['id' => $id]);
    }
}