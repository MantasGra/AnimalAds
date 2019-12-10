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
        // Find all comments for {id} ad
        $comments = $entityManager->getRepository('App:Comment')->findBy(
            ['ad' => '1', 'parentComment' => null]
        );
        // Form for new comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        // Render view template
        return $this->render('ad/view.html.twig', [
            'cmts' => $comments,    // Send gathered comments to view template
            'form' => $form->createView(),  // Send created form to view template
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{parent}/reply", name="replycomment")
     */
    public function reply(Request $request, $id, $parent)
    {
        // Form for new reply to a comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        // Render reply template
        return $this->render('ad/reply.html.twig', [
            'id' => $id,    // Send ad {id} to reply template
            'parent' => $parent,    // Send parent comment id to reply template
            'form' => $form->createView(),  // Send created form to reply template
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{parent}/reply/success", name="submitreply")
     */
    public function replysubmit(Request $request, $id, $parent)
    {
        // Form for new reply to a comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        // Check if form was correctly filled
        if ($form->isSubmitted() && $form->isValid()) {
            // Get {id} ad
            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            // Get {parent} comment
            $parentComment = $this->getDoctrine()->getRepository(Comment::class)->find($parent);
            // Set all variables for a new reply to a comment
            $comment -> setAd($ad);
            $comment -> setWrittenBy($this->getUser());
            $comment -> setCreatedAt(new \DateTime());
            $comment -> setParentComment($parentComment);
            // Post the comment to DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            // Flash a success message
            $this->addFlash('success', 'Your reply was added');
            // Render view template
            return $this->redirectToRoute('view_ad');
        }
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/{id}/contact", name="contact")
     */
    public function contact(Request $request, $id)
    {
        // Form for new message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        // Render contact template
        return $this->render('ad/contact.html.twig',[
            'id' => $id,    // Send ad {id} to reply template
            'form' => $form->createView(),  // Send created form to reply template
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/contact/success", name="submitcontact")
     */
    public function contactsubmit(Request $request, $id)
    {
        // Form for new message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        // Check if form was correctly filled
        if ($form->isSubmitted() && $form->isValid()) {
            // Get {id} ad
            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            // Set all variables for a new message
            $message -> setSentTo($ad->getCreatedBy());
            $message -> setSentFrom($this->getUser());
            $message -> setWrittenAt(new \DateTime());
            // Post the message to DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            // Send an email to the message receiver about the message
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
            // Flash a success message
            $this->addFlash('success', 'Your message was sent');
            // Render view template
            return $this->redirectToRoute('view_ad');
        }
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/{id}/{commid}/edit", name="editcomment")
     */
    public function editcomment(Request $request, $id, $commid)
    {
        // Get {commid} comment
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commid);
        // Form for existing comment to edit
        $form = $this->createForm(CommentType::class, $comment);
        // Render contact template
        return $this->render('ad/edit-comment.html.twig', [
            'id' => $id,    // Send ad {id} to edit-comment template
            'commid' => $commid,    // Send comment {commid} to edit-comment template
            'form' => $form->createView(),  // Send created form to reply template
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{commid}/edit/success", name="submitedit")
     */
    public function editcommentsubmit(Request $request, $id, $commid)
    {
        // Get {commid} comment
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commid);
        // Form for existing comment to edit
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        // Check if the comment was written by current {user}
        if ($comment->getWrittenBy()->getId() == $this->getUser()->getId()) {
            // Check if form was correctly filled
            if ($form->isSubmitted() && $form->isValid()) {
                // Update existing comment
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($comment);
                $entityManager->flush();
                // Flash a success message
                $this->addFlash('success', 'Your comment was changed');
                // Render view template
                return $this->redirectToRoute('view_ad');
            }
            // Flash a warning message
            $this->addFlash('warning', 'Something went wrong');
            // Render view template
            return $this->redirectToRoute('view_ad');
        }
        // Flash a warning message
        $this->addFlash('warning', 'You are not the author of the comment.');
        // Render view template
        return $this->redirectToRoute('view_ad');
    }



    /**
     * @Route(path="/ads/{id}/comment/", name="comment")
     */
    public function comment(Request $request, $id){
        // Form for a new comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        // Check if form was correctly filled
        if ($form->isSubmitted() && $form->isValid()) {
            // Get {id} ad
            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            // Set all variables for a new comment
            $comment -> setAd($ad);
            $comment -> setWrittenBy($this->getUser());
            $comment -> setCreatedAt(new \DateTime());
            // Post the comment to DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            // Flash a success message
            $this->addFlash('success', 'Your comment was added');
            // Render view template
            return $this->redirectToRoute('view_ad');
        }
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad');
    }

    /**
     * @Route(path="/ads/{adid}/{id}/deletecomment/", name="deletecomment")
     */
    public function deleteComment(Request $request, $id){
        // Get commid comment
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        // Check if the comment was written by current {user}
        if ($comment->getWrittenBy()->getId() == $this->getUser()->getId()) {
            // Check if the comment has any children comments
            if (count($comment->getReplies()) == 0) {
                // Delete the comment from DB
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($comment);
                $entityManager->flush();
                // Flash a success message
                $this->addFlash('success', 'Your comment was deleted');
                // Render view template
                return $this->redirectToRoute('view_ad');
            }
            // Flash a warning message
            $this->addFlash('warning', 'This comment has replies.');
            // Render view template
            return $this->redirectToRoute('view_ad');
        }
        // Flash a warning message
        $this->addFlash('warning', 'You are not the author of the comment.');
        // Render view template
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