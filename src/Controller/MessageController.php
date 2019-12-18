<?php


namespace App\Controller;


use App\Entity\Ad;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route(path="/messages", name="browse_messages")
     */
    public function index(MessageRepository $messageRepository)
    {
        $messages = $messageRepository->getMessages($this->getUser()->getId());
        return $this->render('message/index.html.twig', [
            'messages' => $messages
        ]);
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
        return $this->render('ad/contact.html.twig', [
            'id' => $id,    // Send ad {id} to contact template
            'form' => $form->createView(),  // Send created form to contact template
            'error' => $form->getErrors(true)
        ]);

        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
    }

    /**
     * @Route(path="/ads/{id}/contact/s", name="submitcontact")
     */
    public function contactsubmit(Request $request, $id, \Swift_Mailer $mailer)
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
            $message->setSentTo($ad->getCreatedBy());
            $message->setSentFrom($this->getUser());
            $message->setWrittenAt(new \DateTime());
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
                        'message/email-message.html.twig',
                        ['userEmail' => $ad->getCreatedBy()->getEmail()]
                    ),
                    'text/html'
                );
            $mailer->send($emailMessage);
            // Flash a success message
            $this->addFlash('success', 'Your message was sent');
            // Render view template
            return $this->redirectToRoute('view_ad', array('id' => $id));
        }
        // Flash a warning message
        $this->addFlash('warning', 'Something went wrong');
        // Render view template
        return $this->redirectToRoute('view_ad', array('id' => $id));
    }

    /**
     * @Route(path="/messages/{id}", name="message_read")
     */
    public function view($id, MessageRepository $messageRepository)
    {
        $message = $messageRepository->findOneBy(['id' => $id]);
        if (!$message->getIsRead()) {
            $message->setIsRead(true);
            $this->getDoctrine()->getManager()->flush();
        }
        if ($this->getUser() === $message->getSentFrom() || $this->getUser() === $message->getSentTo()) {
            return $this->render('message/view.html.twig', [
                'message' => $message
            ]);
        }
        $this->redirectToRoute('browse_messages');
    }

    /**
     * @Route(path="/messages/create/{id}", name="message_create")
     */
    public function create(User $user, EntityManagerInterface $em, Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSentTo($user);
            $message->setSentFrom($this->getUser());
            $message->setWrittenAt(new \DateTime());
            $em->persist($message);
            $em->flush();
            $this->addFlash('success', 'Your message was sent');
            return $this->redirectToRoute('browse_users');
        }
        return $this->render('ad/contact.html.twig', [ 
            'form' => $form->createView(), 
            'error' => $form->getErrors(true)
        ]);
    }
}