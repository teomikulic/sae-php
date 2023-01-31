<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use BubbleORM\Enums\DatabaseCreationMode;

class DatabaseManager{
    private readonly DatabaseAccessor $db;

    public function __construct(string $host, string $user, string $pass, string $dbName,
        DatabaseCreationMode $creationMode = DatabaseCreationMode::None){
        $this->db = new DatabaseAccessor($host, $user, $pass, $dbName, $creationMode);
    }

    public function getDb() : DatabaseAccessor{
        return $this->db;
    }
    
    function createModels(){
        $this->db->createQuery(Quiz::class)->firstOrDefault();
        $this->db->createQuery(Question::class)->firstOrDefault();
        $this->db->createQuery(User::class)->firstOrDefault();
    }
}