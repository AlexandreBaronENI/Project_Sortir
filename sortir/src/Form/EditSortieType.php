<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'label' => "Nom de la sortie",
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => "Date de début de la sortie",
                'widget' => 'single_text',
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('dateCloture', DateType::class, [
                'label' => "Date limite d'inscription",
                'widget' => 'single_text',
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => "Nombre de places",
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('duree', IntegerType::class, [
                'label' => "Durée",
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => "Description et infos",
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('lieu', EntityType::class, [
                'label' => "Lieu",
                'class' => Lieu::class,
                'choice_label' => function ($lieu) {
                    return $lieu->getNom();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('ville', EntityType::class, [
                'label' => "Ville",
                'class' => Ville::class,
                'choice_label' => function ($lieu) {
                    return $lieu->getNom();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;

        if($etatManager->IsDraft($etat->getId())){
            $builder
                ->add('save', SubmitType::class, [
                    'label' => 'Enregistrer',
                    'attr' => [
                        'class' => 'btn btn-warning mx-3'
                    ]
                ])
                ->add('publish', SubmitType::class, [
                    'label' => 'Publier',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ])
                ->add('delete', SubmitType::class, [
                    'label' => 'Supprimer',
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
