<?php

namespace App\Form;

use App\Entity\Participants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,
                [ 'label' => 'Pseudo: ',
                    'invalid_message' => 'Le pseudo est obligatoire',
                    'required' => true,
                ])
            ->add('nom', TextType::class,
                [ 'label' => 'Nom: ',
                    'invalid_message' => 'Le nom est obligatoire',
                    'required' => true,
                ])
            ->add('prenom', TextType::class,
                [ 'label' => 'Prenom',
                    'invalid_message' => 'Le prénom est obligatoire',
                    'required' => true,
                ])
            ->add('telephone', TextType::class,
                [ 'label' => 'Téléphone: ',
                    'invalid_message' => 'Le numéro de téléphone est obligatoire',
                    'required' => true,
                ])
            ->add('mail', EmailType::class,
                [ 'label' => 'Email: ',
                'invalid_message' => 'Le courriel est obligatoire',
                'required' => true,
                ])
            ->add('password', RepeatedType::class,
                [  'label' => 'Mot de passe: ',
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe est obligatoire',
                    'required' => true,
                    'first_options'  => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Confirmation')
                ])
            ->add('administrateur')
            ->add('actif')
            ->add('sitesNoSite')
            ->add('sortiesNoSortie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
