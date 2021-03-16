<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    /**
     * @Route("/test/add", name="test.add")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        $participant = new Participants();
        $profilForm = $this->createForm(ProfilType::class, $participant);

        $profilForm->handleRequest($request);
        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été sauvegardé !');
            return $this->redirectToRoute('/forgotpassword', ['NoParticipant' => $participant->getNoParticipant()]);
        }

        return $this->render('profile/profil.html.twig', [

            "profilForm" => $profilForm->createView()
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->render("profile/profiltemp.html.twig");
    }
}
