<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\EmailType;
use App\Form\PasswordType;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route(path="/reset-password", name="forgot-password")
     */
    public function reset(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EmailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $tempToken = $this->randomString();
                $user->setTempPasswordToken($tempToken);
                $em->flush();
                $generatedLink = $this->generateUrl('reset-password', ['token' => $tempToken], UrlGeneratorInterface::ABSOLUTE_URL);

                $emailMessage = (new \Swift_Message('Password reset'))
                    ->setFrom('bemantelio@gmail.com')
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'security/password-confirmation.html.twig',
                            ['userEmail' => $user->getEmail(), 'link' => $generatedLink]
                        ),
                        'text/html'
                    );
                $mailer->send($emailMessage);
                $this->addFlash('success', 'Password link has been sent');
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('security/forgot-password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        UserAuthenticator $authenticator,
        \Swift_Mailer $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $token = $this->randomString();
            $user->setTempToken($token);
            $entityManager->flush();
            $generatedLink = $this->generateUrl('confirm_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $emailMessage = (new \Swift_Message('Email confirmation'))
                ->setFrom('bemantelio@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'registration/email-confirmation.html.twig',
                        ['userEmail' => $user->getEmail(), 'link' => $generatedLink]
                    ),
                    'text/html'
                );
            $mailer->send($emailMessage);

            $this->addFlash('info', 'An email has been sent to you to confirm your registration');
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'custom'
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => $form->getErrors(true)
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }

    private function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 55; $i++) {
            $randstring = $randstring . $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/reset-password/{token}", name="reset-password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['tempPasswordToken' => $token]);
        if ($user) {
            $form = $this->createForm(PasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $em->flush();
                return $this->redirectToRoute('login');
            }
            return $this->render('security/change-password.html.twig', [
                'form' => $form->createView(),
                'error' => $form->getErrors(true)
            ]);
        }
        throw new \Symfony\Component\HttpClient\Exception\InvalidArgumentException('Token not found');
    }

}