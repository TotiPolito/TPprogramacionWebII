<?php
require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

class PDFGenerator extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(139, 0, 0);
        $this->Cell(0, 12, 'Panel de Administracion - Estadisticas Generales', 0, 1, 'C');
        $this->Ln(5);

        $this->SetDrawColor(139, 0, 0);
        $this->SetLineWidth(1);
        $this->Line(10, 25, 200, 25);
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120);
        $this->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i'), 0, 0, 'L');
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'R');
    }

    function TablaEstadisticas($stats)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(139, 0, 0);
        $this->SetTextColor(255);
        $this->Cell(90, 10, 'Metrica', 1, 0, 'C', true);
        $this->Cell(90, 10, 'Valor', 1, 1, 'C', true);

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0);

        foreach ($stats as $key => $value) {
            if (is_array($value)) {
                $value = implode(' - ', $value);
            }
            $this->Cell(90, 10, ucfirst(str_replace("_", " ", $key)), 1);
            $this->Cell(90, 10, $value, 1, 1);
        }

        $this->Ln(8);
    }

    public static function generar($stats)
    {
        if (ob_get_length()) ob_end_clean();

        $pdf = new self();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        $pdf->MultiCell(0, 8, "Este reporte contiene un resumen de las estadisticas generales del juego, incluyendo usuarios, partidas y desempeno de los jugadores.", 0, 'L');
        $pdf->Ln(10);

        $pdf->TablaEstadisticas($stats);

        $pdf->SetFont('Arial', 'I', 11);
        $pdf->SetTextColor(100);
        $pdf->MultiCell(0, 8, "Gracias por utilizar el panel de administracion. Mantenga los datos actualizados para un mejor seguimiento del rendimiento del sistema.", 0, 'C');

        $pdf->Output('D', 'estadisticas_juego.pdf');
        exit;
    }
}
