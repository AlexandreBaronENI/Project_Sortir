<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\LoginFormType;
use App\Form\SearchSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * Page de connexion
     * @Route("/login", name="login")
     */
    public function login()
    {
        $loginForm = $this->createForm(LoginFormType::class);
        return $this->render('home/loginPage.html.twig', [
            'loginForm'=>$loginForm->createView()
        ]);
    }

    /**
     * Home
     * @Route("/", name="home")
     */
    public function home(EntityManagerInterface $em, Request $request)
    {
        $sorties = $em->getRepository(Sortie::class)->findAll();

        $searchForm = $this->createForm(SearchSortieType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $site = new Site();

            $sorties = [];
            foreach ($em->getRepository(Site::class)->findAll() as $siteTemp){
                if($searchForm->getData()['site'] != null && $searchForm->getData()['site']->getId() == $siteTemp->getId()){
                    $site = $siteTemp;
                }
            }

            $sortiesFiltered = $em->getRepository(Sortie::class)->findSorties(
                $site,
                $searchForm->getData()['nom'],
                $searchForm->getData()['dateDebut'],
                $searchForm->getData()['dateCloture']);

            $choices = $searchForm->getData()['choices'];
            if(!empty($choices)){
                if(in_array(1, $choices)){
                    foreach ($sortiesFiltered as $sortieTemp){
                        if($sortieTemp->getOrganisateur()->getId() == $this->getUser()->getId()){
                            $sorties[] = $sortieTemp;
                        }
                    }
                }
                if(in_array(2, $choices) || in_array(3, $choices)){
                    $sortiesPartipate = [];
                    foreach ($sortiesFiltered as $sortieTemp) {
                        foreach ($sortieTemp->getInscriptions() as $inscription) {
                            if($inscription->getParticipant()->getId() == $this->getUser()->getId()){
                                $sortiesPartipate[] = $sortieTemp;
                            }
                        }
                    }
                    if(in_array(2, $choices)){
                        $sorties = array_merge($sorties, $sortiesPartipate);
                    }
                    if(in_array(3, $choices)){
                        $sortiesNotPartipate = array_udiff($sortiesFiltered, $sortiesPartipate,
                            function ($obj_a, $obj_b) {
                                return strcmp($obj_a->id, $obj_b->id);
                            }
                        );
                        $sorties = array_merge($sorties, $sortiesNotPartipate);
                    }
                }
                if(in_array(4, $choices)){
                    $etatManager = new Etat($em->getRepository(Etat::class));

                    foreach ($sortiesFiltered as $sortieTemp) {
                        if($etatManager->IsFinished($sortieTemp->getEtat()->getId())){
                            $sorties[] = $sortieTemp;
                        }
                    }
                }
            }else{
                $sorties = $sortiesFiltered;
            }

        }

        return $this->render('home/home.html.twig', [
            'sorties' => $sorties,
            'user' => $this->getUser(),
            'searchForm' => $searchForm->createView()
        ]);
    }
}