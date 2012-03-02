SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


INSERT INTO RegionEducativa (id, nombre) VALUES
(1, 'Jefatura de Región Educativa N° 1'),
(2, 'Jefatura de Región Educativa N° 2'),
(3, 'Jefatura de Región Educativa N° 3'),
(4, 'Jefatura de región educativa N° 4'),
(5, 'Jefatura de Región Educativa N° 5'),
(6, 'Jefatura de región educativa N° 6'),
(7, 'Jefatura de región educativa N° 7'),
(8, 'Jefatura de Región educativa N° 8'),
(9, 'Jefatura de Región educativa N° 9'),
(10, 'Jefatura de Región educativa N° 10'),
(11, 'Jefatura de Región educativa N° 11'),
(12, 'Jefatura de Región educativa N° 12'),
(13, 'Jefatura de región educativa N° 13'),
(14, 'Jefatura de Región educativa N° 14'),
(15, 'Jefatura de Región educativa N° 15'),
(16, 'Jefatura de Región educativa N° 16'),
(17, 'Jefatura de región educativa N° 17'),
(18, 'Jefatura de Región educativa N° 18'),
(19, 'Jefatura de Región educativa N° 19'),
(20, 'Jefatura de Región educativa N° 20'),
(21, 'Jefatura de Región educativa N° 21'),
(22, 'Jefatura de Región educativa N° 22'),
(23, 'Jefatura de Región educativa N° 23'),
(24, 'Jefatura de Región educativa N° 24'),
(25, 'Jefatura de Región educativa N° 25');

INSERT INTO Distrito (id, nombre, region_id) VALUES
(1, 'Adolfo Alsina', 23),
(2, 'Alberti', 15),
(3, 'Almirante Brown', 5),
(4, 'Avellaneda', 2),
(5, 'Ayacucho', 18),
(6, 'Azul', 25),
(7, 'Bahía Blanca', 22),
(8, 'Balcarce', 20),
(9, 'Baradero', 12),
(10, 'Arrecifes', 12),
(11, 'Berazategui', 4),
(12, 'Berisso', 1),
(13, 'Bolivar', 25),
(14, 'Bragado', 15),
(15, 'Brandsen', 1),
(16, 'Campana', 11),
(17, 'Cañuelas', 10),
(18, 'Capitán Sarmiento', 12),
(19, 'Carlos Casares', 15),
(20, 'Carlos Tejedor', 16),
(21, 'Carmen de Areco', 13),
(22, 'Daireaux', 23),
(23, 'Castelli', 18),
(24, 'Colón', 13),
(25, 'Coronel Rosales', 22),
(26, 'Coronel Dorrego', 21),
(27, 'Coronel Pringles', 21),
(28, 'Coronel Suárez', 23),
(29, 'Chacabuco', 14),
(30, 'Chascomús', 17),
(31, 'Chivilcoy', 15),
(32, 'Dolores', 18),
(33, 'Ensenada', 1),
(34, 'Escobar', 11),
(35, 'Esteban Echeverría', 5),
(36, 'Exaltación de la Cruz', 11),
(37, 'Florencio Varela', 4),
(38, 'General Alvarado', 19),
(39, 'General Alvear', 24),
(40, 'General Arenales', 14),
(41, 'General Belgrano', 17),
(42, 'General Guido', 18),
(43, 'General Lamadrid', 23),
(44, 'General Las Heras', 10),
(45, 'General Lavalle', 18),
(46, 'General Madariaga', 18),
(47, 'General Paz', 17),
(48, 'General Pinto', 14),
(49, 'General Pueyrredón', 19),
(50, 'General Rodríguez', 10),
(51, 'General San Martín', 7),
(53, 'General Villegas', 16),
(54, 'Gral Viamonte', 14),
(55, 'Adolfo Gonzales Chaves', 21),
(56, 'Guaminí', 23),
(57, 'Hipolito Yrigoyen', 15),
(58, 'Benito Juárez', 21),
(59, 'Junín', 14),
(60, 'Lanús', 2),
(61, 'La Plata', 1),
(62, 'Laprida', 21),
(63, 'Las Flores', 24),
(64, 'Leandro N. Alem', 14),
(65, 'Lincoln', 14),
(66, 'Lobería', 20),
(67, 'Lobos', 24),
(68, 'Lomas de Zamora', 2),
(69, 'Luján', 10),
(70, 'Magdalena', 1),
(71, 'Maipú', 18),
(72, 'Mar Chiquita', 19),
(73, 'Marcos Paz', 10),
(74, 'La Matanza', 3),
(75, 'Mercedes', 10),
(76, 'Merlo', 8),
(77, 'Monte', 17),
(78, 'Moreno', 9),
(79, 'Morón', 8),
(80, 'Navarro', 10),
(81, 'Necochea', 20),
(82, 'Nueve de Julio', 15),
(83, 'Olavarría', 25),
(84, 'Patagones', 22),
(85, 'Pehuajó', 15),
(86, 'Pellegrini', 16),
(87, 'Pergamino', 13),
(88, 'Pila', 17),
(89, 'Pilar', 11),
(90, 'Puán', 23),
(91, 'Quilmes', 4),
(92, 'Ramallo', 12),
(93, 'Rauch', 17),
(94, 'Rivadavia', 16),
(95, 'Rojas', 13),
(96, 'Roque Pérez', 24),
(97, 'Saavedra', 23),
(98, 'Saladillo', 24),
(99, 'Salto', 13),
(100, 'Salliqueló', 16),
(101, 'San Andrés de Giles', 10),
(102, 'San Antonio de Areco', 13),
(103, 'San Cayetano', 20),
(104, 'San Fernando', 6),
(105, 'San Isidro', 6),
(106, 'San Nicolás', 12),
(107, 'San Pedro', 12),
(108, 'San Vicente', 5),
(109, 'Suipacha', 10),
(110, 'Tandil', 20),
(111, 'Tapalqué', 25),
(112, 'Tigre', 6),
(113, 'Tordillo', 18),
(114, 'Tornquist', 23),
(115, 'Trenque Lauquen', 16),
(116, 'Tres Arroyos', 21),
(117, 'Tres de Febrero', 7),
(118, 'Veinticinco de mayo', 24),
(119, 'Vicente López', 6),
(120, 'Villarino', 22),
(121, 'Zárate', 11),
(122, 'La Costa', 18),
(123, 'Pinamar', 18),
(124, 'Villa Gesell', 18),
(125, 'Monte Hermoso', 22),
(126, 'Tres Lomas', 16),
(127, 'Florentino Ameghino', 14),
(128, 'Presidente Perón', 5),
(129, 'Ezeiza', 5),
(130, 'San Miguel', 9),
(131, 'José C. Paz', 9),
(132, 'Malvinas Argentinas', 9),
(133, 'Punta Indio', 1),
(134, 'Hurlingham', 7),
(135, 'Ituzaingó', 8);

