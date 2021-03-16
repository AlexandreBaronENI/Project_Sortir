<?php


namespace App\Controller;


use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * Page de connexion
     * @Route("/home", name="home")
     */
    public function home()
    {
        $loginForm = $this->createForm(LoginFormType::class);
        return $this->render('home/loginPage.html.twig', [
            'loginForm'=>$loginForm->createView()
        ]);
    }
}