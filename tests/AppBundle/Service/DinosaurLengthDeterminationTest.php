<?php


namespace App\Tests\AppBundle\Service;


use App\Entity\Dinosaur;
use App\Service\DinosaurLengthDetermination;
use PHPUnit\Framework\TestCase;

class DinosaurLengthDeterminationTest extends TestCase
{
    /**
     * @dataProvider getSpecLengthTests
     * @param $spec
     * @param $minExpectedSize
     * @param $maxExpectedSize
     */
    public function testItReturnsCorrectLengthRange($spec, $minExpectedSize, $maxExpectedSize)
    {
        $determination = new DinosaurLengthDetermination();
        $actualSize = $determination->getLengthFromSpecification($spec);

        $this->assertGreaterThanOrEqual($minExpectedSize, $actualSize);
        $this->assertLessThanOrEqual($maxExpectedSize, $actualSize);
    }

    public function getSpecLengthTests(): array
    {
        return [
            // specification, min length, max length
            ['large carnivorous dinosaur', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            'default response' => ['give me all the cookies!!!', 0, Dinosaur::LARGE - 1],
            ['large herbivore', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            ['huge dinosaur', Dinosaur::HUGE, 100],
            ['huge dino', Dinosaur::HUGE, 100],
            ['huge', Dinosaur::HUGE, 100],
            ['OMG', Dinosaur::HUGE, 100],
            ['gigantic', Dinosaur::HUGE, 100],
        ];
    }
}