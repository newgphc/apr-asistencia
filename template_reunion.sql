-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 09, 2018 at 12:21 PM
-- Server version: 5.7.21
-- PHP Version: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `template_reunion`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `busca_por_nombre_final` (IN `prmid` INT(5))  BEGIN
	SELECT s.id_ai_soc, s.rut_soc AS 'rut', s.dvrt_soc as 'dv', CONCAT(s.nomb_soc, ' ',s.apell_soc) AS 'nombre', asis_soc, ct.nomb_rep_soc AS 'nrep', ct.rut_rep_soc AS 'rtrep', ct.dvrt_rep_soc AS 'dvrtrep'  FROM socios s LEFT JOIN carta_datos ct ON ct.id_socio=s.id_ai_soc WHERE s.id_ai_soc=prmid;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `busca_por_rut_final` (IN `prmrut` VARCHAR(15))  BEGIN
	SELECT s.id_ai_soc,  s.rut_soc AS 'rut', s.dvrt_soc AS 'dv', CONCAT(s.nomb_soc, ' ',s.apell_soc) AS 'nombre', asis_soc, ct.nomb_rep_soc AS 'nrep', ct.rut_rep_soc AS 'rtrep', ct.dvrt_rep_soc AS 'dvrtrep'  FROM socios s LEFT JOIN carta_datos ct ON ct.id_socio=s.id_ai_soc WHERE s.rut_soc=prmrut;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `inscribe_fact_elect` (IN `id_socio_i` INT, IN `correo_mail` VARCHAR(1000))  BEGIN
	IF (SELECT COUNT(*) FROM suscripcion_factura_electronica WHERE id_socio=id_socio_i) = 0 THEN
		INSERT INTO suscripcion_factura_electronica (id_socio, correo_electronico, estado)VALUES(id_socio_i, correo_mail, 1);
	ELSE
		update suscripcion_factura_electronica set correo_electronico = correo_mail where id_socio=id_socio_i; 
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_impresoras` ()  BEGIN
	select * from impresoras;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtiene_datos_socio` (IN `idsoc` INT(5))  begin
	SELECT CONCAT(soc.nomb_soc, ' ',  soc.apell_soc)  AS 'nombre', sus.cod_soc_susc AS 'codigo', sus.direccion_susc AS 'direccion', soc.asis_soc as 'asistesoc', c.nomb_rep_soc as 'nombrep', c.rut_rep_soc as 'rut', c.dvrt_rep_soc as 'dv' FROM suscripciones sus INNER JOIN socios soc ON sus.id_soc_susc=soc.id_ai_soc left join carta_datos c on c.id_socio=soc.id_ai_soc WHERE sus.id_soc_susc = idsoc LIMIT 1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtiene_nombre_impresora` (IN `prmidprint` INT(5))  BEGIN
	select value_impresora as 'nameprint' from impresoras where id_impresora = prmidprint;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_busca_datos_carta` (IN `iduser` INT)  BEGIN
	SELECT ct.id_ai_crt as 'id', ct.tpcrt as 'tpcrt', s.rut_soc AS 'rut', s.dvrt_soc AS 'dv', CONCAT(s.nomb_soc, ' ', s.apell_soc) AS 'nombre', sc.direccion_susc AS 'direccion', s.nomb_soc as 'nomb_soc', s.apell_soc 'apell_soc', ct.motivo_inas_soc as 'motivo', ct.nomb_rep_soc as 'nomb_rep_soc', ct.rut_rep_soc as 'rut_rep_soc', ct.dvrt_rep_soc as 'dvrep'
	FROM socios s
	INNER JOIN carta_datos ct ON ct.id_socio=s.id_ai_soc
	INNER JOIN suscripciones sc ON ct.dir_pred=sc.id_ai_susc
	WHERE s.id_ai_soc = iduser;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_busca_datos_carta_rep` (IN `iduser` INT)  BEGIN
SELECT ct.id_ai_crt AS 'id', s.rut_soc AS 'rut', s.dvrt_soc AS 'dv', CONCAT(s.nomb_soc, ' ', s.apell_soc) AS 'nombre', sc.direccion_susc AS 'direccion', ct.nomb_rep_soc AS 'nomb_rep_soc', ct.rut_rep_soc AS 'rut_rep_soc', s.nomb_soc AS 'nomb_soc', s.apell_soc 'apell_soc', ct.dvrt_rep_soc as 'dvrep'
	FROM socios s
	INNER JOIN carta_datos ct ON ct.id_socio=s.id_ai_soc
	INNER JOIN suscripciones sc ON ct.dir_pred=sc.id_ai_susc
	WHERE s.id_ai_soc = iduser;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_ingresa_datos_evento` (IN `dir_event` VARCHAR(250), IN `fec_event` VARCHAR(250), IN `hora_event` TIME, IN `empresa` VARCHAR(250), IN `P_ticket_title` VARCHAR(250), IN `P_ticket_footer_text` VARCHAR(250))  NO SQL
