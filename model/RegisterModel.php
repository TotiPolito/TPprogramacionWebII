<?php
require_once("helper/MyConexion.php");
require_once __DIR__ . ("/../vendor/phpmailer/src/PHPMailer.php");
require_once __DIR__ . ("/../vendor/phpmailer/src/SMTP.php");
require_once __DIR__ . ("/../vendor/phpmailer/src/Exception.php");
require_once __DIR__ . ("/../config/config.php");

use PHPmailer\PHPMailer\PHPMailer;
use PHPmailer\PHPMailer\Exception;

class RegisterModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = MyConexion::getInstance()->getConexion();
    }

    public function usuarioExiste($usuario)
    {
        $query = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function mailExiste($mail)
    {
        $query = "SELECT * FROM usuarios WHERE mail = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function crearUsuario($data)
    {

        $token = bin2hex(random_bytes(32));

        $query = "INSERT INTO usuarios 
                  (nombre_completo, anio_nacimiento, sexo, pais, ciudad, latitud, longitud, mail, usuario, password, foto_perfil, token_validacion)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($query);

        $passwordHash = password_hash($data["password"], PASSWORD_BCRYPT);

        $stmt->bind_param(
            "sisssddsssss",
            $data["nombre_completo"],
            $data["anio_nacimiento"],
            $data["sexo"],
            $data["pais"],
            $data["ciudad"],
            $data["latitud"],
            $data["longitud"],
            $data["mail"],
            $data["usuario"],
            $passwordHash,
            $data["foto_perfil"],
            $token
        );

        if ($stmt->execute()) {
            $idUsuario = $this->conexion->insert_id;

            $sqlStats = "INSERT INTO estadisticas_jugador (id_usuario, preguntas_vistas, aciertos)
                     VALUES ($idUsuario, 0, 0)";
            $this->conexion->query($sqlStats);

            if ($this->enviarMailValidacion($data["mail"], $data["usuario"], $token)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function enviarMailValidacion($mail, $usuario, $token) {
        $mailSender = new PHPMailer(true);

        try {
            $mailSender->IsSMTP();
            $mailSender->Host = "smtp.gmail.com";
            $mailSender->SMTPAuth = true;
            $mailSender->Username = MAIL_USERNAME;
            $mailSender->Password = MAIL_PASSWORD;
            $mailSender->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;;
            $mailSender->Port = 587;

            $mailSender->setFrom('casanovarocio5@gmail.com', 'Juego Web');
            $mailSender->addAddress($mail, $usuario);
            $mailSender->isHTML(true);
            $mailSender->Subject = 'Validacion de cuenta';
            $mailSender->Body = "
            <p>Hola <strong>$usuario</strong>,</p>
            <p>Gracias por registrarte. Para activar tu cuenta, hacé clic en el siguiente enlace:</p>
            <p><a href='http://localhost/TPprogramacionWebII/index.php?controller=Validar&method=validarCuenta&token=$token'>
                Activar cuenta
            </a></p>
            <p>Si no te registraste en nuestro sitio, podés ignorar este mensaje.</p>
        ";

            $mailSender->send();
            return true;
        } catch (Exception $e) {
            error_log("Error enviando correo de validación: " . $mailSender->ErrorInfo);
            return false;
        }
    }

    public function activarCuenta($token) {
        $query = "UPDATE usuarios SET validado = TRUE, token_validacion = NULL WHERE token_validacion = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}
?>
