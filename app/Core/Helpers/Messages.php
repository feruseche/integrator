<?php

declare(strict_types=1);

namespace Core\Helpers;

class Messages
{
    static public $errorServer     = "error al intentar utilizar el registro";
    static public $errorServerDB   = "error de comunicación con el Servidor de Base de Datos";
    static public $notRecordFound  = "registro(s) no encontrado(s)";
    static public $createRecord    = "registro creado correctamente";
    static public $canNotCreate    = "el registro no se pudo crear";
    static public $updatedRecord   = "registro actualizado correctamente";
    static public $canNotUpdate    = "el registro no se pudo actualizar, porque no se encontró el ID del registro en la base de datos";
    static public $deletedRecord   = "registro eliminado correctamente";
    static public $canNotDelete    = "el registro no se pudo eliminar";
    static public $invalidUuid     = "formato UUID inválido";
    static public $invalidName     = "Nombre inválido";
    static public $recordsFound    = "registro(s) encontrado(s)";
    static public $coincidence   = "coincidencia(s) encontrada(s)";

    
    static public function nrecordFound($numberRecords): string
    {
        return $numberRecords .' '. self::$recordsFound;
    }

    static public function nsearchFound($numberSearchs): string
    {
        return $numberSearchs .' '. self::$coincidence;
    }
}
