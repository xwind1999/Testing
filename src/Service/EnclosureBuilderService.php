<?php

namespace App\Service;

use App\Entity\Enclosure;
use App\Entity\Security;
use App\Exception\DinosaursAreRunningRampantException;
use App\Exception\NotABuffetException;
use App\Factory\DinosaurFactory;
use Doctrine\ORM\EntityManagerInterface;

class EnclosureBuilderService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DinosaurFactory
     */
    private $dinosaurFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        DinosaurFactory $dinosaurFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->dinosaurFactory = $dinosaurFactory;
    }

    /**
     * @param int $numberOfSecuritySystems
     * @param int $numberOfDinosaurs
     * @return Enclosure
     * @throws DinosaursAreRunningRampantException
     * @throws NotABuffetException
     */
    public function buildEnclosure(
        int $numberOfSecuritySystems = 1,
        int $numberOfDinosaurs = 3
    ): Enclosure
    {
        $enclosure = new Enclosure();

        $this->addSecuritySystems($numberOfSecuritySystems, $enclosure);

        $this->addDinosaurs($numberOfDinosaurs, $enclosure);

        $this->entityManager->persist($enclosure);
        $this->entityManager->flush();

        return $enclosure;
    }

    /**
     * @param int $numberOfSecuritySystems
     * @param Enclosure $enclosure
     */
    private function addSecuritySystems(int $numberOfSecuritySystems, Enclosure $enclosure)
    {
        $securityNames = ['Fence', 'Electric fence', 'Guard tower'];
        for ($i = 0; $i < $numberOfSecuritySystems; $i++) {
            $securityName = $securityNames[array_rand($securityNames)];
            $security = new Security($securityName, true, $enclosure);

            $enclosure->addSecurity($security);
        }
    }

    /**
     * @param int $numberOfDinosaurs
     * @param Enclosure $enclosure
     * @throws DinosaursAreRunningRampantException
     * @throws NotABuffetException
     */
    private function addDinosaurs(int $numberOfDinosaurs, Enclosure $enclosure)
    {
        $lengths = ['small', 'large', 'huge'];
        $diets = ['herbivore', 'carnivorous'];
        // We should not mix herbivore and carnivorous together,
        // so use the same diet for every dinosaur.
        $diet = $diets[array_rand($diets)];

        for ($i = 0; $i < $numberOfDinosaurs; $i++) {
            $length = $lengths[array_rand($lengths)];
            $specification = "{$length} {$diet} dinosaur";
            $dinosaur = $this->dinosaurFactory->growFromSpecification($specification);
            $enclosure->addDinosaur($dinosaur);
        }
    }
}
