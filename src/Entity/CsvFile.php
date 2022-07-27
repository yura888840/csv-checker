<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Read\CsvFileRepository")
 */
class CsvFile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private ?string $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private $csvFilename;

    public function getCsvFilename()
    {
        return $this->csvFilename;
    }

    public function setCsvFilename($csvFilename): CsvFile
    {
        $this->csvFilename = $csvFilename;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): CsvFile
    {
        $this->id = $id;

        return $this;
    }
}
