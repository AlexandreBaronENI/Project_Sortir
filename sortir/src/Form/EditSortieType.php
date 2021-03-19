<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSortieType extends AbstractType
{
    private $lieuRepository;
    private $etatRepository;

    public function __construct(LieuRepository $lieuRepository, EtatRepository $etatRepository)
    {
        $this->lieuRepository = $lieuRepository;
        $this->etatRepository = $etatRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $etat = $builder->getData()->getEtat();
        $etatManager = new Etat($this->etatRepository);

        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la sortie"
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => "Date de début de la sortie"
            ])
            ->add('dateCloture', DateType::class, [
                'label' => "Date limite d'inscription"
            ])
            ->add('nbInscriptionMax', NumberType::class, [
                'label' => "Nombre de places"
            ])
            ->add('duree', NumberType::class, [
                'label' => "Durée"
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => "Description et infos"
            ])
            ->add('lieu', EntityType::class, [
                'label' => "Lieu",
                'class' => Lieu::class,
                'choice_label' => function ($lieu) {
                    return $lieu->getNom();
                },
            ])
        ;

        if($etatManager->IsDraft($etat->getId())){
            $builder
                ->add('save', SubmitType::class, [
                    'label' => 'Enregistrer',
                    'attr' => [
                        'class' => 'btn btn-light'
                    ]
                ])
                ->add('publish', SubmitType::class, [
                    'label' => 'Publier la sortie',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ]);
        }

        if($etatManager->IsOpen($etat->getId()) || $etatManager->IsDraft($etat->getId())){
            $builder
                ->add('delete', SubmitType::class, [
                    'label' => 'Supprimer la sortie',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ])
                ->add('cancel', ResetType::class, [
                    'label' => 'Annuler',
                    'attr' => [
                        'class' => 'btn btn-danger'
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
