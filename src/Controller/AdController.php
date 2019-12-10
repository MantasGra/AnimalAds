<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Form\MessageType;
use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Ad;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

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
        $entityManager = $this->getDoctrine()->getManager();
        $comments = $entityManager->getRepository('App:Comment')->findBy(
            ['ad' => '1', 'parentComment' => null]
        );
        
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('ad/view.html.twig', [
            'cmts' => $comments,
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{parent}/reply", name="replycomment")
     */
    public function reply(Request $request, $id, $parent)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        return $this->render('ad/reply.html.twig', [
            'id' => $id,
            'parent' => $parent,
            //'parentComment' => $parentComment,
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{parent}/reply/success", name="submitreply")
     */
    public function replysubmit(Request $request, $id, $parent)
    {
        
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            $parentComment = $this->getDoctrine()->getRepository(Comment::class)->find($parent);
            $comment -> setAd($ad);
            $comment -> setWrittenBy($this->getUser());
            $comment -> setCreatedAt(new \DateTime());
            $comment -> setParentComment($parentComment);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Your reply was added');
            return $this->redirectToRoute('view_ad');
        }
        $this->addFlash('warning', 'Something went wrong');
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/{id}/contact", name="contact")
     */
    public function contact(Request $request, $id)
    {

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        return $this->render('ad/contact.html.twig',[
            'id' => $id,
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/contact/success", name="submitcontact")
     */
    public function contactsubmit(Request $request, $id)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            $message -> setSentTo($ad->getCreatedBy());
            $message -> setSentFrom($this->getUser());
            $message -> setWrittenAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'Your message was sent');
            return $this->redirectToRoute('view_ad');

            $emailMessage = (new \Swift_Message('Message to seller'))
                ->setFrom('bemantelio@gmail.com')
                ->setTo($ad->getCreatedBy()->getEmail())
                ->setBody(
                    $this->renderView(
                        'message/email-message.html.twig'
                    ),
                    'text/html'
                );
            $mailer->send($emailMessage);
        }
        $this->addFlash('warning', 'Something went wrong');
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/{id}/{commid}/edit", name="editcomment")
     */
    public function editcomment(Request $request, $id, $commid)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commid);
        $form = $this->createForm(CommentType::class, $comment);
        return $this->render('ad/edit-comment.html.twig', [
            'id' => $id,
            'commid' => $commid,
            'form' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{commid}/edit/success", name="submitedit")
     */
    public function editcommentsubmit(Request $request, $id, $commid)
    {
        //$user = $this->getUser();
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commid);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($comment->getWrittenBy()->getId() == $this->getUser()->getId()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($comment);
                $entityManager->flush();
                $this->addFlash('success', 'Your comment was changed');
                return $this->redirectToRoute('view_ad');
            }
            $this->addFlash('warning', 'Something went wrong');
            return $this->redirectToRoute('view_ad');
        }
        $this->addFlash('warning', 'You are not the author of the comment.');
        return $this->redirectToRoute('view_ad');
    }



    /**
     * @Route(path="/ads/{id}/comment/", name="comment")
     */
    public function comment(Request $request, $id){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            $comment -> setAd($ad);
            $comment -> setWrittenBy($this->getUser());
            $comment -> setCreatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Your comment was added');
            return $this->redirectToRoute('view_ad');
        }
        $this->addFlash('warning', 'Something went wrong');
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/{adid}/{id}/deletecomment/", name="deletecomment")
     */
    public function deleteComment(Request $request, $id){
        $user = $this->getUser();
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        if ($comment->getWrittenBy()->getId() == $user->getId()) {
            if (count($comment->getReplies()) == 0) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($comment);
                $entityManager->flush();
                $this->addFlash('success', 'Your comment was deleted');
                return $this->redirectToRoute('view_ad');
            }
            $this->addFlash('warning', 'This comment has replies.');
            return $this->redirectToRoute('view_ad');
        }
        $this->addFlash('warning', 'You are not the author of the comment.');
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/new", name="add_ad")
     */
    public function add()
    {
        return $this->render('ad/add.html.twig');
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