<?php

namespace App\Repository;

use App\Entity\AppointmentFeedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppointmentFeedback>
 *
 * @method AppointmentFeedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentFeedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentFeedback[]    findAll()
 * @method AppointmentFeedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentFeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentFeedback::class);
    }
}
