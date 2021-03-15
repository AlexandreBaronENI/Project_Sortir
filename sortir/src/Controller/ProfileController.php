<?php


namespace App\Controller;


use App\Entity\Participants;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * * Mot de passe oublié utilisateur
     * @Route("/forgotpassword", name="forgotPassword")
     */
    public function forgotpassword(EntityManagerInterface $em, Request $request)
    {

        //$mail = $request->request->get('mail_mdp_oublie');
        $mail = 'baronalexandre35@gmail.com';

        $forgotPassworForm = $this->createForm(ForgotPasswordType::class);

        if ($mail !== null) {
            $user = $this->getDoctrine()->getRepository(Participants::class)->findOneBy(['mail' => $mail]);

            if ($user == null) {
                throw $this->createNotFoundException('Utilisateur nas pas été trouvé');
            }

            $resetPassworForm = $this->createForm(ResetPasswordType::class, $user);

            return $this->render('profile/reset_password.html.twig',[
                'resetPassworForm' => $resetPassworForm->createView()
            ]);

        }
        return $this->render('profile/forgot_password.html.twig',[
            'forgotPassworForm' => $forgotPassworForm->createView()
        ]);
    }
}