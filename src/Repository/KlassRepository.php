<?php

namespace App\Repository;

use App\Entity\Klass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Klass>
 *
 * @method Klass|null find($id, $lockMode = null, $lockVersion = null)
 * @method Klass|null findOneBy(array $criteria, array $orderBy = null)
 * @method Klass[]    findAll()
 * @method Klass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KlassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Klass::class);
    }
}
