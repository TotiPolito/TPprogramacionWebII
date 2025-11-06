CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

DROP TABLE IF EXISTS partida;
DROP TABLE IF EXISTS respuestas;
DROP TABLE IF EXISTS preguntas;
DROP TABLE IF EXISTS categorias;
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
                          validado BOOLEAN DEFAULT FALSE,
                          latitud DOUBLE NULL,
                          longitud DOUBLE NULL,
                          token_validacion VARCHAR(64) UNIQUE,
                          puntaje INT DEFAULT 0,
                          qr VARCHAR(255) NULL
);

CREATE TABLE categorias (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            descripcion VARCHAR(25)
);

CREATE TABLE preguntas (
                           id TINYINT AUTO_INCREMENT PRIMARY KEY,
                           categoria INT,
                           dificultad VARCHAR(20) NOT NULL,
                           FOREIGN KEY (categoria) REFERENCES categorias(id)
);

ALTER TABLE preguntas
    ADD descripcion VARCHAR(200) AFTER dificultad;

ALTER TABLE preguntas
    ADD imagen VARCHAR(500) AFTER id;

ALTER TABLE preguntas
    MODIFY COLUMN descripcion VARCHAR(500) AFTER dificultad;

ALTER TABLE preguntas
ADD COLUMN vistas INT DEFAULT 0,
ADD COLUMN aciertos INT DEFAULT 0,
MODIFY COLUMN dificultad VARCHAR(20) DEFAULT 'Sin datos';

CREATE TABLE respuestas (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            idPregunta TINYINT,
                            descripcion VARCHAR(500) NOT NULL,
                            estado BOOLEAN,
                            FOREIGN KEY (idPregunta) REFERENCES preguntas(id)
);

CREATE TABLE partida (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         id_usuario INT,
                         puntaje INT,
                         fecha DATETIME,
                         FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

insert into categorias(descripcion)
Values
    ('deportes'),
    ('historia'),
    ('arte'),
    ('ciencia'),
    ('geografia'),
    ('entretenimiento');

insert into preguntas(imagen, categoria, descripcion)
Values
    ('imagenes/qatar.png', 1, 'Que seleccion fue la ganadora del mundial qatar 2022?'),
    ('imagenes/maravilla.jpg',1, 'En que año el boxeador Argentino Sergio "maravilla" Martinez salio campeon del mundo?'),
    ('imagenes/messi.jpg',1, 'Cuantos goles anoto el futbolista Lionel Messi en toda su carrera?'),
    ('imagenes/america.jpg',2, 'En que año Cristobal Colon descubrio America?'),
    ('imagenes/guerra.jpg',2, 'Cuantos años de duracion tuvo la segunda guerra mundial?'),
    ('imagenes/renacimiento.jpg',2, 'En que siglo se desarrollo el renacestismo?'),
    ('imagenes/monalisa.jpg',3, 'Quien pinto la "monalisa"?'),
    ('imagenes/album.jpg',3, 'Cual es el album mas vendido de la historia?'),
    ('imagenes/escultura.jpg',3, 'Donde se encuentra exhibida la escultura "David de Miguel Ángel"?'),
    ('imagenes/matematica.jpg',4, 'Cuanto es 4 x 6?'),
    ('imagenes/espacio.jpg',4, 'Cual es el planeta mas alejado del sol?'),
    ('imagenes/nobel.png',4, 'Quien fue el ganador del premio nobel de física del año 1921?'),
    ('imagenes/europa.jpg',5, 'Que pais no pertenece al continente Europeo?'),
    ('imagenes/pascua.jpg',5, 'A que pais pertenecen las islas de Pascua?'),
    ('imagenes/groenlandia.jpg',5, 'Cual es la capital de Groenlandia?'),
    ('imagenes/titanic.jpg',6, 'Quien es el actor que interpreta el papel de "Jack" en Titanic?'),
    ('imagenes/spiderman.jpg',6, 'Quien es el director de la famosa saga de peliculas "Spiderman" pertenecientes a Sony Pictures?'),
    ('imagenes/oscar.jpg',6, 'Quien es la persona que posee mayor cantidad de premios Oscar ganados?');


insert into respuestas(idPregunta, descripcion, estado)
Values
    (1, 'Francia', false),
    (1, 'Argentina', true),
    (1, 'Marruecos', false),
    (1, 'España', false),
    (2, '2008', true),
    (2, '2002', false),
    (2, '1990', false),
    (2, '2017', false),
    (3, '950', false),
    (3, '800', false),
    (3, '891', true),
    (3, '840', false),
    (4, '1492', true),
    (4, '1230', false),
    (4, '1600', false),
    (4, '1530', false),
    (5, '10', false),
    (5, '3', false),
    (5, '6', true),
    (5, '8', false),
    (6, 'XV al XVI', true),
    (6, 'XII al XIII', false),
    (6, 'XVII al XIX', false),
    (6, 'XI al XII', false),
    (7, 'Salvador dali', false),
    (7, 'Da Vinci', true),
    (7, 'Van Gogh', false),
    (7, 'Frida kahlo', false),
    (8, 'The Dark Side of the Moon', false),
    (8, 'A Night at the Opera', false),
    (8, 'Back in Black', false),
    (8, 'Thriller', true),
    (9, 'Museo britanico', false),
    (9, 'Luovre', false),
    (9, 'Galería de la Academia de Florencia', true),
    (9, 'Museo Metropolitano de Arte', false),
    (10, '28', false),
    (10, '16', false),
    (10, '20', false),
    (10, '24', true),
    (11, 'Marte', false),
    (11, 'Venus', false),
    (11, 'Neptuno', true),
    (11, 'Tierra', false),
    (12, 'Nicola Tesla', false),
    (12, 'Marie Curie', false),
    (12, 'Albert Einstein', true),
    (12, 'Thomas Edison', false),
    (13, 'España', false),
    (13, 'Grecia', false),
    (13, 'Camboya', true),
    (13, 'Bulgaria', false),
    (14, 'Japon', false),
    (14, 'Nueva Zelanda', false),
    (14, 'Reino Unido', false),
    (14, 'Chile', true),
    (15, 'Nuuk', true),
    (15, 'Liubliana', false),
    (15, 'Honiara', false),
    (15, 'Minsk', false),
    (16, 'christian bale', false),
    (16, 'Leonardo Di Caprio', true),
    (16, 'Zac Efron', false),
    (16, 'Brad Pitt', false),
    (17, 'Christopher Nolan', false),
    (17, 'Sam Raimi', true),
    (17, 'Quentin Tarantino', false),
    (17, 'Jon Favreau', false),
    (18, 'Jack Nicholson', false),
    (18, 'Edith Head', false),
    (18, 'Walt Disney', true),
    (18, 'Elizabeth Taylor', false);
