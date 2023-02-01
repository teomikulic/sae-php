<?php

namespace Models;

use BubbleORM\Attributes\Table;
use BubbleORM\Attributes\Key;
use BubbleORM\Attributes\Size;
use BubbleORM\Attributes\MysqlType;
use BubbleORM\Attributes\Ignore;
use BubbleORM\Enums\MysqlTypeEnum;

#[Table("questions")]
class Question{
    #[Ignore] private ?array $answers;
    #[Key] public int $id;

    public function __construct(
        public int $quizId,
        #[Size(120)] public string $question,
        #[MysqlType(MysqlTypeEnum::TinyInt)] public int $type,
        #[MysqlType(MysqlTypeEnum::TinyText)] public string $rightAnswer,
        public string $answersCSV
    ) {}
}