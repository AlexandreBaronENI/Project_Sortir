<?php


namespace App\Controller;


use App\Entity\Participants;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * * Mot de passe oublié utilisateur
     * @Route("/forgotpassword", name="forgotPassword")
     */
    public function forgotpassword(EntityManagerInterface $em, Request $request)
    {

        $participant = new Participants();
        $forgotPasswordForm = $this->createForm(ForgotPasswordType::class, $participant);
        $forgotPasswordForm->handleRequest($request);

        if($forgotPasswordForm->isSubmitted() && $forgotPasswordForm->isValid()){

            if($participant->getMail() !== null){
                $user = $this->getDoctrine()->getRepository(Participants::class)->findOneBy(['mail' => $participant->getMail()]);

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

        $participant = new Participants();
        $resetPasswordForm = $this->createForm(ResetPasswordType::class, $participant);
        $resetPasswordForm->handleRequest($request);

        $mail = $request->getSession()->get('mail');
        $user = $this->getDoctrine()->getRepository(Participants::class)->findOneBy(['mail' => $mail]);

        if ($user == null) {
            throw $this->createNotFoundException('Utilisateur nas pas été trouvé !');
        }

        if($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()){

            if($user->getMail() !== null && $participant->getPassword() != null){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $participant->getPassword()
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