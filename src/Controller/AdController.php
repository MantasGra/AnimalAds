<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Entity\Ad;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $entityManager = $this->getDoctrine()->getManager();
        // Find all comments for {id} ad
        //$ad = $entityManager->getRepository('App:Ad')->find($id);
        //$comments = $ad.getComments();
        $comments = $entityManager->getRepository('App:Comment')->findBy(
            ['ad' => $id, 'parentComment' => null]
        );
        // Form for new comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        // Render view template
        return $this->render('ad/view.html.twig', [
            'id' => $id,
            'cmts' => $comments,    // Send gathered comments to view template
            'form' => $form->createView(),  // Send created form to view template
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{commid}/reply", name="replycomment")
     */
    public function reply(Request $request, $id, $commid)
    {
        // Form for new reply to a comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        // Render reply template
        return $this->render('ad/replyedit.html.twig', [
            'id' => $id,    // Send ad {id} to reply template
            'commid' => $commid,
            'title' => 'Reply to a comment',
            'pathlink' => 'reply',
            'buttonText' => 'Reply',
            'parent' => $commid,    // Send parent comment id to reply template
            'form' => $form->createView(),  // Send created form to reply template
            'error' => $form->getErrors(true)
        ]);
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
    }

    /**
     * @Route(path="/ads/{id}/{commid}/reply/s", name="submitreply")
     */
    public function replysubmit(Request $request, $id, $commid)
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
            $parentComment = $this->getDoctrine()->getRepository(Comment::class)->find($commid);
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
            return $this->redirectToRoute('view_ad', array('id' => $id));
        }
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
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
        return $this->render('ad/replyedit.html.twig', [
            'id' => $id,    // Send ad {id} to edit-comment template
            'commid' => $commid,
            'title' => 'Edit your comment',
            'pathlink' => 'edit',
            'buttonText' => 'Edit',
            'commid' => $commid,    // Send comment {commid} to edit-comment template
            'form' => $form->createView(),  // Send created form to reply template
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route(path="/ads/{id}/{commid}/edit/s", name="submitedit")
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
                return $this->redirectToRoute('view_ad', array('id' => $id));
            }
            // Flash a warning message
            $this->addFlash('warning', 'Something went wrong');
            // Render view template
            return $this->redirectToRoute('view_ad', array('id' => $id));
        }
        // Flash a warning message
        $this->addFlash('warning', 'You are not the author of the comment.');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
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
            return $this->redirectToRoute('view_ad', array('id' => $id));
        }
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
    }

    /**
     * @Route(path="/ads/{id}/{commid}/deletecomment/", name="deletecomment")
     */
    public function deleteComment(Request $request, $id, $commid){
        // Get commid comment
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commid);
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
                return $this->redirectToRoute('view_ad', array('id' => $id));
            }
            // Flash a warning message
            $this->addFlash('warning', 'This comment has replies.');
            // Render view template
            return $this->redirectToRoute('view_ad', array('id' => $id));
        }
        // Flash a warning message
        $this->addFlash('warning', 'You are not the author of the comment.');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
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