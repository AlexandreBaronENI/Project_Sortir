<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Utilisateur;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    private $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', TextType::class,
                [ 'label' => 'Pseudo: ',
                    'invalid_message' => 'Le pseudo est obligatoire',
                    'required' => true,
                    'attr' => [
                        'class' => "col-sm-10 form-control"
                    ]         ])
            ->add('nom', TextType::class,
                [ 'label' => 'Nom: ',
                    'invalid_message' => 'Le nom est obligatoire',
                    'required' => true,
                    'attr' => [
                        'class' => "col-sm-10 form-control"
                    ]         ])
            ->add('prenom', TextType::class,
                [ 'label' => 'Prenom:',
                    'invalid_message' => 'Le prénom est obligatoire',
                    'required' => true,
                    'attr' => [
                        'class' => "col-sm-10 form-control"
                    ]         ])
            ->add('telephone', TextType::class,
                [ 'label' => 'Téléphone: ',
                    'invalid_message' => 'Le numéro de téléphone est obligatoire',
                    'required' => true,
                    'attr' => [
                        'class' => "col-sm-10 form-control"
                    ]         ])
            ->add('mail', EmailType::class,
                [ 'label' => 'Email: ',
                    'invalid_message' => 'Le courriel est obligatoire',
                    'required' => true,
                    'attr' => [
                        'class' => "col-sm-10 form-control"
                    ]         ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe:',
                    'attr' => [
                        'class' => "col-sm-2 col-form-label"
                    ],
                    'attr' => [
                        'class' => 'col-sm-8 form-control'
                    ]               ],
                'second_options' => [
                    'label' => 'Confirmation:',
                    'attr' => [
                        'class' => "col-sm-2 col-form-label"
                    ],
                    'attr' => [
                        'class' => 'col-sm-8 form-control'
                    ]]
            ])

            ->add('site', EntityType::class, [
                'label' => "Site de rattachement",
                'class' => Site::class,
                'choice_label' => function ($site) {
                    return $site->getNom();
                },
                'attr' => [
                    'class' => 'col-sm-10 form-control'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
