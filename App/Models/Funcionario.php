<?php

namespace App\Models;

use App\Core\Model;

class Funcionario {

    private int $idFuncionario;
    private string $nome;
    private string $cpf;
    private string $rg;
    private string $ctps;
    private string $telefone;
    private ?string $telefoneSecundario;
    private string $email;
    private string $emailCorporativo;
    private string $senha;
    private \DateTime $dataNascimento;
    private \DateTime $dataAdmissao;
    private \DateTime $dataCadastro;
    private ?string $fotoPerfil;
    private int $idCargo;

    private function constructFunc($data): Funcionario {
        if (isset($data->idFuncionario)) {
            $this->idFuncionario = $data->idFuncionario;
        }

        if (isset($data->dataCadastro)) {
            $this->dataCadastro = new \DateTime($data->dataCadastro);
        }
        
        $this->nome = $data->nome;
        $this->cpf = $data->cpf;
        $this->rg = $data->rg;
        $this->ctps = $data->ctps;
        $this->telefone = $data->telefone;
        $this->telefoneSecundario = $data->telefoneSecundario ?? null;
        $this->email = $data->email;
        $this->emailCorporativo = $this->createEmailCorporativo();
        $this->senha = $data->senha;
        $this->dataNascimento = new \DateTime($data->dataNascimento);
        $this->dataAdmissao = new \DateTime($data->dataAdmissao);
        $this->fotoPerfil = $data->fotoPerfil ?? null;
        $this->idCargo = $data->cargo;

        return $this;
    }

    public function findAll() {
        $sql = "SELECT idFuncionario, nome, cpf, rg, ctps, telefone, telefoneSecundario, email, emailCorporativo, dataNascimento, dataAdmissao, dataCadastro, fotoPerfil, idCargo FROM tbFuncionarios";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getId($id) {
        $sql = "SELECT idFuncionario, nome, cpf, rg, ctps, telefone, telefoneSecundario, email, emailCorporativo, dataNascimento, dataAdmissao, dataCadastro, fotoPerfil, idCargo FROM tbFuncionarios WHERE idFuncionario = :id";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } else {
            return null;
        }
    }

    public function getByCredentials($data) {
        $sql = "SELECT Func.idFuncionario, Func.nome, Func.cpf, Func.rg, Func.ctps, Func.telefone, Func.telefoneSecundario, Func.email, Func.emailCorporativo, Func.dataNascimento, Func.dataAdmissao, Func.dataCadastro, Func.fotoPerfil, Carg.descricao AS cargo, Carg.permissoes, Setor.descricao as setor
        FROM tbFuncionarios Func 
        INNER JOIN tbCargos Carg
        ON Func.idCargo = Carg.idCargo
        INNER JOIN tbSetores Setor
        ON Carg.idSetor = Setor.idSetor
        WHERE idFuncionario = :matricula 
            AND senha = :senha";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':matricula', $data->matricula);
        $stmt->bindParam(':senha', $data->senha);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } else {
            return null;
        }
    }

    public function create($data) {
        $this->constructFunc($data);

        $sql = "INSERT INTO tbFuncionarios (nome, cpf, rg, ctps, telefone, telefoneSecundario, email, emailCorporativo, senha, dataNascimento, dataAdmissao, fotoPerfil, idCargo) VALUES (:nome, :cpf, :rg, :ctps, :telefone, :telefoneSecundario, :email, :emailCorporativo, :senha, :dataNascimento, :dataAdmissao, :fotoPerfil, :idCargo)";

        $dataNascimentoFormatada = $this->dataNascimento->format('Y-m-d');
        $dataAdmissaoFormatada = $this->dataAdmissao->format('Y-m-d');

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':cpf', $this->cpf);
            $stmt->bindParam(':rg', $this->rg);
            $stmt->bindParam(':ctps', $this->ctps);
            $stmt->bindParam(':telefone', $this->telefone);
            $stmt->bindParam(':telefoneSecundario', $this->telefoneSecundario);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':emailCorporativo', $this->emailCorporativo);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->bindParam(':dataNascimento', $dataNascimentoFormatada);
            $stmt->bindParam(':dataAdmissao', $dataAdmissaoFormatada);
            $stmt->bindParam(':fotoPerfil', $this->fotoPerfil);
            $stmt->bindParam(':idCargo', $this->idCargo);
            
            if ($stmt->execute()) {
                $this->idFuncionario = Model::getLastId('idFuncionario', 'tbFuncionarios');
                return $this->getId($this->idFuncionario);
            }

        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function update($id, $data) {
        $data->dataNascimento = new \DateTime($data->dataNascimento);
        $data->dataAdmissao = new \DateTime($data->dataAdmissao);
        $data->dataNascimento = $data->dataNascimento->format('Y-m-d');
        $data->dataAdmissao = $data->dataAdmissao->format('Y-m-d');

        $sql = "UPDATE tbFuncionarios SET nome = :nome, cpf = :cpf, rg = :rg, ctps = :ctps, telefone = :telefone, email = :email, senha = :senha, dataNascimento = :dataNascimento, dataAdmissao = :dataAdmissao, fotoPerfil = :fotoPerfil, idCargo = :idCargo WHERE idFuncionario = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':nome', $data->nome);
            $stmt->bindParam(':cpf', $data->cpf);
            $stmt->bindParam(':rg', $data->rg);
            $stmt->bindParam(':ctps', $data->ctps);
            $stmt->bindParam(':telefone', $data->telefone);
            $stmt->bindParam(':email', $data->email);
            $stmt->bindParam(':senha', $data->senha);
            $stmt->bindParam(':dataNascimento', $data->dataNascimento);
            $stmt->bindParam(':dataAdmissao', $data->dataAdmissao);
            $stmt->bindParam(':fotoPerfil', $data->fotoPerfil);
            $stmt->bindParam(':idCargo', $data->cargo);
            $stmt->bindParam(':id', $id);

            if($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbFuncionarios WHERE idFuncionario = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return false;
        }
    }

    private function createEmailCorporativo() {

        $nomeCompleto = explode(' ', $this->nome);
        $tamanho = count($nomeCompleto);

        $sql = "SELECT COUNT(*) FROM tbFuncionarios";
        $stmt = Model::getConn()->query($sql);
        $count = $stmt->fetchColumn(); 

        return strtolower($nomeCompleto[0] . "." . $nomeCompleto[$tamanho-1] . $count . "@carzo.com.br");
    }

}