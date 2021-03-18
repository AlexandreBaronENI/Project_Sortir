<?php


namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController 
{
    /**
     * Gestion lieux
     * @Route("/locations", name="admin-locations")
     */
    public function locations()
    {
        return $this->redirectToRoute('home');
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