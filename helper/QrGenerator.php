<?php
require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';

class QrGenerator {
    public static function generarQR($contenido, $rutaSalida) {
        if (!file_exists(dirname($rutaSalida))) {
            mkdir(dirname($rutaSalida), 0777, true);
        }

        QRcode::png($contenido, $rutaSalida, QR_ECLEVEL_L, 10);
    }
}
