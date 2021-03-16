<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\AddSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     * Créer une sortie
     * @Route("/addSortie", name="addSortie")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        {
            $sortie = new Sortie();
            $sortieForm = $this->createForm(AddSortieType::class, $sortie);
            $sortieForm->handleRequest($request);


            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
                if ($sortieForm->get('save')->isClicked()) {
                    $etat = $em->getRepository(Etat::class)->findOneBy(['libelle' => 'draft']);
                    $sortie->setOrganisateur($this->getUser());
                    $sortie->setSite($this->getUser()->getSite());
                    $sortie->setEtat($etat);

                    $em->persist($sortie);
                    $em->flush();
                }
                elseif ($sortieForm->get('publish')->isClicked()) {
                    $etat = $em->getRepository(Etat::class)->findOneBy(['libelle' => 'open']);
                    $sortie->setOrganisateur($this->getUser());
                    $sortie->setSite($this->getUser()->getSite());
                    $sortie->setEtat($etat);

                    $em->persist($sortie);
                    $em->flush();
                }
            }


            return $this->render("sortie/add.html.twig", [
                "sortieForm" => $sortieForm->createView()
            ]);
        }
    }
}