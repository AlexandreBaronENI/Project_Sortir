<?php


namespace App\Controller;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController 
{
    /**
     * Gestion lieux
     * @Route("/locations" name="admin-locations")
     */
    public function locations()
    {

    }

    /**
     * Gestion sites
     * @Route("/sites" name="admin-sites")
     */
    public function sites()
    {

    }

    /**
     * Gestion des users
     * @Route("/users", name="admin-users")
     */
    public function users()
    {

    }
}