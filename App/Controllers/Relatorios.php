<?php

namespace App\Controllers;

use App\Core\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;

class Relatorios extends Controller {

    public function store() {
        $novoRelatorio = $this->getBodyRequest();

        $tipoRelatorio = "relatorio" . ucfirst($novoRelatorio->tipo);
        $relatorioModel = $this->getModel('Relatorio');
        $dadosRelatorio = $relatorioModel->$tipoRelatorio($novoRelatorio);

        $this->gerarRelatorioPDF($dadosRelatorio, $novoRelatorio);
    }

    private function gerarRelatorioPDF($dados, $objRelatorio) {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permite carregar arquivos externos como imagens
        $dompdf = new Dompdf($options);

        $caminhoImagem = $_SERVER['DOCUMENT_ROOT'] . '/assets/logo.png';
        $tipoImagem = pathinfo($caminhoImagem, PATHINFO_EXTENSION);
        $dadosImagem = file_get_contents($caminhoImagem);
        $base64Imagem = 'data:image/' . $tipoImagem . ';base64,' . base64_encode($dadosImagem);

        // Gerar conteúdo HTML do PDF
        $html = '
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");

            body {
                font-family: "Roboto", sans-serif;
                font-size: 14px;
                text-align: center;
            }

            img {
                width: 250px;
                height: 115px;
            }

            table {
                text-align: center;
            }
            
            thead {
                background-color: #df000cd9;
                color: #FFF7F6;
                font-size: 14px;
                font-weight: 600;
            }

            tbody {
                background-color: #EFF0F3;
                color: #000;
                font-size: 12px;
                font-weight: 700;
            }

            table th, table td {
                padding: 5px;
            }
        </style>
        <body>';

        $html .= '<img src="' . $base64Imagem . '" alt="Logo"/>
            <h1>Relatório de ' . ucfirst($objRelatorio->tipo) . '</h1>';
        
        if (isset($objRelatorio->dataInicial) && isset($objRelatorio->dataFinal)) {
            $html .= '<h2>Período de '  . date('d/m/Y', strtotime($objRelatorio->dataInicial)) . " a " . date('d/m/Y', strtotime($objRelatorio->dataFinal)) . '</h2>';
        }
        
        $html .= '<table border="1" width="100%" style="border-collapse: collapse;">
                <thead>
                    <tr>';

        foreach ($dados['colunas'] as $coluna) {
            $html .= '<th>' . $coluna . '</th>';
        }

        $html .= '</tr></thead><tbody>';
        
        // Itera sobre os dados e os coloca no HTML
        foreach ($dados['dadosRelatorio'] as $item) {
            $html .= '<tr>';
                    foreach ($item as $col) {
                        $html .= '<td>' . $col . '</td>';
                    }
            $html .= '</tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>';

        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);

        // Configura o papel e a orientação (A4 e retrato, por exemplo)
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza o PDF
        $dompdf->render();

        // Retorna o PDF para o navegador (pode alterar o Attachment para true se quiser forçar o download)
        $dompdf->stream('relatorio_' . $objRelatorio->tipo . '.pdf', ['Attachment' => true]);
    }
}