CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

DROP TABLE IF EXISTS partida;
DROP TABLE IF EXISTS respuestas;
DROP TABLE IF EXISTS preguntas;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS preguntas_sugeridas;
DROP TABLE IF EXISTS preguntas_reportadas;
DROP TABLE IF EXISTS respuestas_preguntas_sugeridas;
DROP TABLE IF EXISTS estadisticas_jugador;

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

ALTER TABLE usuarios
    ADD COLUMN rol ENUM('jugador', 'editor', 'admin') NOT NULL DEFAULT 'jugador';

INSERT INTO usuarios (
    nombre_completo,
    anio_nacimiento,
    sexo,
    pais,
    ciudad,
    mail,
    usuario,
    password,
    validado,
    rol
) VALUES (
             'Editor del Sitio',
             2006,
             'Femenino',
             'Argentina',
             'San Justo',
             'editor@gmail.com',
             'editor',
             '$2y$10$kuFWF1FXR./MyRn0MLyPveTga9tVqMwjZpqjPjO2fl2QOtql36/5a',
             1,
             'editor'
         );

INSERT INTO usuarios (
    nombre_completo,
    anio_nacimiento,
    sexo,
    pais,
    ciudad,
    mail,
    usuario,
    password,
    validado,
    rol
) VALUES (
             'Administrador del Sitio',
             2000,
             'Masculino',
             'Argentina',
             'Buenos Aires',
             'admin@gmail.com',
             'admin',
             '$2y$10$PCcMBcdhssLc4Ye.JrUl6.sCSpSiPdn/aIBPx4JEkeroKqdCQuPJm',
             1,
             'admin'
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

CREATE TABLE preguntas_sugeridas (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     texto VARCHAR(255) NOT NULL,
                                     categoria_id INT NOT NULL,
                                     sugerida_por INT NOT NULL,
                                     FOREIGN KEY (sugerida_por) REFERENCES usuarios(id),
                                     FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

ALTER TABLE preguntas_sugeridas
    ADD COLUMN imagen VARCHAR(255) NULL;

CREATE TABLE preguntas_reportadas(
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     idPregunta TINYINT,
                                     idUsuario INT,
                                     motivo VARCHAR(255),
                                     FOREIGN KEY (idPregunta) REFERENCES preguntas (id),
                                     FOREIGN KEY (idUsuario) REFERENCES usuarios (id)
);

ALTER TABLE preguntas_sugeridas
    ADD aprobada BOOLEAN DEFAULT NULL AFTER sugerida_por; -- null=pendiente

CREATE TABLE respuestas_preguntas_sugeridas(
                                               id INT AUTO_INCREMENT PRIMARY KEY,
                                               idPregunta INT NOT NULL,
                                               descripcion VARCHAR(500) NOT NULL,
                                               estado BOOLEAN,
                                               FOREIGN KEY (idPregunta) REFERENCES preguntas_sugeridas(id)
);

CREATE TABLE estadisticas_jugador (
                                      id_usuario INT PRIMARY KEY,
                                      preguntas_vistas INT DEFAULT 0,
                                      aciertos INT DEFAULT 0,
                                      FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

ALTER TABLE preguntas
    ADD descripcion VARCHAR(200) AFTER dificultad;

ALTER TABLE preguntas
    ADD imagen VARCHAR(500) AFTER id;

ALTER TABLE preguntas
    MODIFY COLUMN descripcion VARCHAR(500) AFTER dificultad;

ALTER TABLE preguntas
    ADD reportada BOOLEAN DEFAULT 0;

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
    ('imagenes/maravilla.jpg',1, 'En que ano el boxeador Argentino Sergio "maravilla" Martinez salio campeon del mundo?'),
    ('imagenes/messi.jpg',1, 'Cuantos goles anoto el futbolista Lionel Messi en toda su carrera?'),
    ('imagenes/america.jpg',2, 'En que ano Cristobal Colon descubrio America?'),
    ('imagenes/guerra.jpg',2, 'Cuantos anos de duracion tuvo la segunda guerra mundial?'),
    ('imagenes/renacimiento.jpg',2, 'En que siglo se desarrollo el renacestismo?'),
    ('imagenes/monalisa.jpg',3, 'Quien pinto la "monalisa"?'),
    ('imagenes/album.jpg',3, 'Cual es el album mas vendido de la historia?'),
    ('imagenes/escultura.jpg',3, 'Donde se encuentra exhibida la escultura "David de Miguel Angel"?'),
    ('imagenes/matematica.jpg',4, 'Cuanto es 4 x 6?'),
    ('imagenes/espacio.jpg',4, 'Cual es el planeta mas alejado del sol?'),
    ('imagenes/nobel.png',4, 'Quien fue el ganador del premio nobel de fisica del ano 1921?'),
    ('imagenes/europa.jpg',5, 'Que pais no pertenece al continente Europeo?'),
    ('imagenes/pascua.jpg',5, 'A que pais pertenecen las islas de Pascua?'),
    ('imagenes/groenlandia.jpg',5, 'Cual es la capital de Groenlandia?'),
    ('imagenes/titanic.jpg',6, 'Quien es el actor que interpreta el papel de "Jack" en Titanic?'),
    ('imagenes/spiderman.jpg',6, 'Quien es el director de la famosa saga de peliculas "Spiderman" pertenecientes a Sony Pictures?'),
    ('imagenes/oscar.jpg',6, 'Quien es la persona que posee mayor cantidad de premios Oscar ganados?');

insert into preguntas(imagen, categoria, descripcion)
Values
    ('imagenes/serie.jpg', 6, 'Cual es la serie mas larga de todos los tiempos segun Guinness?'),
    ('imagenes/endgame.webp', 6, 'En que ano se estreno Avengers: Endgame?'),
    ('imagenes/adele.png', 6, 'Quien canta la cancion "Rolling in the Deep"?'),
    ('imagenes/link.jpg', 6, 'Que videojuego popular tiene un personaje llamado "Link"?'),
    ('imagenes/solar.jpg', 4, 'Cual es el planeta mas grande del sistema solar?'),
    ('imagenes/sangre.jpg', 4, 'Que tipo de sangre es considerado donante universal?'),
    ('imagenes/gas.jpeg', 4, 'Cual es el gas mas abundante en la atmosfera terrestre?'),
    ( 'imagenes/relatividad.jpg', 4, 'Quien propuso la teoria de la relatividad?'),
    ('imagenes/humano.jpg', 4, 'Que organo del cuerpo humano produce insulina?'),
    ('imagenes/eeuu.jpg' , 2, 'Quien fue el primer presidente de Estados Unidos?'),
    ( 'imagenes/guerra.jpg', 2, 'En que ano termino la Segunda Guerra Mundial?'),
    ( 'imagenes/mp.jpg', 2, 'Que civilizacion construyo Machu Picchu?'),
    ( 'imagenes/mexico.jpg', 2, 'Quien lidero la independencia de Mexico?'),
    ( 'imagenes/escritura.webp', 2, 'Cual fue la primera civilizacion en inventar la escritura?'),
    ('imagenes/rio.jpg', 5, 'Cual es el rio mas largo del mundo?'),
    ('imagenes/habitante.jpg', 5, 'Que pais tiene mas habitantes del mundo?'),
    ('imagenes/australia.png', 5, 'Cual es la capital de Australia?'),
    ('imagenes/desierto.webp', 5, 'Que desierto es el mas grande del mundo?'),
    ('imagenes/congo.jpg', 5, 'En que continente se encuentra el rio Congo?'),
    ('imagenes/futbol.jpg', 1, 'Cuantos jugadores hay en un equipo de futbol en el campo?'),
    ('imagenes/tenis.jpg', 1, 'Quien tiene mas titulos de Grand Slam en tenis masculino?'),
    ('imagenes/judo.jpg', 1, 'En que pais se origino el judo?'),
    ('imagenes/rusia.jpg', 1, 'Que pais gano la Copa Mundial de Futbol 2018?'),
    ('imagenes/f1.jpg', 1, 'Cual es la carrera de Formula 1 mas famosa de Monaco?'),
    ('imagenes/prisma.jpg', 3, 'Cuantos colores tiene un prisma clasico al descomponer la luz blanca?'),
    ('imagenes/dali.jpg', 3, 'En que periodo historico vivio Salvador Dali?');

INSERT INTO respuestas(idPregunta, descripcion, estado) VALUES
                                                            (1, 'Francia', false),
                                                            (1, 'Argentina', true),
                                                            (1, 'Marruecos', false),
                                                            (1, 'Espana', false),
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
                                                            (9, 'Galeria de la Academia de Florencia', true),
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
                                                            (13, 'Espana', false),
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
                                                            (16, 'Christian Bale', false),
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
                                                            (18, 'Elizabeth Taylor', false),
                                                            (19, 'Friends', false),
                                                            (19, 'Los Simpson', true),
                                                            (19, 'Greys Anatomy', false),
                                                            (19, 'The Big Bang Theory', false),
                                                            (20, '2018', false),
                                                            (20, '2019', true),
                                                            (20, '2020', false),
                                                            (20, '2017', false),
                                                            (21, 'Adele', true),
                                                            (21, 'Beyonce', false),
                                                            (21, 'Rihanna', false),
                                                            (21, 'Taylor Swift', false),
                                                            (22, 'Super Mario Bros', false),
                                                            (22, 'The Legend of Zelda', true),
                                                            (22, 'Final Fantasy', false),
                                                            (22, 'Halo', false),
                                                            (23, 'Marte', false),
                                                            (23, 'Jupiter', true),
                                                            (23, 'Saturno', false),
                                                            (23, 'Neptuno', false),
                                                            (24, 'A+', false),
                                                            (24, 'O-', true),
                                                            (24, 'AB+', false),
                                                            (24, 'B-', false),
                                                            (25, 'Oxigeno', false),
                                                            (25, 'Nitrogeno', true),
                                                            (25, 'Dioxido de carbono', false),
                                                            (25, 'Helio', false),
                                                            (26, 'Isaac Newton', false),
                                                            (26, 'Albert Einstein', true),
                                                            (26, 'Galileo Galilei', false),
                                                            (26, 'Nikola Tesla', false),
                                                            (27, 'Higado', false),
                                                            (27, 'Rinon', false),
                                                            (27, 'Pancreas', true),
                                                            (27, 'Estomago', false),
                                                            (28, 'Abraham Lincoln', false),
                                                            (28, 'George Washington', true),
                                                            (28, 'Thomas Jefferson', false),
                                                            (28, 'John Adams', false),
                                                            (29, '1942', false),
                                                            (29, '1945', true),
                                                            (29, '1948', false),
                                                            (29, '1939', false),
                                                            (30, 'Azteca', false),
                                                            (30, 'Maya', false),
                                                            (30, 'Inca', true),
                                                            (30, 'Olmeca', false),
                                                            (31, 'Simon Bolivar', false),
                                                            (31, 'Miguel Hidalgo', true),
                                                            (31, 'Jose de San Martin', false),
                                                            (31, 'Emiliano Zapata', false),
                                                            (32, 'Egipcia', false),
                                                            (32, 'Mesopotamica', true),
                                                            (32, 'China', false),
                                                            (32, 'Griega', false),
                                                            (33, 'Amazonas', true),
                                                            (33, 'Nilo', false),
                                                            (33, 'Yangtse', false),
                                                            (33, 'Misisipi', false),
                                                            (34, 'India', false),
                                                            (34, 'China', true),
                                                            (34, 'Estados Unidos', false),
                                                            (34, 'Indonesia', false),
                                                            (35, 'Sydney', false),
                                                            (35, 'Melbourne', false),
                                                            (35, 'Canberra', true),
                                                            (35, 'Brisbane', false),
                                                            (36, 'Gobi', false),
                                                            (36, 'Sahara', true),
                                                            (36, 'Kalahari', false),
                                                            (36, 'Atacama', false),
                                                            (37, 'Asia', false),
                                                            (37, 'Africa', true),
                                                            (37, 'America', false),
                                                            (37, 'Europa', false),
                                                            (38, '10', false),
                                                            (38, '11', true),
                                                            (38, '9', false),
                                                            (38, '12', false),
                                                            (39, 'Rafael Nadal', false),
                                                            (39, 'Novak Djokovic', true),
                                                            (39, 'Roger Federer', false),
                                                            (39, 'Pete Sampras', false),
                                                            (40, 'China', false),
                                                            (40, 'Japon', true),
                                                            (40, 'Corea del Sur', false),
                                                            (40, 'Brasil', false),
                                                            (41, 'Alemania', false),
                                                            (41, 'Brasil', false),
                                                            (41, 'Francia', true),
                                                            (41, 'Argentina', false),
                                                            (42, 'Gran Premio de Belgica', false),
                                                            (42, 'Gran Premio de Monaco', true),
                                                            (42, 'Gran Premio de Italia', false),
                                                            (42, 'Gran Premio de Espana', false),
                                                            (43, '28', false),
                                                            (43, '16', false),
                                                            (43, '20', false),
                                                            (43, '7', true),
                                                            (44, 'Siglo XV', false),
                                                            (44, 'Siglo XX', true),
                                                            (44, 'Siglo XII', false),
                                                            (44, 'Siglo XIX', false);

INSERT INTO preguntas(imagen, categoria, descripcion) VALUES
('imagenes/biathlon.jpg', 1, 'En que deporte se combinan esqui de fondo y tiro con rifle?'),
('imagenes/pelota_netball.jpg', 1, 'En que deporte se juega sin poder botar la pelota mientras caminas?'),
('imagenes/bizancio.jpg', 2, 'Que imperio tuvo su capital en Constantinopla?'),
('imagenes/piramide_social.jpg', 2, 'Como se llamaba la clase social dominante en la Edad Media europea?'),
('imagenes/caravaggio.jpg', 3, 'Que pintor es conocido por el uso extremo del claroscuro?'),
('imagenes/arquitectura_brutalista.jpg', 3, 'Como se llama el estilo arquitectonico famoso por usar hormigon crudo?'),
('imagenes/ondas.jpg', 4, 'Como se llama el fenomeno en el que una onda cambia de direccion al pasar a otro medio?'),
('imagenes/genes.jpg', 4, 'Como se llama el proceso de copia del ADN antes de la division celular?'),
('imagenes/fiordos.jpg', 5, 'En que pais se encuentran los fiordos mas famosos del mundo?'),
('imagenes/cataratas.jpg', 5, 'Que rio alimenta las Cataratas del Iguazu?'),
('imagenes/violin.jpg', 6, 'Como se llama la pieza de madera que sostiene las cuerdas del violin?'),
('imagenes/idioma_malta.jpg', 6, 'Ademas del ingles, que idioma es oficial en Malta?');

INSERT INTO respuestas(idPregunta, descripcion, estado) VALUES

(45,'Biathlon',true),(45,'Pentatlon moderno',false),(45,'Cross-country sprint',false),(45,'Nordic combined',false),
(46,'Netball',true),(46,'Handball',false),(46,'Rugby 7',false),(46,'Ultimate frisbee',false),
(47,'Imperio Bizantino',true),(47,'Imperio Carolingio',false),(47,'Imperio Otomano',false),(47,'Imperio Austrohungaro',false),
(48,'La nobleza',true),(48,'Los burgueses',false),(48,'Los siervos',false),(48,'El clero bajo',false),
(49,'Caravaggio',true),(49,'Velazquez',false),(49,'Rembrandt',false),(49,'Goya',false),
(50,'Brutalismo',true),(50,'Neoclasico',false),(50,'Barroco moderno',false),(50,'Posmodernismo organico',false),
(51,'Refraccion',true),(51,'Difraccion',false),(51,'Interferencia',false),(51,'Polarizacion',false),
(52,'Replicacion',true),(52,'Transcripcion',false),(52,'Traduccion',false),(52,'Mutacion',false),
(53,'Noruega',true),(53,'Islandia',false),(53,'Nueva Zelanda',false),(53,'Canada',false),
(54,'Rio Iguazu',true),(54,'Rio Parana',false),(54,'Rio Uruguay',false),(54,'Rio Pilcomayo',false),
(55,'El puente',true),(55,'El alma',false),(55,'El marco',false),(55,'El tirador',false),
(56,'Maltes',true),(56,'Italiano',false),(56,'Griego',false),(56,'Arabe moderno',false);
