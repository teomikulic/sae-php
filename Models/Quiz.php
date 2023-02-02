<?php

namespace Models;

use BubbleORM\Attributes\Key;
use BubbleORM\Attributes\Table;
use BubbleORM\Attributes\Size;
use BubbleORM\Attributes\Unsigned;
use BubbleORM\Attributes\MysqlType;
use BubbleORM\Enums\MysqlTypeEnum;
use BubbleORM\Attributes\Ignore;

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
        return !is_null($this->questions);
    }

    public function setQuestions(array $questions) : void{
        $this->questions = $questions;
    }
}