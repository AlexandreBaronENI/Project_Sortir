<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Sortie;
use App\Form\AddSortieType;
use App\Form\CancelSortieType;
use App\Form\EditSortieType;
use App\Repository\InscriptionRepository;
use DateTime;
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
        $etatManager = new Etat($em->getRepository(Etat::class));
        $sortie = new Sortie();
        $sortieForm = $this->createForm(AddSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            if ($sortie->getDateCloture() >= new DateTime() && $sortie->getDateDebut() > $sortie->getDateCloture()) {
                $etatManager = new Etat($em->getRepository(Etat::class));

                if ($sortieForm->get('save')->isClicked()) {
                    $etat = $etatManager->getDraft();
                    $sortie->setOrganisateur($this->getUser());
                    $sortie->setSite($this->getUser()->getSite());
                    $sortie->setEtat($etat);

                    $em->persist($sortie);
                    $em->flush();

                    $this->addFlash('success', 'La sortie à bien été enregistrée !');
                } elseif ($sortieForm->get('publish')->isClicked()) {
                    $etat = $etatManager->getOpen();
                    $sortie->setOrganisateur($this->getUser());
                    $sortie->setSite($this->getUser()->getSite());
                    $sortie->setEtat($etat);

                    $em->persist($sortie);
                    $em->flush();
                    $this->addGroup($sortie, $em);

                    $this->addFlash('success', 'La sortie à bien été publié !');
                }
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', 'La date de clôture des inscriptions doit être avant la date de début et après aujourd\'hui !');
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

        if ($sortie == null) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }

        if ($etatManager->IsClosed($sortie->getEtat()->getId()) || $etatManager->IsFinished($sortie->getEtat()->getId()) || $etatManager->IsCanceled($sortie->getEtat()->getId())) {
            throw $this->createNotFoundException('La sortie est terminée !');
        }

        if ($sortie->getEtat() == $etatManager->IsActive($sortie->getEtat()->getId())) {
            throw $this->createNotFoundException('La sortie est en cours !');
        }

        $sortieForm = $this->createForm(EditSortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if ($sortie->getDateCloture() >= new DateTime() && $sortie->getDateDebut() > $sortie->getDateCloture()) {

            if ($etatManager->IsDraft($sortie->getEtat()->getId()) && $sortieForm->get('save')->isClicked()) {
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('success', 'La sortie à bien été modifiée !');
            } elseif ($etatManager->IsDraft($sortie->getEtat()->getId()) && $sortieForm->get('publish')->isClicked()) {
                $etat = $etatManager->getOpen();
                $sortie->setEtat($etat);

                $em->persist($sortie);
                $em->flush();

                $this->addGroup($sortie, $em);

                $this->addFlash('success', 'La sortie à bien été publiée !');
            } elseif ($etatManager->IsDraft($sortie->getEtat()->getId()) && $sortieForm->get('delete')->isClicked()) {
                $em->remove($sortie);
                $em->flush();
                $this->addFlash('success', 'La sortie à bien été supprimée !');
                return $this->redirectToRoute('home');
            }
        }
        else {
            $this->addFlash('error', 'La date de clôture des inscriptions doit être avant la date de début !');
        }

        return $this->render("sortie/edit.html.twig", [
            "sortieForm" => $sortieForm->createView()
        ]);
    }

    /**
     * Acceder à ses sorties
     * @Route("/mine", name="my-sortie")
     */
    public function getSortieUser(EntityManagerInterface $em)
    {
        $sortiesPartipate = [];
        $sorties = $em->getRepository(Sortie::class)->findAll();
        foreach ($sorties as $sortieTemp) {
            foreach ($sortieTemp->getInscriptions() as $inscription) {
                if ($inscription->getParticipant()->getId() == $this->getUser()->getId()) {
                    $sortiesPartipate[] = $sortieTemp;
                }
            }
        }
        $sorties = $sortiesPartipate;
        return $this->render('sortie/my_sortie.html.twig', [
            'sorties' => $sorties,
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
        if ($sortie == null) {
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
     * Publier une sortie
     * @Route("/publish/{id}", name="sortie-publish", requirements={"id"="\d+"})
     */
    public function publish($id, EntityManagerInterface $em, Request $request)
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);
        $etatManager = new Etat($em->getRepository(Etat::class));

        if ($sortie == null) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }

        if (!$etatManager->IsDraft($sortie->getEtat()->getId())) {
            throw $this->createNotFoundException('La publication de cette sortie n\'est pas possible !');
        }
        $etat = $etatManager->getOpen();
        $sortie->setEtat($etat);

        $em->persist($sortie);
        $em->flush();

        $this->addGroup($sortie, $em);

        $this->addFlash('success', 'La sortie à bien été publiée !');
        return $this->redirectToRoute('home');
    }

    /**
     * Annuler une sortie
     * @Route("/cancel/{id}", name="sortie-cancel", requirements={"id"="\d+"})
     */
    public function cancel($id, EntityManagerInterface $em, Request $request)
    {
        $etatManager = new Etat($em->getRepository(Etat::class));
        $userRole = $this->getUser()->getRoles();
        $contains = $userRole[0];
        $sortie = $em->getRepository(Sortie::class)->find($id);
        $sortieForm = $this->createForm(CancelSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortie == null) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        } elseif ($sortie->getOrganisateur()->getId() != $this->getUser()->getId() && $contains !== 'ROLE_ADMIN') {
            throw $this->createNotFoundException('Vous n\'êtes pas l\'organisateur de cette sortie !');
        } elseif (!$etatManager->IsOpen($sortie->getEtat()->getId())) {
            throw $this->createNotFoundException('Cette sortie ne peut être annulée !');
        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $etat = $etatManager->getCanceled();
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Sortie annulée !');
            return $this->redirectToRoute('home');
        }
        return $this->render('sortie/cancel.html.twig', [
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * S'inscrire à une sortie
     * @Route("/register/{id}", name="sortie-register")
     */
    public function register(int $id, EntityManagerInterface $em, Sortie $sortie)
    {
        if($sortie->getnbInscription() < $sortie->getNbInscriptionMax()){
            $inscription = new Inscription($em->getRepository(Inscription::class));
            $inscription->setSortie($sortie);
            $inscription->setParticipant($this->getUser());
            $inscription->setDateInscription(new DateTime());
            $em->persist($inscription);
            $em->flush();

            $this->addFlash('success', 'Inscription à la sortie réussie !');

        }else{
            $this->addFlash('error', 'Le nombre de participants est déjà au maximum !');
        }
        return $this->redirectToRoute('home');
    }

    /**
     * Désinscrire à une sortie
     * @Route("/withdraw/{id}", name="sortie-withdraw")
     */
    public function withdraw(EntityManagerInterface $em, InscriptionRepository $inscriptionRepository, Sortie $sortie)
    {
        $inscription = $inscriptionRepository->findOneBy(['sortie' => $sortie, 'participant' => $this->getUser()]);

        $em->remove($inscription);
        $em->flush();
        $this->addFlash('success', 'Desinscription à la sortie réussie !');
        return $this->redirectToRoute('home');
    }

    private function addGroup($sortie, $em){
        $userRole = $this->getUser()->getRoles();
        $contains = $userRole[0];
        if($contains === 'ROLE_ADMIN'){
            foreach ($this->getUser()->getGroups() as $group){
                foreach ($group->getUtilisateurs() as $utilisateur){
                    $inscription = new Inscription();
                    $inscription->setSortie($sortie);
                    $inscription->setDateInscription(new DateTime());
                    $inscription->setParticipant($utilisateur);

                    $em->persist($inscription);
                    $em->flush();
                }
            }
        }
    }

}