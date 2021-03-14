<?php


namespace App\Tests\AppBundle\Factory;


use App\Entity\Dinosaur;
use App\Factory\DinosaurFactory;
use App\Service\DinosaurLengthDetermination;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /** @var DinosaurFactory $factory */
    private $factory;

    /**
     * @var DinosaurLengthDetermination|MockObject
     */
    private $lengthDetermination;

    public function setUp() :void
    {
        $this->lengthDetermination = $this->createMock(DinosaurLengthDetermination::class);
        $this->factory = new DinosaurFactory($this->lengthDetermination);
    }

    public function testItGrowsALargeVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertIsString($dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }

    public function testItGrowsATriceraptors()
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
    }

    public function testItGrowsABabyVelociraptor()
    {
        if (!class_exists('Nanny')) {
            $this->markTestSkipped('There is nobody to watch the baby!');
        }

        $dinosaur = $this->factory->growVelociraptor(1);

        $this->assertSame(1, $dinosaur->getLength());

    }

    /**
     * @param string $spec
     * @param bool $expectedIsCarnivorous
     * @throws Exception
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromSpecification(string $spec, bool $expectedIsCarnivorous)
    {
        $this->lengthDetermination
            ->expects($this->any())
            ->method('getLengthFromSpecification')
            ->willReturn(20);
        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Diets do not match');
        $this->assertSame(20, $dinosaur->getLength());
    }

    /**
     * @return array
     */
    public function getSpecificationTests(): array
    {
        return [
            ['large carnivorous dinosaur', true],
            ['give me all the cookies', false],
            ['large herbivore', false],
        ];
    }
}