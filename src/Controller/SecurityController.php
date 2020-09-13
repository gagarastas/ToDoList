<?php


namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login/", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout/", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/register/", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator)
    {
        $registerTemplate = 'security/register.html.twig';
        if ($request->isMethod('GET')) {
            return $this->render($registerTemplate, ['registerErrors' => []]);
        }

        $registerErrors = [];
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $passwordCheck = $request->request->get('passwordCheck');
        $token = $request->request->get('_csrf_token');

        if (!$this->isCsrfTokenValid('register', $token))
            throw new InvalidCsrfTokenException();

        $em = $this->getDoctrine()->getManager();
        $checkUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!is_null($checkUser)) {
            $registerErrors[] = 'имя пользователя занято';
        }

        if ($password !== $passwordCheck || strlen($password) <= 4 || strlen($password) >= 20) {
            $registerErrors[] = 'пароль должен быть больше 4 и меньше 20 символов. Пароли должны быть одинаковы';
        }

        if (!empty($registerErrors)) {
            return $this->render('security/register.html.twig', ['registerErrors' => $registerErrors]);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        return $guardHandler->authenticateUserAndHandleSuccess($user, $request, $formAuthenticator, 'list');


    }
}