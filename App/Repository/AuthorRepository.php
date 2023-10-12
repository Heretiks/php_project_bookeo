<?php

namespace App\Repository;

use App\Entity\Author;
use App\Db\Mysql;
use App\Entity\Type;

class AuthorRepository extends Repository
{

    public function findOneById(int $id): Author|bool
    {

        $query = $this->pdo->prepare('SELECT * FROM author WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();
        $author = $query->fetch($this->pdo::FETCH_ASSOC);
        if ($author) {
            return Author::createAndHydrate($author);
        } else {
            return false;
        }
    }

    public function findAll(): array
    {        
        // coder cette partie
        $query = $this->pdo->prepare("SELECT * FROM author ORDER BY id ASC");
        $query->execute();
        $authors = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $authorsArray = [];

        if (!empty($authors)) {
            foreach($authors as $author) {
                $authorsArray[] = Author::createAndHydrate($author);
            }
        }
 
        return $authorsArray;
    }

    //Ajout pour le CRUD
    public function removeById(int $id)
    {
        $query = $this->pdo->prepare('DELETE FROM author WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function persist(Author $author)
    {
        
        if ($author->getId() !== null) {
                $query = $this->pdo->prepare('UPDATE author SET last_name = :last_name, first_name = :first_name,  
                                                    nickname = :nickname  WHERE id = :id'
                );
                $query->bindValue(':id', $author->getId(), $this->pdo::PARAM_INT);
           

        } else {
            $query = $this->pdo->prepare('INSERT INTO rating (last_name, first_name, nickname) 
                                                    VALUES (:last_name, :first_name, :nickname)'
            );
        }

        $query->bindValue(':last_name', $author->getLastName(), $this->pdo::PARAM_STR);
        $query->bindValue(':first_name', $author->getFirstName(), $this->pdo::PARAM_STR);
        $query->bindValue(':nickname', $author->getNickname(), $this->pdo::PARAM_STR);

        return $query->execute();
    }
}
