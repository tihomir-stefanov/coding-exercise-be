<?php

namespace App\Tests\Repository;

use App\Entity\Award;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AwardRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindOneByLevel(): void
    {
        /** @var Award $award */
        $award = $this->entityManager
            ->getRepository(Award::class)
            ->findOneBy(['level' => 2])
        ;

        $this->assertSame(2, $award->getLevel());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
