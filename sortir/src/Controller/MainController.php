<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\SearchSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route("/")
 */
class MainController extends AbstractController
{

    /**
     * Page de connexion
     * @Route("/login", name="main-login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        return $this->render("home/loginPage.html.twig", array('error' => $error));
    }

    /**
     * @Route("/", name="home")
     */
    public function home(EntityManagerInterface $em, Request $request)
    {
        $etatManager = new Etat($em->getRepository(Etat::class));

        // Gestion du mobile
        $all_sorties = $em->getRepository(Sortie::class)->findAll();
        $sortiesParticipate = [];
        foreach ($all_sorties as $sortieTemp) {
            foreach ($sortieTemp->getInscriptions() as $inscription) {
                if ($inscription->getParticipant()->getId() == $this->getUser()->getId()) {
                    $sortiesParticipate[] = $sortieTemp;
                }
            }
        }
        $sorties_mobile = $sortiesParticipate;

        // Gestion du desktop
        $sorties = $all_sorties;
        $sortiesDraft = $em->getRepository(Sortie::class)->findBy(
            array('organisateur' => $this->getUser()->getId(),
                'etat' => $etatManager->getDraft()->getId())
        );
        $sorties = array_merge($sorties, $sortiesDraft);
        $sorties = array_unique($sorties, SORT_REGULAR);

        $searchForm = $this->createForm(SearchSortieType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $site = new Site();

            $sorties = [];
            foreach ($em->getRepository(Site::class)->findAll() as $siteTemp) {
                if ($searchForm->getData()['site'] != null && $searchForm->getData()['site']->getId() == $siteTemp->getId()) {
                    $site = $siteTemp;
                }
            }

            $sortiesFiltered = $em->getRepository(Sortie::class)->findSorties(
                $site,
                $searchForm->getData()['nom'],
                $searchForm->getData()['dateDebut'],
                $searchForm->getData()['dateCloture'],
                $this->getUser()->getId()
            );

            $choices = $searchForm->getData()['choices'];
            if (!empty($choices)) {
                if (in_array(1, $choices)) {
                    foreach ($sortiesFiltered as $sortieTemp) {
                        if ($sortieTemp->getOrganisateur()->getId() == $this->getUser()->getId()) {
                            $sorties[] = $sortieTemp;
                        }
                    }
                }
                if (in_array(2, $choices) || in_array(3, $choices)) {
                    foreach ($sortiesFiltered as $sortieTemp) {
                        foreach ($sortieTemp->getInscriptions() as $inscription) {
                            if ($inscription->getParticipant()->getId() == $this->getUser()->getId()) {
                                $sortiesParticipate[] = $sortieTemp;
                            }
                        }
                    }
                    if (in_array(2, $choices)) {
                        $sorties = array_merge($sorties, $sortiesParticipate);
                    }
                    if (in_array(3, $choices)) {
                        $sortiesNotPartipate = array_udiff($sortiesFiltered, $sortiesParticipate,
                            function ($obj_a, $obj_b) {
                                return strcmp($obj_a->getId(), $obj_b->getId());
                            }
                        );
                        $sorties = array_merge($sorties, $sortiesNotPartipate);
                    }
                }
                if (in_array(4, $choices)) {
                    foreach ($sortiesFiltered as $sortieTemp) {
                        if ($etatManager->IsFinished($sortieTemp->getEtat()->getId())) {
                            $sorties[] = $sortieTemp;
                        }
                    }
                }
                $sorties = array_unique($sorties, SORT_REGULAR);
            } else {
                $sorties = $sortiesFiltered;
            }

        }

        return $this->render('home/home.html.twig', [
            'sorties' => $sorties,
            'user' => $this->getUser(),
            'sortiesMobile' => $sorties_mobile,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * Logout
     * @Route("/logout", name="main-logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('main-login');
    }


}