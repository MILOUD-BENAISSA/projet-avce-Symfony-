<?php

namespace App\Form;

use App\DTO\UserSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UserSearchType extends AbstractType
{

     /**
     * Contient la données vide de se formulaire
     */
    public UserSearch $emptyData;

    public function __construct()
    {
        $this->emptyData = new UserSearch();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('limit', IntegerType::class,
            [
                'label' => 'limite: ',
                'empty_data' => $this->emptyData->limit,
                'required' => false,
            ])
            ->add('page', IntegerType::class,
            [
                'label' => 'page:  ',
                'empty_data' => $this->emptyData->page,
                'required' => false,
            ])
            ->add('sortBy', ChoiceType::class,[
                'label'=> 'Trier Par: ',
                'choices' => [
                    'Identifiant' => 'id',
                    'Email' => 'email',
                    'Num Tel' => 'phone',

                ],
                'empty_data' => $this->emptyData->sortBy,
                    'required' => false,
            ])
            ->add('direction',  ChoiceType::class,[
                'label'=> 'Sens du tri: ',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Decroissant' => 'DESC',

                ],
                'empty_data' => $this->emptyData->direction,
                    'required' => false,
            ])

            ->add('send', SubmitType::class, [
                'label' => 'envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'data' => $this->emptyData,
        ]);
    }
}
