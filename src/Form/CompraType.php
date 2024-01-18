<?php

namespace App\Form;

use App\Entity\Compra;
use App\Entity\Sorteo;
use App\Entity\User;
use App\Repository\CompraRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompraType extends AbstractType
{

    // private $compraRepository;

    // public function __construct(CompraRepository $compraRepository)
    // {
    //     $this->compraRepository = $compraRepository;
    // }

    // public function buildForm(FormBuilderInterface $builder, array $options): void
    // {
    //     $sorteoId = $options['sorteoId'];
    //     $opciones = $this->compraRepository->numerosLoteriaNoVendidos($sorteoId);
        
    //     $builder
    //         ->add('numeroLoteria', ChoiceType::class, [
    //             'choices' => $opciones,
    //                 // function(\App\Repository\CompraRepository $er) {
    //                 //     return $er->createQueryBuilder('c')->orderBy('c.numeroLoteria', 'ASC');
    //                 // },
                
    //             'placeholder' => 'Selecciona una categoría', // Opcional: añade un marcador de posición
    //         ]);
    // }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sorteoId = $options['sorteoId'];

        $builder
            ->add('numeroLoteria', ChoiceType::class, [
                'choices' => 
                    function(\App\Repository\CompraRepository $er) use ($sorteoId) {
                        return $er->numerosLoteriaNoVendidos($sorteoId);
                    },
                
                'placeholder' => 'Selecciona una categoría', // Opcional: añade un marcador de posición
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compra::class,
        ]);
    }
}
