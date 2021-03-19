<?php


namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AddLocationType;
use App\Form\EditLocationType;

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
        }

        return $this->render('admin/location/add.html.twig',[
            'locationForm' => $locationForm->createView()
        ]);
    }

    /**
     * Modification d'un lieu
     * @Route("/location/edit/{id}", name="edit-location")
     */
    public function edit(int $id, EntityManagerInterface $em, Request $request)
    {
        $lieu = $em->getRepository(Lieu::class)->find($id);
        $locationForm = $this->createForm(EditLocationType::class, $lieu);
        $locationForm->handleRequest($request);
        if ($locationForm->isSubmitted() && $sortieForm->isValid()) {
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('admin-locations');
        }

        return $this->render("admin/location/edit.html.twig", [
            "locationForm" => $locationForm->createView()
        ]);

    }

    /**
     * Gestion sites
     * @Route("/sites", name="admin-sites")
     */
    public function sites()
    {
        return $this->redirectToRoute('home');
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