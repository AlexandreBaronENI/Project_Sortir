<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\AddSortieType;
use App\Form\EditSortieType;
use App\Repository\LieuRepository;
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

    /**
     * Modifier une sortie
     * @Route("/editsortie-{id}", name="editSortie", requirements={"id"="\d+"})
     */
    public function edit($id, EntityManagerInterface $em, Request $request)
    {
        $user = $em->getRepository(Utilisateur::class)->find(1);
        $etatManager = new Etat($em->getRepository(Etat::class));

        $sortie = $em->getRepository(Sortie::class)->find($id);
        $sortieForm = $this->createForm(EditSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortie == null) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }

        if($etatManager->IsClosed($sortie->getEtat()->getId()) || $etatManager->IsFinished($sortie->getEtat()->getId()) || $etatManager->IsCanceled($sortie->getEtat()->getId())){
            throw $this->createNotFoundException('La sortie est terminée !');
        }

        if($sortie->getEtat() == $etatManager->IsActive($sortie->getEtat()->getId())){
            throw $this->createNotFoundException('La sortie est en cours !');
        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            if ($etatManager->IsDraft($sortie->getEtat()->getId()) && $sortieForm->get('save')->isClicked()) {
                $em->persist($sortie);
                $em->flush();
            }
            elseif ($etatManager->IsDraft($sortie->getEtat()->getId()) && $sortieForm->get('publish')->isClicked()) {
                $etat = $etatManager->getOpen();
                $sortie->setEtat($etat);

                $em->persist($sortie);
                $em->flush();
            }
            elseif (($etatManager->IsDraft($sortie->getEtat()->getId()) || $etatManager->IsOpen($sortie->getEtat()->getId())) && $sortieForm->get('delete')->isClicked()) {
                $etat = $etatManager->getCanceled();
                $sortie->setEtat($etat);
                $em->persist($sortie);
                $em->flush();
            }
        }

        return $this->render("sortie/edit.html.twig", [
            "sortieForm" => $sortieForm->createView()
        ]);
    }
    /**
     * Afficher une sortie
     * @Route("/display_sortie/{id}", name="display_sortie")
     */
    public function afficherSortie(int $id, EntityManagerInterface $em)
    {
        $sortie = $this->getDoctrine()
        ->getRepository(Sortie::class)
        ->find($id);
        return $sortie
            ? $this->render('sortie/affichageSortie.html.twig', [  
                'sortie'          => $sortie
            ])
            : throw $this->createNotFoundException("Cette sortie n'existe pas");
    }

}