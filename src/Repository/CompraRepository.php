<?php

namespace App\Repository;

use App\Entity\Compra;
use App\Entity\Sorteo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compra>
 *
 * @method Compra|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compra|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compra[]    findAll()
 * @method Compra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compra::class);
    }

    //    /**
    //     * @return Compra[] Returns an array of Compra objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Compra
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Mostrar todo los cupones venidos.

    public function numerosLoteriaNoVendidos(Sorteo $sorteo): array
    {
        // Obtener todos los números posibles del sorteo
        $todosLosNumeros = range(1, $sorteo->getNumsAVender());

        // Obtener los números de lotería vendidos
        $numerosVendidos = $this->createQueryBuilder('c')
            ->select('c.numeroLoteria')
            ->andWhere('c.sorteo = :sorteo')
            ->setParameter('sorteo', $sorteo)
            ->getQuery()
            ->getResult();

        // Extraer los números de lotería vendidos de los resultados
        $numerosVendidos = array_column($numerosVendidos, 'numeroLoteria');

        // Calcular los números de lotería no vendidos
        $numerosNoVendidos = array_diff($todosLosNumeros, $numerosVendidos);

        return $numerosNoVendidos;
    }


    public function getCompraByUser($user): array
    {

        $compras = $this->createQueryBuilder('c')
        ->andWhere('c.user = :usuario')  // Utiliza el nombre de la propiedad en la entidad, no el nombre de la columna de la base de datos
        ->setParameter('usuario', $user)
        ->getQuery()
        ->getResult();

    return $compras;
    }
    
}
