<?php

namespace App\Repository;

use App\Entity\BookGenre;
use App\Db\Mysql;
use App\Entity\Type;

class BookGenreRepository extends Repository
{
    public function findAllGenreBook(int $bookId): array
    {

        $query = "SELECT genre.name AS genre_name FROM book_genre AS bg JOIN genre ON bg.genre_id = genre.id WHERE bg.book_id = :book_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":book_id", $bookId,  $this->pdo::PARAM_INT);
        $stmt->execute();

        $genres = $stmt->fetchAll( $this->pdo::FETCH_ASSOC);
        $result = [];
        foreach ($genres as $genre) {
           $result[] =  $genre['genre_name'];
        }
        return $result;
    }
}
