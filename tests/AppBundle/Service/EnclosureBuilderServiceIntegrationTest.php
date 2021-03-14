<?php


namespace App\Tests\AppBundle\Service;


use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Entity\Security;
use App\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    public function setUp(): void
    {
        self:self::bootKernel();

        $this->truncateEntities([
            Enclosure::class,
            Security::class,
            Dinosaur::class,
        ]);
    }

    public function testItBuildsEnclosureWithDefaultSpecifications()
    {
        self::bootKernel();
        $enclosureBuilderService = self::$kernel->getContainer()
            ->get(EnclosureBuilderService::class);

        $enclosureBuilderService->buildEnclosure();

        $entityManger = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $count = (int) $entityManger->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('Count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $count, "Amount of security systems is not the same");

        $count = (int) $entityManger->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same');
    }

    private function truncateEntities(array $entities)
    {
       $purger = new ORMPurger($this->getEntityManager());
       $purger->purge();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}