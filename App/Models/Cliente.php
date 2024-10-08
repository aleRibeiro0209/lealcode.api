<?php

namespace App\Models;

use App\Core\Model;

class Cliente {

    private int $idCliente;
    private string $cpf;
    private string $nome;
    private string $telefone;
    private string $email;
    private $dataNascimento;
    private string $cep;
    private string $complemento;
    private int $numeroResidencia;

    private function contructCliente($data) {
        $this->cpf = $data->cpf;
        $this->nome = $data->nome;
        $this->telefone = $data->telefone;
        $this->email = $data->email;
        $this->dataNascimento = $data->dataNascimento;
        $this->cep = $data->cep;
        $this->complemento = $data->complemento;
        $this->numeroResidencia = $data->numeroResidencia;

        return $this;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM tbClientes";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbClientes WHERE cpf = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        return null;
    }

    public function findId($cpf) {
        $sql = "SELECT idCliente FROM tbClientes WHERE cpf = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $cpf);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetchColumn();
        } else {
            return null;
        }
    }

    public function create($data) {
        $this->contructCliente($data);

        $sql = "INSERT INTO tbClientes (cpf, nome, telefone, email, dataNascimento, cep, complemento, numeroResidencia) 
            VALUES (:cpf, :nome, :telefone, :email, :dataNascimento, :cep, :complemento, :numeroResidencia)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':cpf', $this->cpf);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':telefone', $this->telefone);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':dataNascimento', $this->dataNascimento);
            $stmt->bindParam(':cep', $this->cep);
            $stmt->bindParam(':complemento', $this->complemento);
            $stmt->bindParam(':numeroResidencia', $this->numeroResidencia);

            if ($stmt->execute()) {
                $this->idCliente = Model::getLastId('idCliente', 'tbClientes');
                return $this;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function update($id, $data) {
        $sql =  "UPDATE tbClientes SET cpf = :cpf, nome = :nome, telefone = :telefone, email = :email, dataNascimento = :dataNascimento, cep = :cep, complemento = :complemento, numeroResidencia = :numeroResidencia WHERE idCliente = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':cpf', $data->cpf);
            $stmt->bindParam(':nome', $data->nome);
            $stmt->bindParam(':telefone', $data->telefone);
            $stmt->bindParam(':email', $data->email);
            $stmt->bindParam(':dataNascimento', $data->dataNascimento);
            $stmt->bindParam(':cep', $data->cep);
            $stmt->bindParam(':complemento', $data->complemento);
            $stmt->bindParam(':numeroResidencia', $data->numeroResidencia);
            $stmt->bindParam(':id', $id);

            if($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }
}