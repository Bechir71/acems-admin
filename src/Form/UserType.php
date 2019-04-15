<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\User;
use App\Entity\UFR;
use App\Entity\Post;
use App\Entity\Level;
use App\Entity\Address;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'required' => false
            ])
            ->add('room', IntegerType::class, [
                'required' => false
            ])
            ->add('ufr', EntityType::class, [
                'required' => false,
                'class' => UFR::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez l\'UFR'
            ])
            ->add('level', EntityType::class, [
                'required' => false,
                'class' => Level::class,
                'choice_label' => 'name',
                'placeholder' => "Niveau d'étude"
            ])
            ->add('address', EntityType::class, [
                'required' => false,
                'class' => Address::class,
                'choice_label' => 'name',
                'placeholder' => "Adresse ou village"
            ])
            ->add('post', EntityType::class, [
                'required' => false,
                'class' => Post::class,
                'choice_label' => 'name',
                'placeholder' => "Sélectionnez le poste"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
