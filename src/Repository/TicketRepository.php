<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }


    public function getTicketByIntervalId($min, $max) {
        $qb = $this->createQueryBuilder('p');
        $this->addIntervalId($qb, $min, $max);
        return $qb->getQuery()->getResult();
    }

    public function getStatsTicketByIntervalId($min, $max) {
        $qb = $this->createQueryBuilder('p');
        $qb->select('avg(p.id) as idMoyen, count(p.id) as nbId');
        $this->addIntervalId($qb, $min, $max);
        return $qb->getQuery()->getScalarResult();
    }

    private function addIntervalId(QueryBuilder $qb, $min, $max) {
        $qb->andWhere('p.id >= :minId')
            ->andWhere('p.id <= :maxId')
            ->setParameters([
                'minId'=> $min,
                'maxId'=> $max,
            ]);
    }
 public function findTicketByStatut($stat)
{
    $query = $this->_em->createQuery(
        'SELECT t
         FROM App\Entity\Ticket t
         WHERE t.statut = :statut
         ORDER BY t.statut
         ASC'
    )
        ->setParameter('statut', $stat)
    ;
    return $query->execute();
}


}
