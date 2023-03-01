<?php

namespace App\Form;

use App\Entity\Terrain;
use App\Entity\Partenaire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surface')
            ->add('adresse')
            ->add('potentiel')
            ->add('id_partenaire',EntityType::class,['class'=>Partenaire::class,'choice_label'=>'cin']);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