BEGIN
	IF (SELECT COUNT(*) FROM parametros WHERE 1) = 0 THEN
		INSERT INTO parametros (direccion_evento, fecha_evento, hora_evento, nombre_empresa, ticket_title, ticket_footer_text)VALUES(dir_event, STR_TO_DATE(REPLACE(fec_event,'-','.'), GET_FORMAT(DATE,'EUR')), hora_event, empresa, p_ticket_title, p_ticket_footer_text);
	ELSE
		UPDATE parametros SET direccion_evento=dir_event, fecha_evento=STR_TO_DATE(REPLACE(fec_event,'-','.') ,GET_FORMAT(date,'EUR')), hora_evento=hora_event, nombre_empresa=empresa, ticket_title = p_ticket_title, ticket_footer_text = p_ticket_footer_text WHERE 1;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_login_user` (IN `nick` VARCHAR(100), IN `pwd` VARCHAR(256))  BEGIN
	SELECT id_user, Nombre, Admin, Email FROM usuarios WHERE (Usuario = CONVERT(nick USING utf8) COLLATE utf8_spanish_ci) AND (Pass = CONVERT(pwd USING utf8) COLLATE utf8_spanish_ci);
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_param_evento` ()  BEGIN
	SELECT direccion_evento, fecha_evento, hora_evento, nombre_empresa, ticket_title, ticket_footer_text FROM parametros WHERE 1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_reset_system` ()  BEGIN
	truncate table socios;
	truncate table suscripciones;
	truncate table carta_datos;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_actualiza_asist` (IN `soc_id` INT, IN `tcarta` INT(1), IN `rut_rep` INT, IN `dvrt_rep` VARCHAR(10), IN `nomb_rep` VARCHAR(500), IN `motivo` VARCHAR(500), IN `dir_pred` INT)  BEGIN
	update socios set asis_soc=tcarta where id_ai_soc=soc_id;
	insert into carta_datos (tpcrt,motivo_inas_soc, rut_rep_soc, dvrt_rep_soc, nomb_rep_soc, dir_pred, id_socio) values (tcarta, motivo, rut_rep, dvrt_rep, nomb_rep, dir_pred, soc_id);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_actualiza_asist_reset` (IN `soc_id` INT)  BEGIN
    update socios set asis_soc=0 where id_ai_soc=soc_id;
    delete from carta_datos where id_socio=soc_id;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_actualiza_rut` (IN `rut` INT, IN `soc_id` INT, IN `dv` VARCHAR(10))  BEGIN
	update socios set rut_soc=rut, dvrt_soc=dv where id_ai_soc=soc_id;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_busca` (IN `rut` INT)  BEGIN
	SELECT id_ai_soc, CONCAT(rut_soc, '-', dvrt_soc) AS 'rut', CONCAT(nomb_soc, ' ',apell_soc) AS 'nombre', asis_soc from socios where rut_soc=rut;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_busca_id` (IN `id` INT)  BEGIN
	SELECT id_ai_soc, concat(rut_soc, '-', dvrt_soc) as 'rut', concat(nomb_soc, ' ',apell_soc) as 'nombre', asis_soc FROM socios WHERE id_ai_soc=id;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_busca_nombre` (IN `nombre` VARCHAR(1000))  BEGIN
	select id_ai_soc, rut_soc, dvrt_soc, concat(nomb_soc, ' ',  apell_soc)  as 'nombre_completo' from socios where CONCAT(apell_soc, ' ',  nomb_soc) LIKE CONVERT(CONCAT('%', nombre, '%') using utf8) collate utf8_spanish_ci;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_busca_susc_id` (IN `id_soc` INT)  BEGIN
	select id_ai_susc, direccion_susc from suscripciones where id_soc_susc=id_soc;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_factura_ins` (IN `id_user` INT)  BEGIN
    select correo_electronico from suscripcion_factura_electronica where id_socio=id_user;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_ingresa` (IN `cod_soc1` INT, IN `rut_soc1` INT, IN `dvrt_soc1` VARCHAR(5), IN `nomb_soc1` VARCHAR(500), IN `apell_soc1` VARCHAR(500), IN `direccion_soc1` VARCHAR(500), IN `asis_soc1` INT, IN `asistencia_final_soc_rep1` INT)  BEGIN
	DECLARE id_sosio INT;
	set id_sosio = 0;
	IF (select COUNT(*) from socios where rut_soc=rut_soc1) = 0 then
		insert into socios (rut_soc, dvrt_soc, nomb_soc, apell_soc, asis_soc, asitencia_final_soc_rep) values (rut_soc1, dvrt_soc1, nomb_soc1, apell_soc1, asis_soc1, asistencia_final_soc_rep1);
		select @id_sosio:=id_ai_soc from socios where rut_soc=rut_soc1;
		insert into suscripciones (id_soc_susc, cod_soc_susc, direccion_susc) values (@id_sosio, cod_soc1, direccion_soc1);
	ELSE
		select @id_sosio:=id_ai_soc from socios where rut_soc=rut_soc1;
		insert into suscripciones (id_soc_susc, cod_soc_susc, direccion_susc) values (@id_sosio, cod_soc1, direccion_soc1);
	end IF;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_socio_lista_nombre` (IN `nombre` VARCHAR(1000))  BEGIN
	SELECT id_ai_soc, rut_soc, dvrt_soc, CONCAT(nomb_soc, ' ',  apell_soc)  AS 'nombre_completo' FROM socios WHERE CONCAT(apell_soc, ' ',  nomb_soc) LIKE CONVERT(CONCAT('%', nombre, '%') USING utf8) COLLATE utf8_spanish_ci  ORDER BY nombre_completo ASC;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registra_asistencia` (IN `iduser` INT(5), IN `genero` INT(5))  BEGIN
	UPDATE socios SET asitencia_final_soc_rep = 1, gen_s_o_r = genero WHERE id_ai_soc = iduser;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `carta_datos`
