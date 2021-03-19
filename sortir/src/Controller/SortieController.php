<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\AddSortieType;
use App\Form\CancelSortieType;
use App\Form\EditSortieType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{

    /**
     * Créer une sortie
     * @Route("/add", name="sortie-add")
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
     * @Route("/edit/{id}", name="sortie-edit", requirements={"id"="\d+"})
     */
    public function edit($id, EntityManagerInterface $em, Request $request)
    {
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
     * @Route("/{id}", name="sortie-affichage")
     */
    public function afficherSortie(int $id, EntityManagerInterface $em)
    {
        $etatManager = new Etat($em->getRepository(Etat::class));

        $sortie = $this->getDoctrine()
            ->getRepository(Sortie::class)
            ->find($id);
        if( $sortie ==  null){
            throw $this->createNotFoundException("Cette sortie n'existe pas");
        }
        if ($etatManager->IsArchived($sortie->getEtat()->getId())) {
            throw $this->createNotFoundException('Cette sortie n\'existe plus !');
        }

        return $this->render('sortie/affichageSortie.html.twig', [ 
            'sortie' => $sortie,
        ]);
    }

    /**
     * Se desinscrire d'une sortie
     * @Route("/cancel/{id}", name="sortie-cancel", requirements={"id"="\d+"})
     */
    public function cancel($id, EntityManagerInterface $em, Request $request)
    {
        $etatManager = new Etat($em->getRepository(Etat::class));

        $sortie = $em->getRepository(Sortie::class)->find($id);
        $sortieForm = $this->createForm(CancelSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortie == null) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }
        elseif ($sortie->getOrganisateur()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException('Vous n\'êtes pas l\'organisateur de cette sortie !');
        }
        elseif (!$etatManager->IsOpen($sortie->getEtat()->getId())) {
            throw $this->createNotFoundException('Cette sortie ne peut être annulée !');
        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $etat = $etatManager->getCanceled();
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();
        }
        return $this->render('sortie/cancel.html.twig', [
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView()
        ]);
    }

}