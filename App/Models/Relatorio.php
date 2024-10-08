<?php

namespace App\Models;

use App\Core\Model;

class Relatorio {
    public function relatorioEstoque($data) {
        $sql = "SELECT V.modelo as 'Modelo', V.placa as 'Placa', V.cor as 'Cor', V.ano as 'Ano', E.status as 'Status', E.idFuncionario as 'Matrícula do Funcionário', DATE_FORMAT(E.dataAtualizacao, '%d/%m/%Y') as 'Data de Atualização' 
                    FROM tbEstoque E 
                    INNER JOIN tbVeiculos V ON E.idVeiculo = V.idVeiculo
                    WHERE E.dataAtualizacao BETWEEN :dataInicial AND :dataFinal";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':dataInicial', $data->dataInicial);
        $stmt->bindParam(':dataFinal', $data->dataFinal);
        $stmt->execute();

        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Pegar os metadados das colunas
        $columnCount = $stmt->columnCount();
        $nomeColunas = [];

        for ($i = 0; $i < $columnCount; $i++) {
            $columnMeta = $stmt->getColumnMeta($i);
            $nomeColunas[] = $columnMeta['name'];
        }

        return ['dadosRelatorio' => $dados, 'colunas' => $nomeColunas];
    }

    public function relatorioVeiculos($data) {
        $sql = "SELECT V.modelo as Modelo, V.placa as Placa, V.cor as Cor, V.ano as Ano, M.descricao as Marca, C.descricao as Carroceria
                FROM tbVeiculos V
                INNER JOIN tbCarrocerias C ON V.idCarroceria = C.idCarroceria
                INNER JOIN tbMarcas M ON V.idMarca = M.idMarca
                ORDER BY V.ano DESC";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Pegar os metadados das colunas
        $columnCount = $stmt->columnCount();
        $nomeColunas = [];

        for ($i = 0; $i < $columnCount; $i++) {
            $columnMeta = $stmt->getColumnMeta($i);
            $nomeColunas[] = $columnMeta['name'];
        }

        return ['dadosRelatorio' => $dados, 'colunas' => $nomeColunas];
    }

    public function relatorioFuncionarios($data) {
        $sql = "SELECT Func.idFuncionario as 'Matrícula', Func.nome as 'Nome', Func.cpf as 'CPF', CONCAT(Func.telefone, ' ', Func.email) as 'Contatos', 
        DATE_FORMAT(Func.dataNascimento, '%d/%m/%Y') as 'Data de Nascimento',  DATE_FORMAT(Func.dataAdmissao, '%d/%m/%Y') as 'Data de Admissão', Setor.descricao as Setor
        FROM tbFuncionarios Func 
        INNER JOIN tbCargos Carg
        ON Func.idCargo = Carg.idCargo
        INNER JOIN tbSetores Setor
        ON Carg.idSetor = Setor.idSetor";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Pegar os metadados das colunas
        $columnCount = $stmt->columnCount();
        $nomeColunas = [];

        for ($i = 0; $i < $columnCount; $i++) {
            $columnMeta = $stmt->getColumnMeta($i);
            $nomeColunas[] = $columnMeta['name'];
        }

        return ['dadosRelatorio' => $dados, 'colunas' => $nomeColunas];
    }

    public function relatorioVendas($data) {
        $sql = "SELECT Func.idFuncionario as 'Matrícula', Func.nome as 'Nome', Func.cpf as 'CPF', CONCAT(Func.telefone, ' ', Func.email) as 'Contatos', 
        DATE_FORMAT(Func.dataNascimento, '%d/%m/%Y') as 'Data de Nascimento',  DATE_FORMAT(Func.dataAdmissao, '%d/%m/%Y') as 'Data de Admissão', Setor.descricao as Setor
        FROM tbFuncionarios Func 
        INNER JOIN tbCargos Carg
        ON Func.idCargo = Carg.idCargo
        INNER JOIN tbSetores Setor
        ON Carg.idSetor = Setor.idSetor";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Pegar os metadados das colunas
        $columnCount = $stmt->columnCount();
        $nomeColunas = [];

        for ($i = 0; $i < $columnCount; $i++) {
            $columnMeta = $stmt->getColumnMeta($i);
            $nomeColunas[] = $columnMeta['name'];
        }

        return ['dadosRelatorio' => $dados, 'colunas' => $nomeColunas];
    }

}