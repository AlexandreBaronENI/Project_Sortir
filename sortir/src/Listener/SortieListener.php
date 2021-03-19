<?php


namespace App\Listener;


use App\Entity\Etat;
use App\Entity\Sortie;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class SortieListener
{
    protected $em;
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function postLoad(Sortie $sortie)
    {
        $etatManager = new Etat($this->em->getRepository(Etat::class));
        $dateFin = $sortie->getDateDebut();
        $dateFin->add(new DateInterval('P'.$sortie->getDuree().'D'));
        $today = new DateTime();
        if($etatManager->isActive($sortie->getEtat()->getId()) && $today >= $dateFin){
            $etat = $etatManager->getFinished();
            $sortie->setEtat($etat);

            $this->em->persist($sortie);
            $this->em->flush();
        }elseif ($etatManager->isOpen($sortie->getEtat()->getId()) && $today >= $sortie->getDateCloture()){
            $etat = $etatManager->getClosed();
            $sortie->setEtat($etat);

            $this->em->persist($sortie);
            $this->em->flush();
        }elseif ($etatManager->isClosed($sortie->getEtat()->getId()) && $today >= $sortie->getDateDebut()){
            $etat = $etatManager->getActive();
            $sortie->setEtat($etat);

            $this->em->persist($sortie);
            $this->em->flush();
        }
    }
}