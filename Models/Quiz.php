<?php

namespace Models;

use BubbleORM\Attributes\Key;
use BubbleORM\Attributes\Table;
use BubbleORM\Attributes\Size;
use BubbleORM\Attributes\Unsigned;
use BubbleORM\Attributes\Ignore;
use BubbleORM\DatabaseAccessor;

#[Table("quiz")]
class Quiz{
    #[Key, Unsigned] public int $id;
    #[Ignore] private ?array $questions;

    public function __construct(
        #[Size(35)]public string $name,
        #[Size(200)] public string $description,
        public string $imgName
    ){}

    public function isAvailable() : bool{
        return isset($this->questions) && $this->questions;
    }

    public function getQuestionsCount() : int{ 
        return $this->isAvailable() ? count($this->questions) : 0;
    }

    public function getQuestion(?callable $fn = null) : ?Question{
        $questions = self::getQuestions($fn);
        return $questions && count($questions) > 0 ? $questions[0] : null;
    }

    public function getQuestions(?callable $fn = null) : array{
        return is_null($this->questions) || is_null($fn) ? $this->questions : array_values(array_filter($this->questions, $fn));
    }

    public function setQuestions(array $questions) : void{
        if(!isset($this->questions) || is_null($this->questions))
            $this->questions = $questions;
    }

    public function delete(DatabaseAccessor $db) : void{
        $db->remove($this)->removeRange($this->questions)->commit();
    }
}