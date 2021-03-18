<?php

namespace App\Form;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSortieType extends AbstractType
{
    private $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'label' => "Site",
                'required' => false,
                'class' => Site::class,
                'choice_label' => function ($site) {
                    return $site->getNom();
                },
            ])
            ->add('nom', TextType::class, [
                'label' => "Le nom de la sortie contient",
                'required' => false,
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => "Entre",
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateCloture', DateType::class, [
                'label' => "et",
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('choices', ChoiceType::class, [
                'label' => false,
                'choices' =>[
                    'Sorties dont je suis l\'organisateur' => 1,
                    'Sorties auxquelles je suis inscrit/e' => 2,
                    'Sorties auxquelles je ne suis pas inscrit/e' => 3,
                    'Sorties passées' => 4
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
