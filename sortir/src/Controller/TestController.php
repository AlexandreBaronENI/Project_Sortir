<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Utilisateur;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    /**
     * @Route("/add", name="add")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setActif(true);
        $utilisateur->setAdmin(false);

        $profilForm = $this->createForm(ProfilType::class, $utilisateur);
        $profilForm->handleRequest($request);


        if ($profilForm->isSubmitted() && $profilForm->isValid() ) {
            $em->persist($utilisateur);
            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été sauvegardé !');
            return $this->redirectToRoute('home',['id'=>$utilisateur->getId()]);
        }

        return $this->render('profile/add_profile.html.twig',[
            'profilForm' => $profilForm->createView()
        ]);
    }


}
