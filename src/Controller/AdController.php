<?php


namespace App\Controller;

use App\Entity\Ad;
use App\Entity\SavedAd;
use App\Form\AdType;
use DateTime;
use Doctrine\ORM\Mapping\Id;
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
            $ad->setCreatedBy($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ad);
            $entityManager->flush();

            return $this->redirectToRoute('browse_ads');
        }
        return $this->render('ad/add.html.twig', [
            'adForm' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/ads/view/{id}", name="view_ad")
     */
    public function view($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        $qb = $entityManager->createQueryBuilder();

        $qb->select('u')
            ->from('App:SavedAd', 'u')
            ->where('u.ad = :ad')
            ->andWhere('u.user = :user')
            ->setParameter('ad', $ad)
            ->setParameter('user', $this->getUser());

        $query = $qb->getQuery();
        $result = $query->getResult();

        $currentAdViewCount = $ad->getViewCount();
        $ad->setViewCount($currentAdViewCount + 1);
        $entityManager->persist($ad);
        $entityManager->flush();

        return $this->render('ad/view.html.twig', [
            'ad' => $ad,
            'savedAd' => $result
        ]);
    }


    /**
     * @Route(path="/ads/{id}/save", name="save_ad")
     */
    public function save($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        $qb = $entityManager->createQueryBuilder();

        $qb->select('u')
            ->from('App:SavedAd', 'u')
            ->where('u.ad = :ad')
            ->andWhere('u.user = :user')
            ->setParameter('ad', $ad)
            ->setParameter('user', $this->getUser());

        $query = $qb->getQuery();
        $result = $query->getResult();


        if ($result == null) {

            $saved_ad = new SavedAd();

            $saved_ad->setUser($this->getUser());
            $saved_ad->setSavedAt(new \DateTime());
            $saved_ad->setAd($ad);

            $entityManager->persist($saved_ad);
            $entityManager->flush();

            $this->addFlash('success', 'Ad saved!');
        } else {
            $this->addFlash('info', 'Ad already saved!');
        }

        $currentAdViewCount = $ad->getViewCount();
        $ad->setViewCount($currentAdViewCount - 1);
        $entityManager->persist($ad);
        $entityManager->flush();

        return $this->redirectToRoute('view_ad', [
            'id' => $ad->getId()
        ]);
    }

    /**
     * @Route(path="/ads/{id}/remove", name="remove_ad")
     */
    public function remove($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        $entityManager->remove($ad);
        $entityManager->flush();
        return $this->redirectToRoute('browse_ads');
    }


    /**
     * @Route(path="/ads/{id}/forget", name="forget_ad")
     */
    public function forget($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        $qb = $entityManager->createQueryBuilder();

        $qb->select('u')
            ->from('App:SavedAd', 'u')
            ->where('u.ad = :ad')
            ->andWhere('u.user = :user')
            ->setParameter('ad', $ad)
            ->setParameter('user', $this->getUser());

        $query = $qb->getQuery();
        $result = $query->getResult();

        $savedAd = $this->getDoctrine()
            ->getRepository(SavedAd::class)
            ->find($id);

        if ($result != null) {
            $entityManager->remove($result[0]);
            $entityManager->flush();
            $this->addFlash('success', 'Ad forgotten!');
        } else {
            $this->addFlash('info', 'Ad already forgotten!');
        }

        $currentAdViewCount = $ad->getViewCount();
        $ad->setViewCount($currentAdViewCount - 1);
        $entityManager->persist($ad);
        $entityManager->flush();

        return $this->redirectToRoute('view_ad', [
            'id' => $ad->getId(),
            'savedAd' => $result[0]
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
