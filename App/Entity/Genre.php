<?php

namespace App\Entity;

class Genre extends Entity
{
    protected ?int $id = null;
    protected ?int $name = null;

    

    // Gestion pour Id
    public function getId(): ?int {
        return $this->id;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }


    // Gestion pour BookId
    public function setBookId(?string $name): void {
        $this->name = $name;
    }

    public function getBookId(): ?string {
        return $this->name;
    }
}
