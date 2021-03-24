<?php


namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use App\Entity\Group;
use App\Form\AddLocationType;
use App\Form\AddSiteType;
use App\Form\AddTownType;
use App\Form\AddGroupType;
use App\Form\AddUserAdminType;
use App\Form\AdminAddUsersType;
use App\Form\EditLocationType;
use App\Form\EditSiteType;
use DateTime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $locationForm = $this->createForm(AddLocationType::class, $lieu);
        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Nouveau lieu ajouté !');
            return $this->redirectToRoute('admin-locations');
        }

        return $this->render('admin/location/add.html.twig', [
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
    public function deleteLocation(int $id, EntityManagerInterface $em, Request $request)
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
        $siteForm = $this->createForm(AddSiteType::class, $site);
        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $em->persist($site);
            $em->flush();

            $this->addFlash('success', 'Nouveau site ajouté !');
            return $this->redirectToRoute('admin-sites');
        }

        return $this->render('admin/site/add.html.twig', [
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
            $this->addFlash('success', 'Site modifié !');
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
        $this->addFlash('success', 'Site supprimé !');
        return $this->redirectToRoute('admin-sites');
    }

    /**
     * Gestion des villes
     * @Route("/towns", name="admin-towns")
     */
    public function towns(EntityManagerInterface $em, Request $request)
    {
        $villes = $em->getRepository(Ville::class)->findAll();
        return $this->render('admin/town/view_towns.html.twig', [
            'villes' => $villes
        ]);
    }

    /**
     * Ajout de ville
     * @Route("town/add", name="add-town")
     */
    public function addTown(EntityManagerInterface $em, Request $request)
    {
        $ville = new Ville();
        $villeForm = $this->createForm(AddTownType::class, $ville);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'Nouvelle ville ajoutée !');
            return $this->redirectToRoute('admin-towns');
        }

        return $this->render('admin/town/add.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }

    /**
     * Modification d'une ville
     * @Route("/town/edit/{id}", name="edit-town")
     */
    public function editTown(int $id, EntityManagerInterface $em, Request $request)
    {
        $ville = $em->getRepository(Ville::class)->find($id);
        $villeForm = $this->createForm(AddTownType::class, $ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();
            $this->addFlash('success', 'La ville a bien été modifiée !');
            return $this->redirectToRoute('admin-towns');
        }

        return $this->render("admin/town/edit.html.twig", [
            "villeForm" => $villeForm->createView()
        ]);
    }

    /**
     * Suppression d'une ville
     * @Route("/town/delete/{id}", name="delete-town")
     */
    function deleteTown(int $id, EntityManagerInterface $em, Request $request)
    {
        $ville = $em->getRepository(Ville::class)->find($id);
        $em->remove($ville);
        $em->flush();
        $this->addFlash('success', 'Ville supprimée !');
        return $this->redirectToRoute('admin-towns');
    }

    /**
     * Import d'utilisateur par fichier .csv
     * @Route("/users/import", name="admin-import-users")
     */
    public function importUsers(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $usersForm = $this->createForm(AdminAddUsersType::class);
        $usersForm->handleRequest($request);
        if ($usersForm->isSubmitted() && $usersForm->isValid()) {
            $file = $usersForm->get('file')->getData();
            if ($file) {
                $datetime = new DateTime();
                $datetime = $datetime->format('s-i-H-d-m-Y');
                $newFilename = 'import' . $datetime . '.csv';
                try {
                    $file->move(
                        $this->getParameter('import_users_directory'),
                        $newFilename
                    );
                    $readFile = fopen($this->getParameter('import_users_directory') . '/' . $newFilename, 'r');
                    $row = 0;
                    while (($line = fgetcsv($readFile)) !== FALSE) {
                        if ($row > 0) {
                            str_replace(";", ",", $line[0]);
                            $user = explode(";", $line[0]);
                            if(count($user) === 8){
                                $site = $this->getDoctrine()->getRepository(Site::class)->findOneBy(['nom' => $user[0]]);
                                if ($site) {
                                    $userToInsert = new Utilisateur();
                                    $hashed = $encoder->encodePassword($userToInsert, $user[6]);
                                    $userToInsert->setUsername($user[1]);
                                    $userToInsert->setPassword($hashed);
                                    $userToInsert->setSite($site);
                                    $userToInsert->setMail($user[5]);
                                    $userToInsert->setNom($user[2]);
                                    $userToInsert->setPrenom($user[3]);
                                    $userToInsert->setTelephone($user[4]);
                                    $userToInsert->setAdmin($user[7]);
                                    $userToInsert->setActif(true);
                                    try{
                                        $em->persist($userToInsert);
                                        $em->flush();
                                    }catch (UniqueConstraintViolationException $e)
                                    {
                                        $this->addFlash('error', 'L\'utilisateur '.$user[1].' n\'a pas pu être importé, le nom d\'utilisateur ou l\'adresse mail existe déjà !');
                                        $em = $this->getDoctrine()->resetManager();
                                    }catch (\Exception $e)
                                    {
                                        $this->addFlash('error', 'Il y a eu une erreur à l\'importation de l\'utilisateur '.$user[1].' !');
                                        $em = $this->getDoctrine()->resetManager();
                                    }
                                    $this->addFlash('error', $user[1].' importé !');
                                }else{
                                    $this->addFlash('error', 'Le site '.$user[0].' n\'existe pas !');
                                }
                            }else{
                                $this->addFlash('error', 'Merci de remplir tous les champs pour chaque utilisateur !');
                            }
                        }
                        $row++;
                    }
                    fclose($readFile);
                } catch (FileException $e) {
                }
            }
        }

        return $this->render("admin/users/add.html.twig", [
            "usersForm" => $usersForm->createView()
        ]);
    }

    /**
     * Gestion des utilisateurs
     * @Route("/users", name="admin-users")
     */
    function users(EntityManagerInterface $em)
    {
        $users = $em->getRepository(Utilisateur::class)->findAll();
        return $this->render("/admin/users/view_users.html.twig", [
            "users" => $users
        ]);
    }

    /**
     * Ajout de user
     * @Route("/users/add", name="admin-user-add")
     */
    public function addUser(EntityManagerInterface $em, Request $request)
    {
        $user = new Utilisateur();
        $usersForm = $this->createForm(AddUserAdminType::class, $user);
        $usersForm->handleRequest($request);

        if ($usersForm->isSubmitted() && $usersForm->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Nouvel utilisateur ajouté !');
            return $this->redirectToRoute('admin-users');
        }

        return $this->render('admin/users/add_user.html.twig', [
            'usersForm' => $usersForm->createView()
        ]);
    }

    /**
     * Methode pour desactiver un utilisateur
     * @Route("/user/inactive/{id}", name="admin-user-inactive")
     */
    public function setInactiveUser(int $id, EntityManagerInterface $em, Request $request)
    {
        $user = $em->getRepository(Utilisateur::class)->find($id);
        $user->setActif(false);
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Utilisateur rendu inactif !');
        return $this->redirectToRoute('admin-users');

    }

    /**
     * Suppression d'un utilisateur
     * @Route("/user/delete/{id}", name="admin-user-delete")
     */
    function deleteUser(int $id, EntityManagerInterface $em, Request $request)
    {
        $user = $em->getRepository(Utilisateur::class)->find($id);
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Utilisateur supprimé !');

        return $this->redirectToRoute('admin-users');
    }

    /**
     * Vue des groupes
     * @Route("/groups", name="admin-groups")
     */
    function groups(EntityManagerInterface $em)
    {
        $groups = $em->getRepository(Group::class)->findAll();
        $createur = $this->getUser();
        return $this->render("/admin/group/view_group.html.twig", [
            "groups" => $groups,
            "user" => $createur
        ]);
    }

    /**
     * Ajout d'un groupe
     * @Route("/groups/add", name="admin-add-group")
     */
    function addGroup(EntityManagerInterface $em, Request $request)
    {
        $group = new Group();
        $groupForm = $this->createForm(AddGroupType::class, $group);
        $groupForm->handleRequest($request);

        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $group->setCreateur($this->getUser());
            $em->persist($group);
            $em->flush();

            $this->addFlash('success', 'Nouveau groupe ajouté !');
        }

        return $this->render('admin/group/add.html.twig', [
            'groupForm' => $groupForm->createView()
        ]);
    }

    /**
     * Modification d'un groupe (disponible juste pour le créateur du groupe)
     * @Route("/group/update/{id}", name="group-edit")
     */
    function updateGroup(int $id, EntityManagerInterface $em, Request $request)
    {
        $group = $em->getRepository(Group::class)->find($id);
        $groupForm = $this->createForm(AddGroupType::class, $group);
        $groupForm->handleRequest($request);
        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $em->persist($group);
            $em->flush();
            $this->addFlash('success', 'Le groupe a bien été modifié !');
            return $this->redirectToRoute('admin-groups');
        }

        return $this->render("admin/group/add.html.twig", [
            "groupForm" => $groupForm->createView()
        ]);
    }
    /**
     * Suppression d'un groupe (disponible juste pour le créateur du groupe)
     * @Route("/group/delete/{id}", name="group-delete")
     */
    function deleteGroup(int $id, EntityManagerInterface $em, Request $request)
    {
        $group = $em->getRepository(Group::class)->find($id);
        $em->remove($group);
        $em->flush();
        $this->addFlash('success', 'Groupe supprimé !');

        return $this->redirectToRoute('admin-groups');
    }
}