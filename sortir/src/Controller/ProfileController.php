<?php


namespace App\Controller;


use App\Entity\Utilisateur;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
     /**
     * Page de connexion
     * @Route("/home", name="home")
     */
    public function home()
    {
        $loginForm = $this->createForm(loginFormType::class);
        return $this->render('home/loginPage.html.twig', 
        ['loginForm'=>$loginForm->createView()]);
    }

    /**
     * * Mot de passe oublié utilisateur
     * @Route("/forgotpassword", name="forgotPassword")
     */
    public function forgotpassword(EntityManagerInterface $em, Request $request)
    {

        $userForm = new Utilisateur();
        $forgotPasswordForm = $this->createForm(ForgotPasswordType::class, $userForm);
        $forgotPasswordForm->handleRequest($request);

        if($forgotPasswordForm->isSubmitted() && $forgotPasswordForm->isValid()){

            if($userForm->getMail() !== null){
                $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['mail' => $userForm->getMail()]);

                if ($user == null) {
                    throw $this->createNotFoundException('Utilisateur nas pas été trouvé !');
                }

                $session = $request->getSession();
                $session->set('mail', $user->getMail());

                return $this->redirectToRoute('resetPassword');
            }

        }
        return $this->render('profile/forgot_password.html.twig',[
            'forgotPasswordForm' => $forgotPasswordForm->createView()
        ]);
    }
    /**
     * * Réinitialisation du mot de passe utilisateur
     * @Route("/resetpassword", name="resetPassword")
     */
    public function resetpassword(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $userForm = new Utilisateur();
        $resetPasswordForm = $this->createForm(ResetPasswordType::class, $userForm);
        $resetPasswordForm->handleRequest($request);

        $mail = $request->getSession()->get('mail');
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['mail' => $mail]);

        if ($user == null) {
            throw $this->createNotFoundException('Utilisateur nas pas été trouvé !');
        }

        if($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()){

            if($user->getMail() !== null && $userForm->getPassword() != null){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $userForm->getPassword()
                    )
                );
                $em->persist($user);
                $em->flush();
            }

        }

        return $this->render('profile/reset_password.html.twig',[
            'resetPasswordForm' => $resetPasswordForm->createView()
        ]);
    }
}