<?php

namespace App\Entity;


class BookGenre extends Entity{
    protected int $bookId;
    protected int $genreId;

    // Gestion BookId
    public function setBookId(int $bookId): void {
        $this->bookId = $bookId;
    }

    public function getBookId(): int {
            return $this->bookId;
        }
    

    // Gestion de GenreId 
    public function setGenreId(int $genreId): void {
        $this->genreId = $genreId;
    }

    public function getGenreId(): int {
        return $this->genreId;
    }
}
 