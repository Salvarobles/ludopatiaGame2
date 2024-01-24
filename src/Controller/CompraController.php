<?php

namespace App\Controller;

use App\Entity\Compra;
use App\Entity\Sorteo;
use App\Entity\User;
use App\Form\CompraType;
use App\Repository\CompraRepository;
use App\Repository\SorteoRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compra')]
class CompraController extends AbstractController
{
    #[Route('/', name: 'app_compra_index', methods: ['GET'])]
    public function index(CompraRepository $compraRepository): Response
    {
        return $this->render('compra/index.html.twig', [
            'compras' => $compraRepository->findAll(),
        ]);
    }

    #[Route('/participe', name: 'app_compra_participe', methods: ['GET'])]
    public function showParticipe(Compra $compra, EntityManagerInterface $entityManager, CompraRepository $compraRepository,SorteoRepository $sorteoRepository): Response
    {

        $user = $this->getUser()->getId();
        $userSaldo = $this->getUser()->getSaldoActual();
        $compras = $compraRepository->getCompraByUser($user);

        $sorteos = $sorteoRepository->sorteosFinally();
        
        foreach($sorteos as $sorteo){
            $premio = $sorteo->getPremio();
            $numsAVender = $sorteo->getNumsAVender();
            $boleto = rand(1, $numsAVender);

            foreach($compras as $compra){
                if ($compra->getSorteo()->getId() == $sorteo->getId()){
                    if  ($compra->getNumeroLoteria() == $boleto){
                        $updateSaldo = $this->getUser()->setSaldoActual($premio + $userSaldo);
                        $entityManager->persist($updateSaldo);
                    }
                }
            }

            $sorteo->setBoletoPremido($boleto);
            $entityManager->persist($sorteo);
            
        }
        

        // Flushear los cambios a la base de datos
        $entityManager->flush();
        

        return $this->render('compra/participar.html.twig', [
            'compras' => $compras,
        ]);
    }

    #[Route('/new/{id}', name: 'app_compra_new', methods: ['GET', 'POST'])]
    public function new(Sorteo $sorteo, Request $request, EntityManagerInterface $entityManager, CompraRepository $compraRepository, SorteoRepository $sorteoRepository): Response
    {


        $compra = new Compra();
        $numeros = $compraRepository->numerosLoteriaNoVendidos($sorteo);

        // $form = $this->createForm(CompraType::class, $compra, ['numeros' => $numeros]);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager->persist($compra);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_compra_index', [], Response::HTTP_SEE_OTHER);
        // }

        return $this->render('compra/verNumeros.html.twig', [
            'compra' => $compra,
            'numeros' => $numeros,
            'sorteo' => $sorteo,
        ]);
    }

    #[Route('/new/{sorteo}', name: 'app_compra_add', methods: ['GET', 'POST'])]
    public function compraBoleto(Sorteo $sorteo, Request $request, EntityManagerInterface $entityManager, CompraRepository $compraRepository, SorteoRepository $sorteoRepository): Response
    {
        $compra = new Compra();
        $numeros = $compraRepository->numerosLoteriaNoVendidos($sorteo);
        $usuario = $this->getUser(); // Obtén la instancia del usuario autenticado directamente

        return $this->render('compra/verNumeros.html.twig', [
            'compra' => $compra,
            'numeros' => $numeros,
            'sorteo' => $sorteo,
            'usuario' => $usuario,
        ]);
    }

    #[Route('/compra/{sorteo}', name: 'app_compra_boleto', methods: ['GET', 'POST'])]
    public function compraSorteo(Sorteo $sorteo, Request $request, EntityManagerInterface $entityManager, CompraRepository $compraRepository, SorteoRepository $sorteoRepository): Response
    {

        $idBoleto = $request->request->get("idBoleto");
        $fechaHoraActual = new DateTime();
        $compra = new Compra();
        $compra->setSorteo($sorteo);
        $compra->setNumeroLoteria($idBoleto);
        $compra->setUser($this->getUser());
        $compra->setFechaCompra($fechaHoraActual);

        $saldo = $this->getUser()->getSaldoActual() - $sorteo->getPrecioNumero();

        $user = $this->getUser()->setSaldoActual($saldo);
        // Persistir la entidad
        $entityManager->persist($compra);
        $entityManager->persist($user);
        // Flushear los cambios a la base de datos
        $entityManager->flush();

        return $this->render('compra/compraNumero.html.twig', [
            'compra' => $compra,
            'sorteo' => $sorteo,
        ]);
    }



    // #[Route('/new/{id}', name: 'app_compra_new', methods: ['GET', 'POST'])]
    // public function new($id, Request $request, EntityManagerInterface $entityManager, CompraRepository $compraRepository): Response
    // {
    //     $compra = new Compra();
    //     $form = $this->createForm(CompraType::class, $compra, ['sorteoId' => $id, 'compraRepository' => $compraRepository]);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($compra);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_compra_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('compra/new.html.twig', [
    //         'compra' => $compra,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_compra_show', methods: ['GET'])]
    public function show(Compra $compra): Response
    {
        return $this->render('compra/show.html.twig', [
            'compra' => $compra,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_compra_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Compra $compra, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompraType::class, $compra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('compra/edit.html.twig', [
            'compra' => $compra,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compra_delete', methods: ['POST'])]
    public function delete(Request $request, Compra $compra, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $compra->getId(), $request->request->get('_token'))) {
            $entityManager->remove($compra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_compra_index', [], Response::HTTP_SEE_OTHER);
    }
}
