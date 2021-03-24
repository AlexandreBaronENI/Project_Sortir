<?php


namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\ForgotPasswordType;
use App\Form\ProfilType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/", name="profile-affichage")
     */
    public function afficherProfil()
    {

        $utilisateur = $this->getUser();
        $id = $utilisateur->getId();
        $profil = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($id);
        if( $profil ==  null){
            throw $this->createNotFoundException("Ce profil n'existe pas");
        }
        return $this->render('profile/view_profile.html.twig', [ 'profil' => $profil]);
    }

    /**
     * @Route("/modified", name="profile-modified")
     */
    public function modified(EntityManagerInterface $em, Request $request,UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = $this->getUser();

        $profilForm = $this->createForm(ProfilType::class, $utilisateur);
        $profilForm->handleRequest($request);


        if ($profilForm->isSubmitted() && $profilForm->isValid() ) {
            $hashed = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($hashed);
            $em->persist($utilisateur);
            $em->flush();

            $imageFile = $profilForm->get('image')->getData();

            if ($imageFile) {
                $newFilename = $utilisateur->getId().'.png';

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

            }
            $this->addFlash('success', 'Votre profil a bien été sauvegardé !');
            return $this->redirectToRoute('profile-affichage');
        }

        return $this->render('profile/add_profile.html.twig',[
            'profilForm' => $profilForm->createView(),'profil'=>$utilisateur
        ]);
    }

    /**
     * @Route("/{id}", name="profile-affichage-autre", requirements={"id"="\d+"})
     */
    public function afficherautreProfil(int $id)
    {
        if($id == null){
            $id = $this->getId();
                    }

        $profil = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($id);
        if( $profil ==  null){
            throw $this->createNotFoundException("Ce profil n'existe pas");
        }
        return $this->render('profile/view_profile.html.twig', [ 'profil' => $profil]);
    }

    /**
     * @Route("/add", name="profile-add")
     */
    public function add(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setActif(true);
        $utilisateur->setAdmin(false);
        
        $profilForm = $this->createForm(ProfilType::class, $utilisateur);
        $profilForm->handleRequest($request);


        if ($profilForm->isSubmitted() && $profilForm->isValid() ) {
            $hashed = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($hashed);
            $em->persist($utilisateur);
            $em->flush();

            $imageFile = $profilForm->get('image')->getData();

            if ($imageFile) {
                $newFilename = $utilisateur->getId() .'.png';

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dump($e);
                }}

            $this->addFlash('success', 'Votre profil a bien été sauvegardé !');
            return $this->redirectToRoute('profile-affichage');
        }

        return $this->render('profile/add_profile.html.twig',[
            'profilForm' => $profilForm->createView()
        ]);
    }
    /**
     * * Mot de passe oublié utilisateur
     * @Route("/forgotpassword", name="profile-forgot-password")
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

                return $this->redirectToRoute('profile-reset-password');
            }

        }
        return $this->render('profile/forgot_password.html.twig',[
            'forgotPasswordForm' => $forgotPasswordForm->createView()
        ]);
    }
    /**
     * * Réinitialisation du mot de passe utilisateur
     * @Route("/resetpassword", name="profile-reset-password")
     */
    public function resetpassword(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $userForm = new Utilisateur();
        $resetPasswordForm = $this->createForm(ResetPasswordType::class, $userForm);
        $resetPasswordForm->handleRequest($request);

        $mail = $request->getSession()->get('mail');
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['mail' => $mail]);

        if ($user == null) {
            throw $this->createNotFoundException('Utilisateur n a pas été trouvé !');
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