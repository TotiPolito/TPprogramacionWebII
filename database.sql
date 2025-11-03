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

DROP TABLE IF EXISTS categorias;

CREATE TABLE categorias(
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          descripcion VARCHAR(25)
);

DROP TABLE IF EXISTS preguntas;

CREATE TABLE preguntas (
                            id tinyint AUTO_INCREMENT PRIMARY KEY,
                            categoria int,
                            dificultad VARCHAR(20) NOT NULL,
                            FOREIGN KEY (categoria) REFERENCES categorias(id)
);

ALTER TABLE preguntas
    ADD descripcion varchar(200) AFTER dificultad;

ALTER TABLE preguntas
    ADD imagen varchar(500) after id;

ALTER TABLE preguntas
    MODIFY COLUMN descripcion VARCHAR(500) AFTER dificultad;

DROP TABLE IF EXISTS respuestas;

CREATE TABLE respuestas(
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           idPregunta tinyint,
                           descripcion VARCHAR(50) NOT NULL,
                           estado boolean,
                           FOREIGN KEY (idPregunta) REFERENCES preguntas(id)
);

ALTER TABLE respuestas
    MODIFY COLUMN descripcion VARCHAR(500) AFTER idPregunta;

insert into categorias(descripcion)
Values
('deportes'),
('historia'),
('arte'),
('ciencia'),
('geografia'),
('entretenimiento');

insert into preguntas(imagen, categoria, dificultad, descripcion)
Values
    ('imagenes/qatar.png', 1, 'facil', 'Que seleccion fue la ganadora del mundial qatar 2022?'),
    ('imagenes/maravilla.jpg',1, 'moderada', 'En que año el boxeador Argentino Sergio "maravilla" Martinez salio campeon del mundo?'),
    ('imagenes/messi.jpg',1, 'dificil', 'Cuantos goles anoto el futbolista Lionel Messi en toda su carrera?'),
    ('imagenes/america.jpg',2, 'facil', 'En que año Cristobal Colon descubrio America?'),
    ('imagenes/guerra.jpg',2, 'moderada', 'Cuantos años de duracion tuvo la segunda guerra mundial?'),
    ('imagenes/renacimiento.jpg',2, 'dificil', 'En que siglo se desarrollo el renacestismo?'),
    ('imagenes/monalisa.jpg',3, 'facil', 'Quien pinto la "monalisa"?'),
    ('imagenes/album.jpg',3, 'moderada', 'Cual es el album mas vendido de la historia?'),
    ('imagenes/escultura.jpg',3, 'dificil', 'Donde se encuentra exhibida la escultura "David de Miguel Ángel"?'),
    ('imagenes/matematica.jpg',4, 'facil', 'Cuanto es 4 x 6?'),
    ('imagenes/espacio.jpg',4, 'moderada', 'Cual es el planeta mas alejado del sol?'),
    ('imagenes/nobel.png',4, 'dificil', 'Quien fue el ganador del premio nobel de física del año 1921?'),
    ('imagenes/europa.jpg',5, 'facil', 'Que pais no pertenece al continente Europeo?'),
    ('imagenes/pascua.jpg',5, 'moderada', 'A que pais pertenecen las islas de Pascua?'),
    ('imagenes/groenlandia.jpg',5, 'dificil', 'Cual es la capital de Groenlandia?'),
    ('imagenes/titanic.jpg',6, 'facil', 'Quien es el actor que interpreta el papel de "Jack" en Titanic?'),
    ('imagenes/spiderman.jpg',6, 'moderada', 'Quien es el director de la famosa saga de peliculas "Spiderman" pertenecientes a Sony Pictures?'),
    ('imagenes/oscar.jpg',6, 'dificil', 'Quien es la persona que posee mayor cantidad de premios Oscar ganados?');


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

ALTER TABLE usuarios
    ADD COLUMN latitud DOUBLE NULL,
ADD COLUMN longitud DOUBLE NULL;

ALTER TABLE usuarios
ADD COLUMN token_validacion VARCHAR(64) UNIQUE;

ALTER TABLE usuarios
    ADD COLUMN puntaje INT DEFAULT 0;

CREATE TABLE partida (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         id_usuario INT,
                         puntaje INT,
                         fecha DATETIME,
                         FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);