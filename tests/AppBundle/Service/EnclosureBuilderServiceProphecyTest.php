<?php


namespace App\Tests\AppBundle\Service;


use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class EnclosureBuilderServiceProphecyTest extends TestCase
{
    private $prophet;

    public function setUp(): void
    {
        $this->prophet = new \Prophecy\Prophet;
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }
    public function testItBuildsAndPersistsEnclosure()
    {
        $entityManger = $this->prophet->prophesize(EntityManagerInterface::class);

        $entityManger->persist(Argument::type(Enclosure::class))
            ->shouldBeCalledTimes(1);

        $entityManger->flush()->shouldBeCalled();

        $dinosaurFactory = $this->prophet->prophesize(DinosaurFactory::class);

        $dinosaurFactory->growFromSpecification(Argument::type('string'))
            ->shouldBeCalledTimes(2)
            ->willReturn(new Dinosaur());

        $builder = new EnclosureBuilderService(
            $entityManger->reveal(),
            $dinosaurFactory->reveal()
        );
        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());
    }
}