<?php
namespace BubbleORM;

require_once __DIR__ ."Attributes\Ignore.php";
use BubbleORM\Attributes\Ignore;
require_once __DIR__ ."Attributes\Table.php";
use BubbleORM\Attributes\Table;
require_once __DIR__ ."Exceptions\TableInformationsNotFoundException.php";
use BubbleORM\Exceptions\TableInformationsNotFoundException;
require_once __DIR__ ."Exceptions\TableWithNoKeyException.php";
use BubbleORM\Exceptions\TableWithNoKeyException;
require_once __DIR__ ."Exceptions\TableWithoutPropertiesException.php";
use BubbleORM\Exceptions\TableWithoutPropertiesException;

use ReflectionClass;

class ModelInformationsCache{
    private static array $modelsInformations = [];

    public static function tryRegisterModelInformations(string $className) : ?ModelInformations {
        $res = null;
        
        if(!array_key_exists($className, self::$modelsInformations)){
            $typeInfos = new ReflectionClass($className);

            $attributes = $typeInfos->getAttributes(Table::class);
            if(count($attributes) !== 1)
                throw new TableInformationsNotFoundException($className);

            $tableInfos = $attributes[0]->newInstance();

            $properties = [];

            foreach($typeInfos->getProperties() as $propertyInfos){
                if(!$propertyInfos->isStatic() && $propertyInfos->isPublic() && !is_null($propertyInfos->getType())){
                    $attributes = $propertyInfos->getAttributes();
    
                    if(empty(array_filter($attributes, fn($attribute) => $attribute->getName() === Ignore::class)))
                        $properties[] = new DatabaseColumn($propertyInfos, $attributes);
                }
            }
    
            if(empty($properties))
                throw new TableWithoutPropertiesException($className);
            
            if(empty(array_filter($properties, fn($dbClmn) => $dbClmn->isPrimaryKey())))
                throw new TableWithNoKeyException($className);

            $res = new ModelInformations($className, fn() => $typeInfos->newInstanceWithoutConstructor(), $properties, $tableInfos);
            self::$modelsInformations[$className] = $res;
        }

        return $res;
    }

    public static function tryGetModelInformations(string $className, ModelInformations|null &$d = null) : bool{
        if(array_key_exists($className, self::$modelsInformations)){
            $d = self::$modelsInformations[$className];
            return true;
        }
        
        return false;
    }
}