<?php

namespace AppBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom du repas (Essayez d\'être le plus attirant possible dans l\'énoncé, c\'est la première chose que les utilisateurs verront) '])
            ->add('origin', ChoiceType::class, ['label' => 'Type de cuisine', 'choices'  => [
                'Marocain' => 'Marocain',
                'Français' => 'Français',
                'Autre' => 'Autre',
            ]])
            ->add('adress', TextType::class, ['label' => 'Adresse où le repas a lieu (Elle ne sera transmises qu\'aux utilisateurs que vous acceptez pour le repas)'])
            ->add('pricePerPerson', MoneyType::class, ['label' => 'Prix par personne (en €)'])
            ->add('contentMeal', CKEditorType::class, ['label' => 'Contenu du repas']) // TODO : personnaliser l'éditeur parce qu'il y a trop de trucs là
            ->add('pictures', FileType::class, ['label' => 'Images associées au repas'])
            ->add('timeEvent', DateTimeType::class, ['label' => 'Date et heure du repas']) // TODO : Restreindre les dates au futur
            ->add('numberPlaces', IntegerType::class, ['label' => 'Nombre de place'])
            ->add('delivery', CheckboxType::class, ['label' => 'Je peux livrer (+3€)', 'required' => false])
            ->add('takeAway', CheckboxType::class, ['label' => 'À emporter', 'required' => false])
            ->add('onTheSpot', CheckboxType::class, ['label' => 'Sur place', 'required' => false])
            ->add('automaticalyAcceptedRequest', CheckboxType::class, ['label' => 'Acceptez-vous automatiquement les demandes ?', 'required' => false])
            ->add('enabled', SubmitType::class, ['label' => 'Brouillon', 'attr' => ['class' => 'btn-success']]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Meal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_meal';
    }


}