INSERT INTO Localidad (id, nombre, distrito_id) VALUES
(1, 'Carhué', 1),
(2, 'Colonia San Miguel Arcángel', 1),
(3, 'Delfín Huergo', 1),
(5, 'Esteban Agustín Gascón', 1),
(6, 'La Pala', 1),
(7, 'Maza', 1),
(8, 'Rivera', 1),
(9, 'Thames', 1),
(10, 'Yutuyaco', 1),
(11, 'Adolfo Gonzales Chaves (Est. Chaves)', 55),
(12, 'De la Garma', 55),
(13, 'Juan E. Barra', 55),
(14, 'Vásquez', 55),
(15, 'Alberti (Est. Andrés Vaccarezza)', 2),
(16, 'Coronel Seguí', 2),
(17, 'Mechita', 2),
(18, 'Pla', 2),
(19, 'Villa Grisolía (Est. Achupallas)', 2),
(20, 'Villa María', 2),
(21, 'Villa Ortiz (Est. Coronel Mom)', 2),
(22, 'Almirante Brown', 3),
(23, 'Adrogué', 3),
(24, 'Burzaco', 3),
(25, 'Claypole', 3),
(26, 'Don Orione', 3),
(27, 'Glew', 3),
(28, 'Jose Mármol', 3),
(29, 'Longchamps', 3),
(30, 'Ministro Rivadavia', 3),
(31, 'Rafael Calzada', 3),
(34, 'Arrecifes', 10),
(35, 'Todd', 10),
(36, 'Viña', 10),
(38, 'Avellaneda', 4),
(39, 'Crucesita', 4),
(40, 'Dock sud', 4),
(42, 'Piñeyro', 4),
(43, 'Sarandí', 4),
(44, 'Villa Domínico', 4),
(45, 'Wilde', 4),
(46, 'Area Reserva Cinturón Ecológico', 4),
(47, 'Ayacucho', 5),
(48, 'La Constancia', 5),
(49, 'Solanet', 5),
(50, 'Udaquiola', 5),
(51, '16 de Julio', 6),
(52, 'Ariel', 6),
(53, 'Azul', 6),
(54, 'Cacharí', 6),
(55, 'Chillar', 6),
(57, 'Bahía Blanca', 7),
(58, 'Grunbein', 7),
(59, 'Ingeniero White', 7),
(60, 'Villa Bordeau', 7),
(61, 'Villa Espora (Base Aeronaval Comandante Espora)', 7),
(62, 'Cabildo', 7),
(63, 'General Daniel Cerri (Est. General Cerri)', 7),
(64, 'Balcarce', 8),
(65, 'Los Pinos', 8),
(66, 'Napaleofú', 8),
(67, 'Ramos Otero', 8),
(68, 'San Agustín', 8),
(69, 'Villa Laguna La Brava', 8),
(70, 'Baradero', 9),
(71, 'Irineo Portela', 9),
(72, 'Santa Coloma', 9),
(73, 'Villa Alsina (Est. Alsina)', 9),
(74, 'Barker', 58),
(75, 'Benito Juárez (Est. Juárez)', 58),
(76, 'López', 58),
(77, 'Tedín Uriburu', 58),
(78, 'Villa Cacique (Est. Alfredo Fortabat)', 58),
(79, 'Berazategui', 11),
(80, 'Berazategui Oeste', 11),
(81, 'Carlos Tomas Sourigues', 11),
(82, 'Centro Agrícola El Pato', 11),
(83, 'Guillermo E. Hudson', 11),
(84, 'Juan María Gutiérrez', 11),
(85, 'Pereyra', 11),
(86, 'Plátanos', 11),
(87, 'Ranelagh', 11),
(88, 'Villa España', 11),
(90, 'Barrio Banco Provincia', 12),
(91, 'Barrio El carmen (Este)', 12),
(92, 'Barrio Universitario', 12),
(93, 'Berisso', 12),
(94, 'Los Talas', 12),
(95, 'Villa Arguello', 12),
(96, 'Villa Dolores', 12),
(97, 'Villa Independencia', 12),
(98, 'Villa Nueva', 12),
(99, 'Villa Porteña', 12),
(100, 'Villa Progreso', 12),
(101, 'Villa San Carlos', 12),
(102, 'Villa Zula', 12),
(103, 'Hale', 13),
(104, 'Juan F. Ibarra', 13),
(105, 'Mariano Unzué', 13),
(106, 'Paula', 13),
(107, 'Pirovano', 13),
(108, 'San Carlos de Bolívar (Est. Bolívar)', 13),
(109, 'Urdampilleta', 13),
(110, 'Villa Lynch Pueyrredón', 13),
(111, 'Bragado', 14),
(112, 'Comodoro Py', 14),
(113, 'General O''Brien', 14),
(114, 'Irala', 14),
(115, 'La Limpia', 14),
(116, 'Máximo Fernández (Est. Juan F. Salaberry)', 14),
(117, 'Mechita (Est. Mecha)', 14),
(118, 'Olascoaga', 14),
(119, 'Warnes', 14),
(120, 'Altamirano', 15),
(121, 'Barrio Las Golondrinas', 15),
(122, 'Barrio Los Bosquecitos', 15),
(123, 'Barrio Parque Las Acacias', 15),
(124, 'Coronel Brandsen', 15),
(125, 'Gómez', 15),
(126, 'Jeppener', 15),
(127, 'Oliden', 15),
(128, 'Samborombón', 15),
(129, 'Alto Los Cardales', 16),
(130, 'Barrio Los Pioneros', 16),
(131, 'Campana', 16),
(132, 'Lomas del Río Luján (Est. Río Luján)', 16),
(133, 'Alejandro Petión', 17),
(134, 'Barrio El Taladro', 17),
(135, 'Cañuelas', 17),
(136, 'Gobernador Udaondo', 17),
(137, 'Máximo Paz', 17),
(138, 'Barrio Belgrano', 17),
(139, 'Maximo Paz (Barrio La Torre)', 17),
(140, 'Santa Rosa', 17),
(141, 'Uribelarrea', 17),
(142, 'Vicente Casares', 17),
(143, 'Capitán Sarmiento', 18),
(144, 'La Luisa', 18),
(145, 'Bellocq', 19),
(146, 'Cadret', 19),
(147, 'Carlos Casares', 19),
(148, 'Colonia Mauricio', 19),
(149, 'Hortensia', 19),
(150, 'La Sofía', 19),
(151, 'Mauricio Hirsch', 19),
(152, 'Moctezuma', 19),
(153, 'Ordoqui', 19),
(154, 'Smith', 19),
(155, 'Carlos Tejedor', 20),
(156, 'Colonia Seré', 20),
(157, 'Curarú', 20),
(158, 'Timote', 20),
(159, 'Tres Algarrobos (Est. Cuenca)', 20),
(160, 'Carmen de Areco', 21),
(161, 'Pueblo Gouin', 21),
(162, 'Tres Sargentos', 21),
(163, 'Castelli', 23),
(164, 'Centro Guerrero', 23),
(165, 'Castilla', 29),
(166, 'Chacabuco', 29),
(167, 'Los Angeles', 29),
(168, 'O''Higgins', 29),
(169, 'Rawson', 29),
(171, 'Chascomús', 30),
(172, 'Chascomús Country Club', 30),
(173, 'Manuel J. Cobo (Est. Lezama)', 30),
(174, 'Villa Parque Girado', 30),
(175, 'Chivilcoy', 31),
(176, 'Emilio Ayarza', 31),
(177, 'Gorostiaga', 31),
(178, 'La Rica', 31),
(179, 'Moquehuá', 31),
(180, 'Ramón Biaus', 31),
(181, 'San Sebastián', 31),
(182, 'Colón', 24),
(183, 'El Arbolito', 24),
(184, 'Pearson', 24),
(185, 'Sarasa', 24),
(186, 'Aparicio', 26),
(187, 'Balneario Marisol', 26),
(188, 'Coronel Dorrego', 26),
(189, 'El Perdido (Est. José A. Guisasola)', 26),
(190, 'Faro', 26),
(191, 'Irene', 26),
(192, 'Oriente', 26),
(193, 'San Román', 26),
(194, 'Coronel Pringles (Est. Pringles)', 27),
(195, 'El Divisorio', 27),
(196, 'El Pensamiento', 27),
(197, 'Indio Rico', 27),
(198, 'Lartigau', 27),
(199, 'Bajo Hondo', 25),
(200, 'Balneario Pehuen Co', 25),
(201, 'Punta Alta (Est Almirante Solier)', 25),
(202, 'Villa del Mar', 25),
(203, 'Villa General Arias (Est. Kilómetro 638)', 25),
(204, 'Cascadas', 28),
(205, 'Coronel Suárez', 28),
(206, 'Cura Malal', 28),
(207, 'D''Orbigny', 28),
(208, 'Huanguelén', 28),
(209, 'Pasman', 28),
(210, 'San José', 28),
(211, 'Santa María', 28),
(212, 'Santa Trinidad', 28),
(213, 'Villa La Arcadia', 28),
(214, 'Andant', 22),
(215, 'Arboledas', 22),
(216, 'Daireaux', 22),
(217, 'La Larga', 22),
(218, 'La Manuela', 22),
(219, 'Salazar', 22),
(220, 'Dolores', 32),
(221, 'Sevigne', 32),
(222, 'Ensenada', 33),
(223, 'Dique Nº 1', 33),
(224, 'Ensenada - Barrio Las Casuarinas', 33),
(225, 'Isla Santiago (Oeste) Escuela y Liceo Naval', 33),
(226, 'Punta Lara', 33),
(227, 'Villa Catela', 33),
(228, 'Escobar', 34),
(229, 'Belén de Escobar', 34),
(230, 'El Cazador', 34),
(231, 'Garín', 34),
(232, 'Ingeniero Maschwitz', 34),
(234, 'Matheu', 34),
(235, 'Maquinista F. Savio Este', 34),
(236, 'Puerto Paraná', 34),
(237, 'Esteban Echeverría', 35),
(238, 'Canning', 35),
(239, 'El Jagüel', 35),
(240, 'Luis Guillón', 35),
(241, 'Monte Grande', 35),
(242, '9 de Abril', 35),
(243, 'Zona Aeropuerto Internacional Ezeiza', 35),
(244, 'Arroyo de la Cruz', 36),
(245, 'Capilla del Señor (Est. Capilla)', 36),
(246, 'Diego Gaynor', 36),
(247, 'Los Cardales', 36),
(248, 'Parada Orlando', 36),
(249, 'Parada Robles - Pavón', 36),
(250, 'El Remanso - Barrio Los Pinos', 36),
(251, 'Parada Robles', 36),
(253, 'Ezeiza', 129),
(254, 'Aeropuerto Internacional Ezeiza', 129),
(255, 'Cannig', 129),
(256, 'Carlos Spegazzini', 129),
(257, 'José María Ezeiza', 129),
(258, 'La Unión', 129),
(259, 'Tristán Suárez', 129),
(261, 'Bosques', 37),
(262, 'Estanislao Severo Zeballos', 37),
(263, 'Florencio Varela', 37),
(264, 'Gobernador Julio A. Costa', 37),
(265, 'Ingeniero Juan Allan', 37),
(266, 'Villa Brown', 37),
(267, 'Villa San Luis', 37),
(268, 'Villa Santa Rosa', 37),
(269, 'Villa Vatteone', 37),
(270, 'La Capilla', 37),
(271, 'Ameghino', 127),
(273, 'Porvenir', 127),
(274, 'Comandante Nicanor Otamendi', 38),
(275, 'Mar del Sur', 38),
(276, 'Mechongué', 38),
(277, 'Miramar', 38),
(278, 'General Alvear', 39),
(279, 'Arribeños', 40),
(280, 'Ascensión', 40),
(281, 'Estación Arenales', 40),
(282, 'Ferré', 40),
(283, 'General Arenales', 40),
(284, 'La Angelita', 40),
(285, 'La Trinidad', 40),
(286, 'General Belgrano', 41),
(287, 'Gorchs', 41),
(288, 'General Guido', 42),
(289, 'Labarden', 42),
(290, 'General La Madrid', 43),
(291, 'La Colina', 43),
(292, 'Las Martinetas', 43),
(293, 'Líbano', 43),
(294, 'Pontaut', 43),
(295, 'General Hornos', 44),
(296, 'General Las Heras (Est. Las Heras)', 44),
(297, 'La Choza', 44),
(298, 'Lozano', 44),
(299, 'Plomer', 44),
(300, 'Villars', 44),
(301, 'Chacras de San Clemente', 45),
(302, 'General Lavalle', 45),
(303, 'Pavón', 45),
(305, 'Barrio Kennedy', 46),
(306, 'General Juan Madariaga', 46),
(307, 'Barrio Río Salado', 47),
(308, 'Loma Verde', 47),
(309, 'Ranchos', 47),
(310, 'Villanueva (Ap. Río Salado)', 47),
(311, 'Colonia San Ricardo (Est. Iriarte)', 48),
(312, 'General Pinto', 48),
(313, 'Germania (Est. Mayor José Orellano)', 48),
(314, 'Villa Francia (Est. Coronel Granada)', 48),
(315, 'Villa Roth (Est. Ingeniero Balbín)', 48),
(316, 'Barrio Colinas Verdes', 49),
(317, 'Barrio El Boquerón', 49),
(318, 'Barrio El Casal', 49),
(319, 'Barrio El Coyunco', 49),
(320, 'Barrio La Gloria', 49),
(321, 'Barrio Santa Paula', 49),
(322, 'Batán', 49),
(323, 'Chapadmalal', 49),
(324, 'El Marquesado', 49),
(325, 'Estación Chapadmalal', 49),
(327, 'Camet', 49),
(328, 'Estación Camet', 49),
(329, 'Mar del Plata', 49),
(330, 'Punta Mogotes', 49),
(331, 'Sierra de los Padres', 49),
(332, 'Barrio Ruta 24 Kilómetro 10', 50),
(333, 'Country Club Bosque Real - Barrio Morabo', 50),
(334, 'General Rodríguez', 50),
(335, 'General San Martín', 51),
(336, 'Barrio Parque General San Martín', 51),
(337, 'Billinghurst', 51),
(338, 'Ciudad del Libertador General San Martín', 51),
(339, 'Ciudad Jardín El Libertador', 51),
(340, 'Villa Ayacucho', 51),
(341, 'Villa Ballester', 51),
(342, 'Villa Bernardo Monteagudo', 51),
(343, 'Villa Chacabuco', 51),
(344, 'Villa Coronel José M. Zapiola', 51),
(345, 'Villa General Antonio J. de Sucre', 51),
(346, 'Villa General Eugenio Necochea', 51),
(347, 'Villa General José Tomás Guido', 51),
(348, 'Villa General Juan G. Las Heras', 51),
(349, 'Villa Godoy Cruz', 51),
(350, 'Villa Granaderos de San Martín', 51),
(351, 'Villa Gregoria Matorras', 51),
(352, 'Villa José León Suárez', 51),
(353, 'Villa Juan Martín de Pueyrredón', 51),
(354, 'Villa Libertad', 51),
(355, 'Villa Lynch', 51),
(356, 'Villa Maipú', 51),
(357, 'Va. Ma. Irene de los Remedios de Escalada', 51),
(358, 'Villa Marqués Alejandro María de Aguado', 51),
(359, 'Villa Parque Presidente Figueroa Alcorta', 51),
(360, 'Villa Parque San Lorenzo', 51),
(361, 'Villa San Andrés', 51),
(362, 'Villa Yapeyú', 51),
(363, 'Baigorrita', 53),
(364, 'La Delfina', 53),
(365, 'Los Toldos', 53),
(366, 'San Emilio', 53),
(367, 'Zavalía', 53),
(368, 'Banderaló', 54),
(369, 'Cañada Seca', 54),
(370, 'Coronel Charlone', 54),
(371, 'Emilio V. Bunge', 54),
(372, 'General Villegas (Est. Villegas)', 54),
(373, 'Massey (Est. Elordi)', 54),
(374, 'Pichincha', 54),
(375, 'Piedritas', 54),
(376, 'Santa Eleodora', 54),
(377, 'Santa Regina', 54),
(378, 'Villa Saboya', 54),
(379, 'Villa Sauze', 54),
(380, 'Arroyo Venado', 56),
(381, 'Casbas', 56),
(382, 'Garré', 56),
(383, 'Guaminí', 56),
(384, 'Laguna Alsina (Est. Bonifacio)', 56),
(385, 'Henderson', 57),
(386, 'Herrera Vegas', 57),
(388, 'Hurlingham', 134),
(389, 'Villa Santos Tesei', 134),
(390, 'William C. Morris', 134),
(391, 'Ituzaingó', 135),
(392, 'Ituzaingó Centro', 135),
(393, 'Ituzaingó Sur', 135),
(394, 'Villa Gobernador Udaondo', 135),
(397, 'José C. Paz', 131),
(399, 'Agustín Roca', 59),
(400, 'Agustina', 59),
(401, 'Balneario Laguna de Gómez', 59),
(402, 'Fortín Tiburcio', 59),
(403, 'Junín', 59),
(404, 'Laplacette', 59),
(405, 'Morse', 59),
(406, 'Saforcada', 59),
(407, 'Las Toninas', 122),
(408, 'Mar de Ajó - San Bernardo', 122),
(409, 'Aguas Verdes - Costa Azul', 122),
(410, 'Lucila del Mar - Los Tamariscos', 122),
(411, 'Mar de Ajó - Nueva Atlantis', 122),
(412, 'Mar de Ajó Norte', 122),
(413, 'San Bernardo', 122),
(414, 'San Clemente del Tuyú', 122),
(415, 'Santa Teresita - Mar del Tuyú', 122),
(416, 'Mar del Tuyú - Costa del Este', 122),
(417, 'Santa Teresita', 122),
(418, 'La Matanza', 74),
(419, 'Aldo Bonzi', 74),
(420, 'Ciudad Evita', 74),
(421, 'González Catán', 74),
(422, 'Gregorio de Laferrere', 74),
(423, 'Isidro Casanova', 74),
(424, 'La Tablada', 74),
(425, 'Lomas del Mirador', 74),
(426, 'Rafael Castillo', 74),
(427, 'Ramos Mejía', 74),
(428, 'San Justo', 74),
(429, 'Tapiales', 74),
(430, '20 de Junio', 74),
(431, 'Villa Eduardo Madero', 74),
(432, 'Villa Luzuriaga', 74),
(433, 'Virrey del Pino', 74),
(434, 'Country Club El Rodeo', 61),
(435, 'Ignacio Correas', 61),
(437, 'Abasto', 61),
(438, 'Angel Etcheverry', 61),
(439, 'Arana', 61),
(440, 'Arturo Seguí', 61),
(441, 'Barrio El Carmen (Oeste)', 61),
(442, 'Barrio Gambier', 61),
(443, 'Barrio Las Malvinas', 61),
(444, 'Barrio Las Quintas', 61),
(445, 'City Bell - Country Grand Bell', 61),
(446, 'El Retiro', 61),
(447, 'Joaquín Gorina', 61),
(448, 'José Hernández', 61),
(449, 'José Melchor Romero', 61),
(450, 'La Cumbre', 61),
(451, 'La Plata', 61),
(452, 'Lisandro Olmos - Cárcel de Olmos', 61),
(453, 'Los Hornos', 61),
(454, 'Manuel B. Gonnet', 61),
(455, 'Ringuelet', 61),
(456, 'Rufino de Elizalde', 61),
(457, 'Tolosa', 61),
(458, 'Transradio', 61),
(459, 'Villa Elisa', 61),
(460, 'Villa Elvira', 61),
(461, 'Villa Garibaldi', 61),
(462, 'Villa Montoro', 61),
(463, 'Villa Parque Sicardi', 61),
(464, 'Lomas de Copello', 61),
(465, 'Ruta Sol', 61),
(466, 'Lanus', 60),
(467, 'Gerli', 60),
(468, 'Lanús Este', 60),
(469, 'Lanús Oeste', 60),
(470, 'Monte Chingolo', 60),
(471, 'Remedios de Escalada de San Martín', 60),
(472, 'Valentín Alsina', 60),
(473, 'Laprida', 62),
(474, 'Pueblo Nuevo', 62),
(475, 'Pueblo San Jorge', 62),
(476, 'Coronel Boerr', 63),
(477, 'El Trigo', 63),
(478, 'Las Flores', 63),
(479, 'Pardo', 63),
(480, 'Alberdi Viejo', 64),
(481, 'El Dorado', 64),
(482, 'Fortín Acha', 64),
(483, 'Juan Bautista Alberdi (Est. Alberdi)', 64),
(484, 'Leandro N. Alem', 64),
(485, 'Vedia', 64),
(486, 'Arenaza', 65),
(487, 'Bayauca', 65),
(488, 'Bermúdez', 65),
(489, 'Carlos Salas', 65),
(490, 'Coronel Martínez de Hoz (Ap. Kilómetro 322)', 65),
(491, 'El Triunfo', 65),
(492, 'Las Toscas', 65),
(493, 'Lincoln', 65),
(494, 'Pasteur', 65),
(495, 'Roberts', 65),
(496, 'Arenas Verdes', 66),
(497, 'Licenciado Matienzo', 66),
(498, 'Lobería', 66),
(499, 'Pieres', 66),
(500, 'San Manuel', 66),
(501, 'Tamangueyú', 66),
(502, 'Antonio Carboni', 67),
(503, 'Elvira', 67),
(504, 'Laguna de Lobos', 67),
(505, 'Lobos', 67),
(506, 'Salvador María', 67),
(508, 'Banfield', 68),
(509, 'Lavallol', 68),
(510, 'Lomas de Zamora', 68),
(511, 'Temperley', 68),
(512, 'Turdera', 68),
(513, 'Villa Centenario', 68),
(514, 'Villa Fiorito', 68),
(515, 'Barrio Las Casuarinas', 69),
(516, 'Carlos Keen', 69),
(517, 'Cortines', 69),
(518, 'José María Jáuregui', 69),
(519, 'Villa Flandria Norte', 69),
(520, 'Villa Flandria Sur', 69),
(521, 'Lezica y Torrezuri', 69),
(522, 'Luján', 69),
(523, 'Olivera', 69),
(524, 'Open Door', 69),
(525, 'Country Club Las Praderas', 69),
(526, 'Open Door - Colonia Dr. Domingo Cabred', 69),
(527, 'Torres', 69),
(528, 'Atalaya', 70),
(529, 'General Mansilla (Est. Bartolomé Bavio)', 70),
(530, 'Los Naranjos', 70),
(531, 'Magdalena', 70),
(532, 'Roberto J. Payró', 70),
(533, 'Vieytes', 70),
(534, 'Las Armas', 71),
(535, 'Maipú', 71),
(536, 'Santo Domingo', 71),
(538, 'Area de Promoción El Triángulo', 132),
(539, 'Grand Bourg', 132),
(540, 'Ingeniero Adolfo Sourdeaux', 132),
(541, 'Ingeniero Pablo Nogués', 132),
(542, 'Los Polvorines', 132),
(543, 'Malvinas Argentinas', 132),
(544, 'Tortuguitas', 132),
(545, 'Villa de Mayo', 132),
(546, 'Coronel Vidal', 72),
(547, 'General Pirán', 72),
(548, 'La Armonía', 72),
(549, 'Mar Chiquita', 72),
(551, 'La Baliza', 72),
(552, 'La Caleta', 72),
(553, 'Mar de Cobo', 72),
(555, 'Atlántida', 72),
(556, 'Camet Norte', 72),
(557, 'Frente Mar', 72),
(558, 'Playa Dorada', 72),
(559, 'Santa Clara del Mar', 72),
(560, 'Santa Elena', 72),
(561, 'Vivoratá', 72),
(562, 'Barrio Santa Rosa', 73),
(564, 'Bo. Lisandro de la Torre y Santa Marta', 73),
(565, 'Marcos Paz', 73),
(566, 'Gowland', 75),
(567, 'Mercedes', 75),
(568, 'Tomás Jofré', 75),
(570, 'Libertad', 76),
(571, 'Mariano Acosta', 76),
(572, 'Merlo', 76),
(573, 'Pontevedra', 76),
(574, 'San Antonio de Padua', 76),
(575, 'Abbott', 77),
(576, 'San Miguel del Monte (Est. Monte)', 77),
(577, 'Zenón Videla Dorna', 77),
(578, 'Balneario Sauce Grande', 125),
(579, 'Monte Hermoso', 125),
(581, 'Cuartel V', 78),
(582, 'Francisco Álvarez', 78),
(583, 'La Reja', 78),
(584, 'Moreno', 78),
(585, 'Paso del Rey', 78),
(586, 'Trujuí', 78),
(588, 'Castelar', 79),
(589, 'El Palomar', 79),
(590, 'Haedo', 79),
(591, 'Morón', 79),
(592, 'Villa Sarmiento', 79),
(593, 'José Juan Almeyra', 80),
(594, 'Las Marianas', 80),
(595, 'Navarro', 80),
(596, 'Villa Moll (Est. Moll)', 80),
(597, 'Claraz', 81),
(598, 'Costa Bonita', 81),
(599, 'Juan N. Fernández', 81),
(600, 'Necochea - Quequén', 81),
(601, 'Necochea', 81),
(602, 'Quequén', 81),
(603, 'Nicanor Olivera (Est. La Dulce)', 81),
(604, 'Ramón Santamarina', 81),
(605, '12 de Octubre', 82),
(606, '9 de Julio', 82),
(607, 'Alfredo Demarchi (Est. Facundo Quiroga)', 82),
(608, 'Carlos María Naón', 82),
(609, 'Dudignac', 82),
(610, 'La Aurora (Est. La Niña)', 82),
(611, 'Manuel B. Gonnet (Est. French)', 82),
(612, 'Marcelino Ugarte (Est. Dennehy)', 82),
(613, 'Morea', 82),
(614, 'Norumbega', 82),
(615, 'Patricios', 82),
(616, 'Villa General Fournier (Est. 9 de Julio Sud)', 82),
(617, 'Blancagrande', 83),
(618, 'Colonia Hinojo', 83),
(619, 'Colonia Nievas', 83),
(620, 'Colonia San Miguel', 83),
(621, 'Espigas', 83),
(622, 'Hinojo', 83),
(623, 'Olavarría', 83),
(624, 'Recalde', 83),
(625, 'Santa Luisa', 83),
(626, 'Sierra Chica', 83),
(628, 'Sierras Bayas', 83),
(629, 'Villa Arrieta', 83),
(630, 'Villa Alfredo Fortabat', 83),
(631, 'Villa La Serranía', 83),
(632, 'Bahía San Blas', 84),
(633, 'Cardenal Cagliero', 84),
(634, 'Carmen de Patagones', 84),
(635, 'José B. Casas', 84),
(636, 'Juan A. Pradere', 84),
(637, 'Stroeder', 84),
(638, 'Villalonga', 84),
(639, 'Capitán Castro', 85),
(640, 'Chiclana', 85),
(641, 'Francisco Madero', 85),
(642, 'Juan José Paso', 85),
(643, 'Magdala', 85),
(644, 'Mones Cazón', 85),
(645, 'Nueva Plata', 85),
(646, 'Pehuajó', 85),
(647, 'San Bernardo (Est. Guanaco)', 85),
(648, 'Bocayuva', 86),
(649, 'De Bary', 86),
(650, 'Pellegrini', 86),
(651, 'Acevedo', 87),
(652, 'Fontezuela', 87),
(653, 'Guerrico', 87),
(654, 'Juan A. de la Peña', 87),
(655, 'Juan Anchorena (Est. Urquiza)', 87),
(656, 'La Violeta', 87),
(657, 'Manuel Ocampo', 87),
(658, 'Mariano Benítez', 87),
(659, 'Mariano H. Alfonzo (Est. San Patricio)', 87),
(660, 'Pergamino', 87),
(661, 'Pinzón', 87),
(662, 'Rancagua', 87),
(663, 'Villa Angélica (Est. El Socorro)', 87),
(664, 'Villa San José', 87),
(665, 'Casalins', 88),
(666, 'Pila', 88),
(667, 'Barrio Parque Almirante Irizar (Ap. Kilómetro 61)', 89),
(668, 'Club de Campo Larena - Los Quinchos', 89),
(669, 'Club de Campo Larena', 89),
(670, 'Los Quinchos', 89),
(671, 'Country Club El Jagüel', 89),
(673, 'Del Viso', 89),
(674, 'Fátima', 89),
(675, 'La Lonja', 89),
(676, 'Los Cachorros', 89),
(677, 'Manzanares', 89),
(678, 'Manzone', 89),
(679, 'Maquinista F. Savio (Oeste)', 89),
(680, 'Pilar', 89),
(681, 'Presidente Derqui', 89),
(682, 'Roberto De Vicenzo', 89),
(683, 'Santa Teresa', 89),
(684, 'Totuguitas', 89),
(685, 'Villa Astolfi', 89),
(686, 'Villa Rosa', 89),
(687, 'Zelaya', 89),
(689, 'Cariló', 123),
(690, 'Ostende', 123),
(691, 'Pinamar', 123),
(692, 'Valeria del Mar', 123),
(693, 'Presidente Perón', 128),
(694, 'Barrio América Unida (Ex Longchamps)', 128),
(695, 'Guernica', 128),
(696, '17 de Agosto', 90),
(697, 'Azopardo', 90),
(698, 'Bordenave', 90),
(699, 'Darregueira', 90),
(700, 'Estela', 90),
(701, 'Felipe Solá', 90),
(702, 'López Lecube', 90),
(703, 'Puán', 90),
(704, 'San Germán', 90),
(705, 'Villa Castelar (Est. Erize)', 90),
(706, 'Villa Iris', 90),
(707, 'Alvarez Jonte', 133),
(708, 'Las Tahonas', 133),
(709, 'Pipinas', 133),
(710, 'Punta Indio', 133),
(711, 'Verónica', 133),
(713, 'Bernal', 91),
(714, 'Bernal Oeste', 91),
(715, 'Don Bosco', 91),
(716, 'Ezpeleta', 91),
(717, 'Ezpeleta Oeste', 91),
(718, 'Quilmes', 91),
(719, 'Quilmes Oeste', 91),
(720, 'San Francisco Solano', 91),
(721, 'Villa La Florida', 91),
(722, 'El Paraíso', 92),
(723, 'Las Bahamas', 92),
(724, 'Pérez Millán', 92),
(725, 'Ramallo', 92),
(726, 'Villa General Savio (Est. Sánchez)', 92),
(727, 'Villa Ramallo', 92),
(728, 'Rauch', 93),
(729, 'América', 94),
(730, 'Fortín Olavarría', 94),
(731, 'González Moreno', 94),
(732, 'Mira Pampa', 94),
(733, 'Roosevelt', 94),
(734, 'San Mauricio', 94),
(735, 'Sansinena', 94),
(736, 'Sundblad', 94),
(737, 'La Beba', 95),
(738, 'Las Carabelas', 95),
(739, 'Los Indios', 95),
(740, 'Rafael Obligado', 95),
(741, 'Roberto Cano', 95),
(742, 'Rojas', 95),
(743, 'Villa Manuel Pomar', 95),
(744, 'Villa Parque Cecir', 95),
(745, 'Carlos Beguerie', 96),
(746, 'Roque Pérez', 96),
(747, 'Arroyo Corto', 97),
(748, 'Colonia San Martín', 97),
(749, 'Dufaur', 97),
(750, 'Espartillar', 97),
(751, 'Goyena', 97),
(752, 'Pigüé', 97),
(753, 'Saavedra', 97),
(754, 'Alvarez de Toledo', 98),
(755, 'Blaquier', 98),
(756, 'Cazón', 98),
(757, 'Del Carril', 98),
(758, 'Polvaredas', 98),
(759, 'Saladillo', 98),
(760, 'Quenumá', 100),
(761, 'Salliqueló', 100),
(762, 'Arroyo Dulce', 99),
(763, 'Berdier', 99),
(764, 'Gahan', 99),
(765, 'Inés Indart', 99),
(766, 'La Invencible', 99),
(767, 'Salto', 99),
(768, 'Azcuénaga', 101),
(769, 'Cucullú', 101),
(770, 'Franklin', 101),
(771, 'San Andrés de Giles', 101),
(772, 'Solís', 101),
(773, 'Villa Espil', 101),
(774, 'Villa Ruiz', 101),
(775, 'Duggan', 102),
(776, 'San Antonio de Areco', 102),
(777, 'Villa Lía', 102),
(778, 'Balneario San Cayetano', 103),
(779, 'Ochandío', 103),
(780, 'San Cayetano', 103),
(782, 'San Fernando', 104),
(783, 'Victoria', 104),
(784, 'Virreyes', 104),
(787, 'Acassuso', 105),
(788, 'Béccar', 105),
(789, 'Boulogne Sur Mer', 105),
(790, 'Martínez', 105),
(791, 'San Isidro', 105),
(794, 'Bella Vista', 130),
(795, 'Campo de Mayo', 130),
(796, 'Muñiz', 130),
(797, 'San Miguel', 130),
(798, 'Conesa', 106),
(799, 'Erézcano', 106),
(800, 'General Rojo', 106),
(802, 'La Emilia', 106),
(803, 'Villa Campi', 106),
(804, 'Villa Canto (Ex- 2153)', 106),
(805, 'Villa Riccio', 106),
(806, 'Villa Hermosa', 106),
(807, 'San Nicolás de los Arroyos', 106),
(808, 'Campos Salles', 106),
(809, 'San Nicolás', 106),
(810, 'Villa Esperanza', 106),
(811, 'Gobernador Castro', 107),
(812, 'Obligado', 107),
(813, 'Pueblo Doyle', 107),
(814, 'Río Tala', 107),
(815, 'San Pedro', 107),
(816, 'Santa Lucía', 107),
(817, 'Domselaar', 108),
(819, 'Alejandro Korn', 108),
(820, 'San Vicente', 108),
(821, 'General Rivas', 109),
(822, 'Suipacha', 109),
(823, 'De la Canal', 110),
(824, 'Desvío Aguirre', 110),
(825, 'Gardey', 110),
(826, 'María Ignacia (Est. Vela)', 110),
(827, 'Tandil', 110),
(828, 'Crotto', 111),
(829, 'Tapalqué', 111),
(830, 'Velloso', 111),
(832, 'Benavidez', 112),
(833, 'Dique Luján', 112),
(834, 'Don Torcuato Este', 112),
(835, 'Don Torcuato Oeste', 112),
(836, 'El Talar', 112),
(837, 'General Pacheco', 112),
(838, 'Los Troncos del Talar', 112),
(839, 'Ricardo Rojas', 112),
(840, 'Rincón de Milberg', 112),
(841, 'Tigre', 112),
(842, 'Islas', 112),
(843, 'General Conesa', 113),
(844, 'Villa Roch', 113),
(845, 'Chasicó', 114),
(846, 'Saldungaray', 114),
(847, 'Sierra de la Ventana', 114),
(848, 'Tornquist', 114),
(849, 'Tres Picos', 114),
(850, 'Villa Serrana La Gruta', 114),
(851, 'Villa Ventana', 114),
(852, '30 de Agosto', 115),
(853, 'Berutti', 115),
(854, 'Girodias', 115),
(855, 'La Carreta', 115),
(856, 'Trenque Lauquen', 115),
(857, 'Balneario Orense', 116),
(859, 'Claromecó', 116),
(860, 'Dunamar', 116),
(861, 'Copetonas', 116),
(862, 'Lin Calel', 116),
(863, 'Micaela Cascallares (Est. Cascallares)', 116),
(864, 'Orense', 116),
(865, 'Reta', 116),
(866, 'San Francisco de Bellocq', 116),
(867, 'San Mayol', 116),
(868, 'Tres Arroyos', 116),
(869, 'Villa Rodríguez (Est. Barrow)', 116),
(870, 'Tres de Febrero', 117),
(871, 'Caseros', 117),
(872, 'Churruca', 117),
(873, 'Ciudad Jardín Lomas del Palomar', 117),
(874, 'Ciudadela', 117),
(875, 'El Libertador', 117),
(876, 'José Ingenieros', 117),
(877, 'Loma Hermosa', 117),
(878, 'Martín Coronado', 117),
(879, '11 de Septiembe', 117),
(880, 'Pablo Podestá', 117),
(881, 'Remedios de Escalada', 117),
(882, 'Saenz Peña', 117),
(883, 'Santos Lugares', 117),
(884, 'Villa Bosch (Est. Juan María Bosch)', 117),
(885, 'Villa Raffo', 117),
(886, 'Ingeniero Thompson', 126),
(887, 'Tres Lomas', 126),
(888, '25 de Mayo', 118),
(889, 'Agustín Mosconi', 118),
(890, 'Del Valle', 118),
(891, 'Ernestina', 118),
(892, 'Gobernador Ugarte', 118),
(893, 'Lucas Monteverde', 118),
(894, 'Norberto de la Riestra', 118),
(895, 'Pedernales', 118),
(896, 'San Enrique', 118),
(897, 'Valdés', 118),
(899, 'Carapachay', 119),
(900, 'Florida', 119),
(901, 'Florida Oeste', 119),
(902, 'La Lucila', 119),
(903, 'Munro', 119),
(904, 'Olivos', 119),
(905, 'Vicente Lopez', 119),
(906, 'Villa Adelina', 119),
(907, 'Villa Martelli', 119),
(909, 'Mar Azul', 124),
(910, 'Mar de las Pampas', 124),
(911, 'Villa Gesell', 124),
(912, 'Argerich', 120),
(913, 'Colonia San Adolfo', 120),
(914, 'Hilario Ascasubi', 120),
(915, 'Juan Cousté (Est. Algarrobo)', 120),
(916, 'Mayor Buratovich', 120),
(918, 'Country Los Médanos (Barrio Gas del Estado)', 120),
(919, 'Médanos', 120),
(920, 'Pedro Luro', 120),
(921, 'Teniente Origone', 120),
(922, 'Barrio Saavedra', 121),
(923, 'Country Club El Casco', 121),
(924, 'Escalada', 121),
(925, 'Lima', 121),
(926, 'Zárate', 121);


