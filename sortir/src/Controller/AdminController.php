<?php


namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Lieu;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AddLocationType;
use App\Form\EditLocationType;
use App\Form\AddSiteType;
use App\Form\EditSiteType;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController 
{
    /**
     * Gestion lieux
     * @Route("/locations", name="admin-locations")
     */
    public function locations(EntityManagerInterface $em, Request $request)
    {
        $lieux = $em->getRepository(Lieu::class)->findAll();
        return $this->render('admin/location/view_locations.html.twig', [
            'lieux' => $lieux
        ]);
    }

    /**
     * Ajout d'un lieu
     * @Route("/location/add", name="add-location")
     */
    public function addLocation(EntityManagerInterface $em, Request $request)
    {
        $lieu = new Lieu();
        $locationForm = $this->createForm(addLocationType::class, $lieu);
        $locationForm->handleRequest($request);
        
        if ($locationForm->isSubmitted() && $locationForm->isValid() ) {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Nouveau lieu ajouté !');
            return $this->redirectToRoute('admin-locations');
        }

        return $this->render('admin/location/add.html.twig',[
            'locationForm' => $locationForm->createView()
        ]);
    }

    /**
     * Modification d'un lieu
     * @Route("/location/edit/{id}", name="edit-location")
     */
    public function editLocation(int $id, EntityManagerInterface $em, Request $request)
    {
        $lieu = $em->getRepository(Lieu::class)->find($id);
        $locationForm = $this->createForm(EditLocationType::class, $lieu);
        $locationForm->handleRequest($request);
        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $em->persist($lieu);
            $em->flush();
            return $this->redirectToRoute('admin-locations');
        }

        return $this->render("admin/location/edit.html.twig", [
            "locationForm" => $locationForm->createView()
        ]);

    }

    /**
     * Supprimer un site
     * @Route("/location/delete/{id}", name="delete-location")
     */
    public  function deleteLocation(int $id, EntityManagerInterface $em, Request $request)
    {
        $lieu = $em->getRepository(Lieu::class)->find($id);
        $em->remove($lieu);
        $em->flush();

        return $this->redirectToRoute('admin-locations');
    }

    /**
     * Gestion sites
     * @Route("/sites", name="admin-sites")
     */
    public function sites(EntityManagerInterface $em)
    {
        $sites = $em->getRepository(Site::class)->findAll();
        return $this->render('admin/site/view_sites.html.twig', [
            'sites' => $sites
        ]);
    }

    /**
     * Ajout d'un site
     * @Route("/site/add", name="add-site")
     */
    function addSite(EntityManagerInterface $em, Request $request)
    {
        $site = new Site();
        $siteForm = $this->createForm(addSiteType::class, $site);
        $siteForm->handleRequest($request);
        
        if ($siteForm->isSubmitted() && $siteForm->isValid() ) {
            $em->persist($site);
            $em->flush();

            $this->addFlash('success', 'Nouveau site ajouté !');
            return $this->redirectToRoute('admin-sites');
        }

        return $this->render('admin/site/add.html.twig',[
            'siteForm' => $siteForm->createView()
        ]);
    }
    /**
     * Modification d'un site
     * @Route("/site/edit/{id}", name="edit-site")
     */
    public function editSite(int $id, EntityManagerInterface $em, Request $request)
    {
        $site = $em->getRepository(Site::class)->find($id);
        $siteForm = $this->createForm(EditSiteType::class, $site);
        $siteForm->handleRequest($request);
        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $em->persist($site);
            $em->flush();
            return $this->redirectToRoute('admin-sites');
        }

        return $this->render("admin/site/edit.html.twig", [
            "siteForm" => $siteForm->createView()
        ]);
    }
    /**
     * Suppression d'un site
     * @Route("/site/delete/{id}", name="delete-site")
     */
    public function deleteSite(int $id, EntityManagerInterface $em, Request $request)
    {
        $site = $em->getRepository(Site::class)->find($id);
        $em->remove($site);
        $em->flush();

        return $this->redirectToRoute('admin-sites');
    }

    /**
     * Gestion des users
     * @Route("/users", name="admin-users")
     */
    public function users()
    {
        return $this->redirectToRoute('home');
    }
}