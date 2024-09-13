<?php

namespace App\Models;

use App\Core\Model;

class Veiculo {

    private int $idVeiculo;
    private string $modelo;
    private string $chassi;
    private string $placa;
    private string $renavam;
    private string $numeroMotor;
    private string $cor;
    private int $ano;
    private string $marca;
    private int $idCarroceria;

    public function constructCar($data): Veiculo {
        if (isset($data->idVeiculo)) {
            $this->idVeiculo = $data->idVeiculo;
        }
        $this->modelo = $data->modelo;
        $this->chassi = $data->chassi;
        $this->placa = $data->placa;
        $this->renavam = $data->renavam;
        $this->numeroMotor = $data->numeroMotor;
        $this->cor = $data->cor;
        $this->ano = $data->ano;
        $this->marca = $data->marca;
        $this->idCarroceria = $data->carroceria;

        return $this;
    }

    public function findAll($data): array {
        $sql = "SELECT * FROM tbVeiculos LIMIT :limite OFFSET :offset";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':limite', $data->limite, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $data->offset, \PDO::PARAM_INT);
        $stmt->execute();
        $veiculos = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $sqlCount = "SELECT COUNT(*) FROM tbVeiculos";
        $total = Model::getConn()->query($sqlCount)->fetchColumn();

        return [
            'veiculos' => $veiculos,
            'total' => $total,
            'paginaAtual' => $data->pagina,
            'itensPorPagina' => $data->limite,
            'totalPaginas' => ceil($total / $data->limite)
        ];
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbVeiculos WHERE idVeiculo = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        return null;
    }

    public function create($data): ?Veiculo {
        $this->constructCar($data);

        $sql = "INSERT INTO tbVeiculos (modelo, chassi, placa, renavam, numeroMotor, cor, ano, marca, idCarroceria) VALUES (:modelo, :chassi, :placa, :renavam, :numeroMotor, :cor, :ano, :marca, :idCarroceria)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':modelo', $this->modelo);
            $stmt->bindParam(':chassi', $this->chassi);
            $stmt->bindParam(':placa', $this->placa);
            $stmt->bindParam(':renavam', $this->renavam);
            $stmt->bindParam(':numeroMotor', $this->numeroMotor);
            $stmt->bindParam(':cor', $this->cor);
            $stmt->bindParam(':ano', $this->ano);
            $stmt->bindParam(':marca', $this->marca);
            $stmt->bindParam(':idCarroceria', $this->idCarroceria);

            if ($stmt->execute()) {
                $this->idVeiculo = Model::getLastId('idVeiculo', 'tbVeiculos');
                return $this;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Não foi possível inserir os dados do veículo: ' . $e->getMessage()]);
        }

        return null;
    }

    public function update($id, $data) {
        $sql = "UPDATE tbVeiculos SET modelo = :modelo, chassi = :chassi, placa = :placa, renavam = :renavam, numeroMotor = :numeroMotor, cor = :cor, ano = :ano, marca = :marca, idCarroceria = :idCarroceria WHERE idVeiculo = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':modelo', $data->modelo);
            $stmt->bindParam(':chassi', $data->chassi);
            $stmt->bindParam(':placa', $data->placa);
            $stmt->bindParam(':renavam', $data->renavam);
            $stmt->bindParam(':numeroMotor', $data->numeroMotor);
            $stmt->bindParam(':cor', $data->cor);
            $stmt->bindParam(':ano', $data->ano);
            $stmt->bindParam(':marca', $data->marca);
            $stmt->bindParam(':idCarroceria', $data->carroceria);
            $stmt->bindParam(':id', $id);

            if($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar o veículo: ' . $e->getMessage()]);
        }

        return null;
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbVeiculos WHERE idVeiculo = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao deletar o veiculo: ' . $e->getMessage()]);
            return false;
        }
    }

}