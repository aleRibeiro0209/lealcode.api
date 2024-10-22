<?php

namespace App\Models;

use App\Core\Model;

class Venda {

    private int $idVenda;
    private string $dataVenda;
    private string $formaPagamento;
    private float $valorTotal;
    private float $descontoAplicado;
    private float $valorEntrada;
    private int $quantidadeParcelas;
    private float $valorParcela;
    private float $jurosAplicados;
    private ?string $instituicaoFinanceira;
    private float $taxasEncargosAdicionais;
    private ?string $statusDoc;
    private ?string $dataPrevisao;
    private ?string $responsavel;
    private ?string $seguradora;
    private ?string $tiposGarantia;
    private ?string $servicosExtras;
    private ?float $valorSeguro;
    private ?string $observacoes;
    private int $idFuncionario;
    private int $idVeiculo;
    private int $idCliente;

    private function contructVenda($data) {
        $this->dataVenda = $data->dataVenda;
        $this->formaPagamento = $data->formaPagamento;
        $this->valorTotal = $data->valorTotal;
        $this->descontoAplicado = isset($data->descontoAplicado) ? $data->descontoAplicado : 0;
        $this->valorEntrada = isset($data->valorEntrada) ? $data->valorEntrada : 0;
        $this->quantidadeParcelas = isset($data->quantidadeParcelas) ? $data->quantidadeParcelas : 0;
        $this->valorParcela = isset($data->valorParcela) ? $data->valorParcela : 0;
        $this->jurosAplicados = isset($data->jurosAplicados) ? $data->jurosAplicados : 0;
        $this->instituicaoFinanceira = isset($data->instituicaoFinanceira) ? $data->instituicaoFinanceira : null;
        $this->taxasEncargosAdicionais = isset($data->taxasEncargosAdicionais) ? $data->taxasEncargosAdicionais : 0;
        $this->statusDoc = isset($data->statusDoc) ? $data->statusDoc : null;
        $this->dataPrevisao = isset($data->dataPrevisao) ? $data->dataPrevisao : null;
        $this->responsavel = isset($data->responsavel) ? $data->responsavel : null;
        $this->seguradora = isset($data->seguradora) ? $data->seguradora : null;
        $this->tiposGarantia = isset($data->tiposGarantia) ? $data->tiposGarantia : null;
        $this->servicosExtras = isset($data->servicosExtras) ? $data->servicosExtras : null;
        $this->valorSeguro = isset($data->valorSeguro) ? $data->valorSeguro : null;
        $this->observacoes = isset($data->observacoes) ? $data->observacoes : null;
        $this->idFuncionario = $data->idFuncionario;
        $this->idVeiculo = $data->idVeiculo;
        $this->idCliente = $data->idCliente;
    
        return $this;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM tbVendas";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbVendas WHERE idVenda = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        return null;
    }

    public function create($data) {
        $this->contructVenda($data);

        $sql = "INSERT INTO tbVendas (dataVenda, formaPagamento, valorTotal, descontoAplicado, valorEntrada, quantidadeParcelas, valorParcela, jurosAplicados, instituicaoFinanceira, taxasEncargosAdicionais, statusDoc, dataPrevisao, responsavel, seguradora, tiposGarantia, servicosExtras, valorSeguro, observacoes, idFuncionario, idVeiculo, idCliente) VALUES (:dataVenda, :formaPagamento, :valorTotal, :descontoAplicado, :valorEntrada, :quantidadeParcelas, :valorParcela, :jurosAplicados, :instituicaoFinanceira, :taxasEncargosAdicionais, :statusDoc, :dataPrevisao, :responsavel, :seguradora, :tiposGarantia, :servicosExtras, :valorSeguro, :observacoes, :idFuncionario, :idVeiculo, :idCliente)";


        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':dataVenda', $this->dataVenda);
            $stmt->bindParam(':formaPagamento', $this->formaPagamento);
            $stmt->bindParam(':valorTotal', $this->valorTotal);
            $stmt->bindParam(':descontoAplicado', $this->descontoAplicado);
            $stmt->bindParam(':valorEntrada', $this->valorEntrada);
            $stmt->bindParam(':quantidadeParcelas', $this->quantidadeParcelas);
            $stmt->bindParam(':valorParcela', $this->valorParcela);
            $stmt->bindParam(':jurosAplicados', $this->jurosAplicados);
            $stmt->bindParam(':instituicaoFinanceira', $this->instituicaoFinanceira);
            $stmt->bindParam(':taxasEncargosAdicionais', $this->taxasEncargosAdicionais);
            $stmt->bindParam(':statusDoc', $this->statusDoc);
            $stmt->bindParam(':dataPrevisao', $this->dataPrevisao);
            $stmt->bindParam(':responsavel', $this->responsavel);
            $stmt->bindParam(':seguradora', $this->seguradora);
            $stmt->bindParam(':tiposGarantia', $this->tiposGarantia);
            $stmt->bindParam(':servicosExtras', $this->servicosExtras);
            $stmt->bindParam(':valorSeguro', $this->valorSeguro);
            $stmt->bindParam(':observacoes', $this->observacoes);
            $stmt->bindParam(':idFuncionario', $this->idFuncionario);
            $stmt->bindParam(':idVeiculo', $this->idVeiculo);
            $stmt->bindParam(':idCliente', $this->idCliente);