INSERT INTO  `jym_jovenes`.`Plantilla` (`id` ,`codigo` ,`asunto` ,`cuerpo` ,`puedeBorrarse`)
VALUES ('1',  'registro_usuario',  'Registro usuarios',  'asdasd asda sdasd asdasd ',  '0');


INSERT INTO `jym_jovenes`.`TipoEscuela`(`id`,`nombre`) VALUES
(1,'CEBAS'),
(2,'CENS'),
(3,'CEPT'),
(4,'ECSB'),
(5,'Centro de Educación Complementario'),
(6,'Escuela de Adultos'),
(7,'Escuela de Educación Secundaria'),
(8,'Escuela de Enseñanza Media'),
(9,'Escuela Secundaria Agropecuaria'),
(10,'Escuela Secundaria Técnica'),
(11,'Escuela Secundaria Básica'),
(12,'Instituto Superior de Formación Docente y Técnico');

INSERT INTO `jym_jovenes`.`Tema` (`id`,`nombre`,`anulado`) VALUES
(1,'Episodios de violencia institucional como el "gatillo facil"',0),
(2, 'Percepciones actuales sobre la dictadura: el "aca no pasó nada"',0),
(3, 'Agenda actual de Derechos Humanos',0),
(4, 'Biografía de Desaparecidos de la Comunidad',0),
(5, 'Consecuencias de la Dictadura Militar en la Comunidad',0),
(6, 'Discriminación, Género y Violencia',0),
(7, 'Guerra de Malvinas',0),
(8, 'Impacto de la Dictadura sobre las prácticas educativas',0),
(9, 'Participación Juvenil y Protesta Social hoy',0),
(10, 'Reconstrucción de los episodios locales, de represión y de resistencia',0),
(11, 'Reconstrucción de la vida cotidiana durante la dictadura',0),
(12, 'Transformaciones socioeconómicas provocadas por la dictadura',0),
(13, 'Exclusión Social',0);

INSERT INTO `jym_jovenes`.`Produccion` (`id`,`nombre`) VALUES
(1,'Documental Audiovisual'),
(2,'Ficción Audiovisual'),
(3,'Murga'),
(4,'Teatro, Expresión Corporal'),
(5,'Danzas'),
(6,'Producción Gráfica (folletos, cartillas, diarios, revistas)'),
(7,'Historieta'),
(8,'Libro'),
(9,'Mural'),
(10,'Placa'),
(11,'Instalación'),
(12,'Edición Multimedia'),
(13,'Página web, Blog, etc'),
(14,'Documental Sonoro'),
(15,'Radio'),
(16,'Música, Canciones'),
(17,'Muestra Fotográfica');
/* (18,'Otro'); */



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


