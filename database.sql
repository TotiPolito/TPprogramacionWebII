CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nombre_completo VARCHAR(100) NOT NULL,
                          anio_nacimiento INT NOT NULL,
                          sexo ENUM('Masculino', 'Femenino', 'Prefiero no cargarlo') NOT NULL,
                          pais VARCHAR(50) NOT NULL,
                          ciudad VARCHAR(50) NOT NULL,
                          mail VARCHAR(100) NOT NULL UNIQUE,
                          usuario VARCHAR(50) NOT NULL UNIQUE,
                          password VARCHAR(100) NOT NULL,
                          foto_perfil VARCHAR(255),
                          validado BOOLEAN DEFAULT FALSE
);
