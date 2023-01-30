<?php

namespace Models;

use BubbleORM\Attributes\Key;
use BubbleORM\Attributes\Table;
use BubbleORM\Attributes\Size;
use BubbleORM\Attributes\Unsigned;
use BubbleORM\Attributes\MysqlType;
use BubbleORM\Enums\MysqlTypeEnum;

#[Table("quiz")]
class Quiz{
    #[Key, Unsigned] public int $id;

    public function __construct(
        #[Size(35)]public string $name,
        #[Size(200)] public string $description,
        #[MysqlType(MysqlTypeEnum::LongBlob)] public array $img
    ) {}
}