            if ($stmt->execute()) {
                $this->idVenda = Model::getLastId('idVenda', 'tbVendas');
                return $this->getId($this->idVenda);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo"Chegou aqui: " . $e->getMessage();
            return null;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE tbVendas SET 
            dataVenda = :dataVenda, 
            formaPagamento = :formaPagamento, 
            valorTotal = :valorTotal, 
            descontoAplicado = :descontoAplicado, 
            valorEntrada = :valorEntrada, 
            quantidadeParcelas = :quantidadeParcelas, 
            valorParcela = :valorParcela, 
            jurosAplicados = :jurosAplicados, 
            instituicaoFinanceira = :instituicaoFinanceira, 
            taxasEncargosAdicionais = :taxasEncargosAdicionais, 
            statusDoc = :statusDoc, 
            dataPrevisao = :dataPrevisao, 
            responsavel = :responsavel, 
            seguradora = :seguradora, 
            tiposGarantia = :tiposGarantia, 
            servicosExtras = :servicosExtras, 
            valorSeguro = :valorSeguro, 
            observacoes = :observacoes, 
            idFuncionario = :idFuncionario, 
            idVeiculo = :idVeiculo, 
            idCliente = :idCliente 
        WHERE idVenda = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':dataVenda', $data->dataVenda);
            $stmt->bindParam(':formaPagamento', $data->formaPagamento);
            $stmt->bindParam(':valorTotal', $data->valorTotal);
            $stmt->bindParam(':descontoAplicado', $data->descontoAplicado);
            $stmt->bindParam(':valorEntrada', $data->valorEntrada);
            $stmt->bindParam(':quantidadeParcelas', $data->quantidadeParcelas);
            $stmt->bindParam(':valorParcela', $data->valorParcela);
            $stmt->bindParam(':jurosAplicados', $data->jurosAplicados);
            $stmt->bindParam(':instituicaoFinanceira', $data->instituicaoFinanceira);
            $stmt->bindParam(':taxasEncargosAdicionais', $data->taxasEncargosAdicionais);
            $stmt->bindParam(':statusDoc', $data->statusDoc);
            $stmt->bindParam(':dataPrevisao', $data->dataPrevisao);
            $stmt->bindParam(':responsavel', $data->responsavel);
            $stmt->bindParam(':seguradora', $data->seguradora);
            $stmt->bindParam(':tiposGarantia', $data->tiposGarantia);
            $stmt->bindParam(':servicosExtras', $data->servicosExtras);
            $stmt->bindParam(':valorSeguro', $data->valorSeguro);
            $stmt->bindParam(':observacoes', $data->observacoes);
            $stmt->bindParam(':idFuncionario', $data->idFuncionario);
            $stmt->bindParam(':idVeiculo', $data->idVeiculo);
            $stmt->bindParam(':idCliente', $data->idCliente);
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