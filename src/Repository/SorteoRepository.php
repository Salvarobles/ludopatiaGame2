<?php

namespace App\Repository;

use App\Entity\Sorteo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sorteo>
 *
 * @method Sorteo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorteo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorteo[]    findAll()
 * @method Sorteo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SorteoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sorteo::class);
    }

    //    /**
    //     * @return Sorteo[] Returns an array of Sorteo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sorteo
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function sorteosByDate()
    {
        $fechaActual = new \DateTime('now');

        return $this->createQueryBuilder('s')
            ->where('s.fechaHora >= :fechaActual')
            ->setParameter('fechaActual', $fechaActual)
            ->orderBy('s.fechaHora', 'ASC')
            ->getQuery()
            ->getResult();
    }
    // Encontrar todos los sorteos que hayan finalizado la fecha y que no tenga numero premiado.
    public function sorteosFinally()
    {
        $fechaActual = new \DateTime('now');
    
        return $this->createQueryBuilder('s')
            ->where('s.fechaHora < :fechaActual')
            ->andWhere('s.boletoPremido IS NULL') // Asumiendo que 'boleto_premiado' es el nombre del campo
            ->setParameter('fechaActual', $fechaActual)
            ->orderBy('s.fechaHora', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
