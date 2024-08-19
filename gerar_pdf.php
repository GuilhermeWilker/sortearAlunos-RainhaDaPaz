<?php
require('./fpdf/fpdf.php');

session_start();

if (isset($_SESSION['salas'])) {
    $salas = $_SESSION['salas'];

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Distribuicao de Alunos', 0, 1, 'C');
            $this->Ln(5);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }

        function SalaTable($salas)
        {
            foreach ($salas as $index => $sala) {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 10, 'Sala ' . ($index + 1) . ': ' . count($sala) . ' alunos', 0, 1);
                $this->SetFont('Arial', '', 12);
                foreach ($sala as $i => $aluno) {
                    $this->Cell(0, 10, utf8_decode($aluno), 0, 1); // Converta UTF-8 para ISO-8859-1
                }
                $this->Ln(5);
            }
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SalaTable($salas);
    $pdf->Output('D', 'distribuicao_alunos.pdf');
}