--

CREATE TABLE `carta_datos` (
  `id_ai_crt` int(15) NOT NULL,
  `tpcrt` int(1) NOT NULL,
  `motivo_inas_soc` varchar(1500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rut_rep_soc` int(15) DEFAULT NULL,
  `dvrt_rep_soc` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nomb_rep_soc` varchar(1500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dir_pred` int(15) DEFAULT NULL,
  `id_socio` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `impresoras`
--

CREATE TABLE `impresoras` (
  `id_impresora` int(10) NOT NULL,
  `value_impresora` varchar(1000) COLLATE utf8_spanish_ci DEFAULT NULL,
  `label_impresora` varchar(1000) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `impresoras`
--

INSERT INTO `impresoras` (`id_impresora`, `value_impresora`, `label_impresora`) VALUES
(1, '192.168.1.110', 'Tiquet1'),
(2, '192.168.1.111', 'Tiquet2'),
(3, '192.168.1.112', 'Tiquet3'),
(4, '192.168.1.113', 'Tiquet4'),
(5, '192.168.1.114', 'Tiquet5'),
(6, '192.168.1.115', 'Tiquet6');

-- --------------------------------------------------------

--
-- Table structure for table `parametros`
--

CREATE TABLE `parametros` (
  `id_pm` int(10) NOT NULL,
  `direccion_evento` varchar(500) NOT NULL,
  `fecha_evento` date NOT NULL,
  `nombre_empresa` varchar(500) NOT NULL,
  `hora_evento` time DEFAULT NULL,
  `ticket_title` varchar(250) NOT NULL,
  `ticket_footer_text` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parametros`
--

INSERT INTO `parametros` (`id_pm`, `direccion_evento`, `fecha_evento`, `nombre_empresa`, `hora_evento`, `ticket_title`, `ticket_footer_text`) VALUES
(0, 'el estadio del club deportivo.Challay Boys de Champa', '2018-03-10', 'Cooperativa Agua Potable  Hospital - Champa Ltda.', '16:00:00', 'Junta Anual de Socios', 'Valido para el sorteo');

-- --------------------------------------------------------

--
-- Table structure for table `socios`
--

CREATE TABLE `socios` (
  `id_ai_soc` int(11) NOT NULL,
  `rut_soc` int(10) DEFAULT NULL,
  `dvrt_soc` varchar(10) NOT NULL,
  `nomb_soc` varchar(500) NOT NULL,
  `apell_soc` varchar(500) NOT NULL,
  `rut_conflict` int(10) DEFAULT NULL,
  `asis_soc` int(10) NOT NULL,
  `gen_s_o_r` int(10) DEFAULT NULL,
  `asitencia_final_soc_rep` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suscripciones`
--

CREATE TABLE `suscripciones` (
  `id_ai_susc` int(11) NOT NULL,
  `id_soc_susc` int(10) NOT NULL,
  `cod_soc_susc` int(10) NOT NULL,
  `direccion_susc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suscripcion_factura_electronica`
--

CREATE TABLE `suscripcion_factura_electronica` (
  `id_susc_mail` int(5) NOT NULL,
  `id_socio` int(5) DEFAULT NULL,
  `correo_electronico` varchar(1000) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(5) NOT NULL,
  `Usuario` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Pass` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Admin` char(1) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_user`, `Usuario`, `Nombre`, `Pass`, `Email`, `Admin`) VALUES
(13, 'Admin', 'Administrador', 'e3d49eb7d5bd443589ed96f77b2e9a1c2504b359d5c200c1401a2266065a8856', 'jose.scream17@gmail.com', '1'),
(14, 'carol', 'Carol Ivette Castro Garate', '499bc7df9d8873c1c38e6898177c343b2a34d2eb43178a9eb4efcb993366c8cd', 'carito19ivi@hotmail.com', '0'),
(15, 'eliana', 'Eliana Silva', '499bc7df9d8873c1c38e6898177c343b2a34d2eb43178a9eb4efcb993366c8cd', 'esilva@hospitalchampa.cl', '0'),
(16, 'jennifer', 'Jennifer Santander', '9ce8db922a8f4a7abd859adee70bd8b7a63321265487da54cf4bed6a69eb3e1b', 'jsantander@hospitalchampa.cl', '0'),
(17, 'jorge', 'Jorge Eugenio Quintanilla Cabezas', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', 'jorgequintanillacabezas@hotmail.com', '0'),
(18, 'leyla', 'leyla arraño', '0a3cefa3b15597e213707e04b4b3e3a0c8b72fc7fc30663e882e636954cbd494', 'ejaleyu@gmail.com', '0'),
(19, 'mariela', 'MARIELA MORALES PROAÑO', '81d0c69a618a3ac8e2e012a3f0c2d152dc2830f4f6fd190a80827cfec6bdbb70', ' ', '0'),
(20, 'roberto', 'roberto carlos soto toledo', '0f2f04006a97c9041459ba90e4bd7b0c6fce81c2d7154117fa4ffa7a094f58f7', 'rsoto@hospitalchampa.cl', '0'),
(21, 'test', 'TESTER User', '22227d1a92c98e414a27eb4a54f913cb8d2470dd046305a586aeeed98bb7e589', ' ', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carta_datos`
--
ALTER TABLE `carta_datos`
  ADD PRIMARY KEY (`id_ai_crt`);

--
-- Indexes for table `impresoras`
--
ALTER TABLE `impresoras`
  ADD PRIMARY KEY (`id_impresora`);

--
-- Indexes for table `socios`
--
ALTER TABLE `socios`
  ADD UNIQUE KEY `id_ai_soc` (`id_ai_soc`);

--
-- Indexes for table `suscripciones`
--
ALTER TABLE `suscripciones`
  ADD UNIQUE KEY `id_ai_susc` (`id_ai_susc`);

--
-- Indexes for table `suscripcion_factura_electronica`
--
ALTER TABLE `suscripcion_factura_electronica`
  ADD PRIMARY KEY (`id_susc_mail`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `PK_Usuarios` (`Usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carta_datos`
--
ALTER TABLE `carta_datos`
  MODIFY `id_ai_crt` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `impresoras`
--
ALTER TABLE `impresoras`
  MODIFY `id_impresora` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `socios`
--
ALTER TABLE `socios`
  MODIFY `id_ai_soc` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suscripciones`
--
ALTER TABLE `suscripciones`
  MODIFY `id_ai_susc` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suscripcion_factura_electronica`
--
ALTER TABLE `suscripcion_factura_electronica`
  MODIFY `id_susc_mail` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
