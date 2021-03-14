<?php


namespace App\Factory;


use App\Entity\Dinosaur;
use App\Service\DinosaurLengthDetermination;
use Exception;

class DinosaurFactory
{
    private $lengthDetermination;

    public function __construct(DinosaurLengthDetermination $lengthDetermination)
    {
        $this->lengthDetermination = $lengthDetermination;
    }

    /**
     * @param int $length
     * @return Dinosaur
     */
    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    /**
     * @param string $genus
     * @param bool $isCarnivorous
     * @param int $length
     * @return Dinosaur
     */
    public function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);

        $dinosaur->setLength($length);

        return $dinosaur;
    }

    /**
     * @param string $specification
     * @return Dinosaur
     * @throws Exception
     */
    public function growFromSpecification(string $specification): Dinosaur
    {
        // defaults
        $codeName = 'InG-' . random_int(1, 99999);
        $length = $this->lengthDetermination->getLengthFromSpecification($specification);
        $isCarnivorous = false;

        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        return $this->createDinosaur($codeName, $isCarnivorous, $length);
    }

}