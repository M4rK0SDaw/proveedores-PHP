<?php
require_once('config/db.php');
//require_once('class/persona.php');

class vendedorModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }


    public function insert(array $vendedor): ?string //devuelvo entero o null
    {
        try {
            $sql = "INSERT INTO vendedor  VALUES (:NUMVEND,:NOMVEND ,:NOMBRECOMER ,:TELEFONO, :CALLE,:CIUDAD ,:PROVINCIA ,:COD_POSTAL);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [

                ":NUMVEND" => $vendedor["numvend"],
                ":NOMVEND" => $vendedor["nomvend"],
                ":NOMBRECOMER" => $vendedor["nombrecomer"],
                ":TELEFONO" => $vendedor["telefono"],
                ":CALLE" => $vendedor["domicilio"],
                ":CIUDAD" => $vendedor["ciudad"],
                ":PROVINCIA" => $vendedor["provincia"],
                ":COD_POSTAL" => $vendedor["cod_post"],

            ];
            $resultado = $sentencia->execute($arrayDatos);
            return ($resultado == true) ? $vendedor["numvend"] : null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return null;
        }
    }


    public function read(int $id): ?stdClass
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM vendedor WHERE NUMVEND=:id");

        $arrayDatos = [":id" => $id];

        $resultado = $sentencia->execute($arrayDatos);

        if (!$resultado) return null;

        $vendedor = $sentencia->fetch(PDO::FETCH_OBJ);

        return ($vendedor == false) ? null : $vendedor;
    }


    public function readAll(): array
    {
        $sentencia = $this->conexion->query("SELECT * from vendedor");

        $v = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $v;
    }


    public function delete(int $id): bool
    {
        $sql = "DELETE FROM vendedor WHERE numvend =:id";
        try {
            $sentencia = $this->conexion->prepare($sql);

            $resultado = $sentencia->execute([":id" => $id]);

            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function edit(int $id, array $vendedor): bool
    {
        try {
            $sql = "UPDATE vendedor SET (NUMVEND=:numvend,NOMVEND=:nomvend ,NOMBRECOMER=;nombrecomer ,TELEFONO=:telefono, CALLE=:domicilio,CIUDAD=:ciudad ,:PROVINCIA=:provincia ,COD_POSTAL=:cod_post);";
            $sql .= " WHERE NUMVEND = :id;";

            $arrayDatos = [
                ":numvend" => $vendedor["NUMVEND"],
                ":nomvend" => $vendedor["nomvend"],
                ":nombrecomer" => $vendedor["NOMBRECOMER"],
                ":telefono" => $vendedor["TELEFONO"],
                ":domicilio" => $vendedor["CALLE"],
                ":ciudad" => $vendedor["CIUDAD"],
                ":ciudad" => $vendedor["PROVINCIA"],
                ":cod_post" => $vendedor["COD_POSTAL"],
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }


    public function search(string $dato): array
    {
        
        $sentencia = $this->conexion->prepare("SELECT * FROM vendedor WHERE NUMVEND LIKE :dato");
        
        $arrayDatos = [":dato" => "%$dato%"];

        $resultado = $sentencia->execute($arrayDatos);

        if (!$resultado) return [];

        $vendedors = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        return $vendedors;

    }
}
