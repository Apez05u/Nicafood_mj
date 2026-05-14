-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2026 a las 17:38:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nicafood_erp`
--
CREATE DATABASE IF NOT EXISTS `nicafood_erp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `nicafood_erp`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activo_fijo`
--

CREATE TABLE `activo_fijo` (
  `id_activo` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_cuenta_contable` int(11) DEFAULT NULL,
  `fecha_adquisicion` date NOT NULL,
  `costo_adquisicion` decimal(12,2) NOT NULL,
  `vida_util_anos` int(11) DEFAULT NULL,
  `valor_residual` decimal(12,2) DEFAULT 0.00,
  `metodo_depreciacion` enum('Linea_Recta','Saldo_Decreciente','Unidades_Produccion') DEFAULT 'Linea_Recta',
  `ubicacion_fisica` varchar(100) DEFAULT NULL,
  `estado` enum('Activo','Dado_Baja','Vendido','Robado') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id_area` int(11) NOT NULL,
  `id_depto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id_area`, `id_depto`, `nombre`, `codigo`, `responsable_id`) VALUES
(1, 1, 'Caja', 'CAJ-01', NULL),
(2, 2, 'Cocina Principal', 'COC-01', NULL),
(3, 3, 'Administración', 'ADM-01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asiento_cabecera`
--

CREATE TABLE `asiento_cabecera` (
  `id_asiento` int(11) NOT NULL,
  `numero_asiento` varchar(20) DEFAULT NULL,
  `fecha_asiento` date NOT NULL,
  `tipo_asiento` enum('Diario','Ajuste','Cierre','Apertura','Nomina','Inventario') DEFAULT 'Diario',
  `id_documento_ref` int(11) DEFAULT NULL,
  `tipo_documento_ref` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `total_debitos` decimal(14,2) DEFAULT 0.00,
  `total_creditos` decimal(14,2) DEFAULT 0.00,
  `estado` enum('Borrador','Contabilizado','Anulado') DEFAULT 'Borrador',
  `id_empleado_registro` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asiento_detalle`
--

CREATE TABLE `asiento_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_asiento` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `id_centro_costo` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `debito` decimal(14,2) DEFAULT 0.00,
  `credito` decimal(14,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `tipo_registro` enum('Normal','Tarde','Ausente','Permiso','Vacacion','Licencia') DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_inventario`
--

CREATE TABLE `auditoria_inventario` (
  `id_auditoria` int(11) NOT NULL,
  `id_inventario` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad_sistema` decimal(10,3) DEFAULT NULL,
  `cantidad_fisica` decimal(10,3) DEFAULT NULL,
  `diferencia` decimal(10,3) DEFAULT NULL,
  `porcentaje_diferencia` decimal(5,2) DEFAULT NULL,
  `causa_diferencia` text DEFAULT NULL,
  `ajustado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `id_banco` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo_banco` varchar(10) DEFAULT NULL,
  `pais` varchar(2) DEFAULT 'NI',
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`id_banco`, `nombre`, `codigo_banco`, `pais`, `activo`) VALUES
(1, 'Banco de América', 'BAM', 'NI', 1),
(2, 'BAC Credomatic', 'BAC', 'NI', 1),
(3, 'Banpro', 'BNP', 'NI', 1),
(4, 'Lafise', 'LAF', 'NI', 1),
(5, 'Banco de América', 'BAM', 'NI', 1),
(6, 'BAC Credomatic', 'BAC', 'NI', 1),
(7, 'Banpro', 'BNP', 'NI', 1),
(8, 'Lafise', 'LAF', 'NI', 1),
(9, 'Banco de América', 'BAM', 'NI', 1),
(10, 'BAC Credomatic', 'BAC', 'NI', 1),
(11, 'Banpro', 'BNP', 'NI', 1),
(12, 'Lafise', 'LAF', 'NI', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora_log`
--

CREATE TABLE `bitacora_log` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega`
--

CREATE TABLE `bodega` (
  `id_bodega` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('Seco','Frio','Congelado','Liquido','General') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bodega`
--

INSERT INTO `bodega` (`id_bodega`, `id_unidad`, `codigo`, `nombre`, `tipo`) VALUES
(1, 1, NULL, 'Almacén Principal', 'Seco');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nivel_jerarquico` int(11) DEFAULT 1,
  `sueldo_base` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `id_area`, `nombre`, `nivel_jerarquico`, `sueldo_base`) VALUES
(1, 1, 'Cajero', 1, NULL),
(2, 2, 'Cocinero', 1, NULL),
(3, 3, 'Administrador', 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` enum('Insumo','Producto','Servicio','Gasto') DEFAULT 'Producto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `tipo`) VALUES
(1, 'Comidas', 'Producto'),
(2, 'Bebidas', 'Producto'),
(3, 'Postres', 'Producto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro_costo`
--

CREATE TABLE `centro_costo` (
  `id_centro` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_depto` int(11) DEFAULT NULL,
  `presupuesto_mensual` decimal(12,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `codigo_cliente` varchar(20) DEFAULT NULL,
  `metodo_pago_preferido` varchar(30) DEFAULT 'Efectivo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `tipo_cliente` enum('Natural','Corporativo','Frecuente','VIP') DEFAULT 'Natural',
  `limite_credito` decimal(10,2) DEFAULT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `id_persona`, `codigo_cliente`, `metodo_pago_preferido`, `fecha_registro`, `fecha_modificacion`, `fecha_eliminacion`, `tipo_cliente`, `limite_credito`, `saldo_pendiente`) VALUES
(1, 1, 'CLI-001', 'Efectivo', '2026-05-01 02:55:49', '2026-05-01 02:55:49', NULL, 'Natural', NULL, 0.00),
(2, 2, 'CLI-000002', 'Efectivo', '2026-05-05 03:28:39', '2026-05-05 03:28:39', NULL, 'Natural', NULL, 0.00),
(3, 3, 'CLI-000003', 'Efectivo', '2026-05-05 03:35:42', '2026-05-05 03:35:42', NULL, 'Natural', NULL, 0.00),
(4, 4, 'CLI-000004', 'Efectivo', '2026-05-05 03:57:34', '2026-05-05 03:57:34', NULL, 'Natural', NULL, 0.00),
(5, 8, 'CLI-20260506-0008', 'Efectivo', '2026-05-06 00:54:36', '2026-05-06 00:54:36', NULL, 'Natural', NULL, 0.00),
(7, 10, 'CLI-20260506-0010', 'Efectivo', '2026-05-06 03:15:33', '2026-05-06 03:15:33', NULL, 'Natural', NULL, 0.00),
(8, 13, 'CLI-20260506-0013', 'Efectivo', '2026-05-06 03:40:01', '2026-05-06 03:40:01', NULL, 'Natural', NULL, 0.00),
(9, 14, 'CLI-20260506-0014', 'Tarjeta', '2026-05-06 15:20:46', '2026-05-06 15:20:46', NULL, 'Natural', NULL, 0.00),
(10, 32, 'CLI-20260513-0032', 'Efectivo', '2026-05-13 14:44:47', '2026-05-13 14:44:47', NULL, 'Natural', NULL, 0.00),
(11, 33, 'CLI-20260513-0033', 'Tarjeta', '2026-05-13 14:47:38', '2026-05-13 14:47:38', NULL, 'Natural', NULL, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cola_tareas`
--

CREATE TABLE `cola_tareas` (
  `id_tarea` int(11) NOT NULL,
  `nombre_tarea` varchar(100) NOT NULL,
  `tipo` enum('Backup','Reporte','Limpieza','Sincronizacion','Notificacion') DEFAULT 'Backup',
  `estado` enum('Pendiente','En_Proceso','Completada','Fallida') DEFAULT 'Pendiente',
  `prioridad` enum('Baja','Media','Alta') DEFAULT 'Media',
  `fecha_programada` datetime DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `resultado` text DEFAULT NULL,
  `intentos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo`
--

CREATE TABLE `combo` (
  `id_combo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_combo` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `combo`
--

INSERT INTO `combo` (`id_combo`, `nombre`, `precio_combo`, `activo`) VALUES
(1, 'Combo Familiar', 450.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_detalle`
--

CREATE TABLE `combo_detalle` (
  `id_combo` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_default` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto_nomina`
--

CREATE TABLE `concepto_nomina` (
  `id_concepto` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('Ingreso','Deduccion','Aporte_Empresa') NOT NULL,
  `calculo` enum('Fijo','Porcentaje','Variable','Formula') DEFAULT 'Fijo',
  `valor_base` decimal(10,2) DEFAULT NULL,
  `porcentaje` decimal(5,2) DEFAULT NULL,
  `afecta_iss` tinyint(1) DEFAULT 0,
  `afecta_ir` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `concepto_nomina`
--

INSERT INTO `concepto_nomina` (`id_concepto`, `codigo`, `nombre`, `tipo`, `calculo`, `valor_base`, `porcentaje`, `afecta_iss`, `afecta_ir`, `activo`) VALUES
(1, 'SAL_BASE', 'Salario Base', 'Ingreso', 'Fijo', NULL, NULL, 0, 0, 1),
(2, 'HOR_EXT', 'Horas Extras', 'Ingreso', 'Variable', NULL, NULL, 0, 0, 1),
(3, 'BONIF', 'Bonificación', 'Ingreso', 'Fijo', NULL, NULL, 0, 0, 1),
(4, 'INSS_EMP', 'INSS Patronal', 'Aporte_Empresa', 'Porcentaje', NULL, 19.00, 0, 0, 1),
(5, 'INSS_LAB', 'INSS Laboral', 'Deduccion', 'Porcentaje', NULL, 7.00, 0, 0, 1),
(6, 'IR_LAB', 'Impuesto sobre la Renta', 'Deduccion', 'Porcentaje', NULL, 10.00, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sistema`
--

CREATE TABLE `configuracion_sistema` (
  `id_config` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text DEFAULT NULL,
  `tipo` enum('texto','numero','boolean','json','archivo') DEFAULT 'texto',
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_sistema`
--

INSERT INTO `configuracion_sistema` (`id_config`, `clave`, `valor`, `tipo`, `descripcion`, `fecha_actualizacion`) VALUES
(1, 'nombre_sistema', 'NicaFood ERP', 'texto', 'Nombre del sistema', '2026-05-05 02:33:06'),
(2, 'version', '6.3', 'texto', 'Versión del sistema', '2026-05-05 02:33:06'),
(3, 'iva_default', '15', 'numero', 'IVA por defecto', '2026-05-05 02:33:06'),
(4, 'moneda_simbolo', 'C$', 'texto', 'Símbolo de moneda local', '2026-05-05 02:33:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato_laboral`
--

CREATE TABLE `contrato_laboral` (
  `id_contrato` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_contrato` enum('Indefinido','Plazo_Fijo','Temporal','Obra','Servicio') DEFAULT 'Indefinido',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `salario_mensual` decimal(10,2) DEFAULT NULL,
  `horas_semanales` int(11) DEFAULT 48,
  `estado` enum('Activo','Finalizado','Cancelado') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato_proveedor`
--

CREATE TABLE `contrato_proveedor` (
  `id_contrato` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `tipo_contrato` enum('Compra','Servicio','Arrendamiento','Distribucion') DEFAULT 'Compra',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `monto_total` decimal(12,2) DEFAULT NULL,
  `condiciones_pago` text DEFAULT NULL,
  `estado` enum('Activo','Vencido','Cancelado') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_temperatura`
--

CREATE TABLE `control_temperatura` (
  `id_control` int(11) NOT NULL,
  `id_bodega` int(11) NOT NULL,
  `temperatura` decimal(5,2) NOT NULL,
  `humedad` decimal(5,2) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `id_empleado_registro` int(11) DEFAULT NULL,
  `dentro_rango` tinyint(1) DEFAULT 1,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costeo_producto`
--

CREATE TABLE `costeo_producto` (
  `id_costeo` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_costeo` date NOT NULL,
  `costo_materia_prima` decimal(10,2) DEFAULT NULL,
  `costo_mano_obra` decimal(10,2) DEFAULT NULL,
  `costo_indirectos` decimal(10,2) DEFAULT NULL,
  `costo_total` decimal(10,2) DEFAULT NULL,
  `margen_aplicado` decimal(5,2) DEFAULT NULL,
  `precio_sugerido` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_bancaria`
--

CREATE TABLE `cuenta_bancaria` (
  `id_cuenta` int(11) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `numero_cuenta` varchar(30) NOT NULL,
  `tipo_cuenta` enum('Corriente','Ahorro','Credito') DEFAULT 'Corriente',
  `moneda` varchar(3) DEFAULT 'NIO',
  `saldo_actual` decimal(14,2) DEFAULT 0.00,
  `fecha_apertura` date DEFAULT NULL,
  `estado` enum('Activa','Cerrada','Congelada') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupon_descuento`
--

CREATE TABLE `cupon_descuento` (
  `id_cupon` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `tipo` enum('Porcentaje','Monto_Fijo','Envio_Gratis') DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_depto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `presupuesto_anual` decimal(14,2) DEFAULT 0.00,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_depto`, `nombre`, `presupuesto_anual`, `fecha_registro`) VALUES
(1, 'Ventas', 0.00, '2026-05-01 02:37:00'),
(2, 'Cocina', 0.00, '2026-05-01 02:37:00'),
(3, 'Administración', 0.00, '2026-05-01 02:37:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `depreciacion_mensual`
--

CREATE TABLE `depreciacion_mensual` (
  `id_depreciacion` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `mes_anio` date NOT NULL,
  `monto_depreciacion` decimal(12,2) NOT NULL,
  `depreciacion_acumulada` decimal(12,2) DEFAULT NULL,
  `valor_libros` decimal(12,2) DEFAULT NULL,
  `id_asiento_contable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cotizacion`
--

CREATE TABLE `detalle_cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad_solicitada` decimal(10,3) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `plazo_entrega_dias` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_orden_compra`
--

CREATE TABLE `detalle_orden_compra` (
  `id_detalle` int(11) NOT NULL,
  `id_orden` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_proveedor`
--

CREATE TABLE `devolucion_proveedor` (
  `id_devolucion` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_recepcion` int(11) DEFAULT NULL,
  `fecha_devolucion` date NOT NULL,
  `motivo` enum('Producto_Dañado','Vencido','Calidad','Error_Cantidad','Otro') DEFAULT 'Producto_Dañado',
  `total_devuelto` decimal(12,2) DEFAULT NULL,
  `estado` enum('Pendiente','Aprobado','Rechazado','Reembolsado') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_venta`
--

CREATE TABLE `devolucion_venta` (
  `id_devolucion` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_factura_original` int(11) NOT NULL,
  `id_empleado_autoriza` int(11) DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT current_timestamp(),
  `motivo` enum('Producto_Dañado','Error_Pedido','Insatisfaccion','Vencido','Otro') DEFAULT 'Producto_Dañado',
  `total_devuelto` decimal(12,2) DEFAULT NULL,
  `tipo_reembolso` enum('Efectivo','Credito','Cambio') DEFAULT 'Efectivo',
  `estado` enum('Pendiente','Aprobado','Rechazado','Procesado') DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `codigo_emp` varchar(20) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `tipo_categoria` enum('Administrativo','Operativo','Gerencial','Temporario') DEFAULT 'Operativo',
  `aplica_vacaciones` tinyint(1) DEFAULT 1,
  `Tipo_sangre` varchar(5) DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Licencia','Despedido') DEFAULT 'Activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_egreso` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `id_persona`, `codigo_emp`, `id_unidad`, `id_area`, `id_cargo`, `fecha_ingreso`, `tipo_categoria`, `aplica_vacaciones`, `Tipo_sangre`, `estado`, `fecha_registro`, `fecha_modificacion`, `fecha_egreso`) VALUES
(1, 1, 'EMP-001', 1, 1, 1, '2024-01-15', 'Operativo', 1, NULL, 'Inactivo', '2026-05-01 02:37:00', '2026-05-13 04:13:00', NULL),
(2, 2, 'EMP-002', 1, 3, 3, '2024-03-01', 'Gerencial', 1, NULL, 'Activo', '2026-05-05 16:06:59', '2026-05-05 16:06:59', NULL),
(3, 3, 'EMP-003', 1, 3, 3, '2024-03-01', 'Gerencial', 1, NULL, 'Activo', '2026-05-05 16:06:59', '2026-05-05 16:06:59', NULL),
(4, 4, 'EMP-004', 1, 1, 1, '2026-05-12', 'Operativo', 1, NULL, 'Activo', '2026-05-05 16:06:59', '2026-05-12 02:51:29', NULL),
(17, 27, 'EMP-20260513-3822', 1, 3, 3, '2026-05-13', 'Administrativo', 1, NULL, 'Activo', '2026-05-13 01:54:50', '2026-05-13 01:54:50', NULL),
(18, 28, 'EMP-20260513-8478', 1, 3, 3, '2026-05-13', 'Gerencial', 1, NULL, 'Activo', '2026-05-13 04:05:06', '2026-05-13 04:05:06', NULL),
(19, 29, 'EMP-20260513-5093', 1, 2, 2, '2026-05-13', 'Temporario', 1, NULL, 'Activo', '2026-05-13 13:39:01', '2026-05-13 14:54:36', NULL),
(20, 34, 'EMP-20260513-7095', 1, 2, 2, '2026-05-13', 'Operativo', 1, NULL, 'Activo', '2026-05-13 14:59:55', '2026-05-13 14:59:55', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre_legal` varchar(150) NOT NULL,
  `nombre_comercial` varchar(150) DEFAULT NULL,
  `ruc_fiscal` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `departamento` varchar(50) DEFAULT NULL,
  `pais` varchar(2) DEFAULT 'NI',
  `moneda_base` varchar(3) DEFAULT 'NIO',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `codigo`, `nombre_legal`, `nombre_comercial`, `ruc_fiscal`, `telefono`, `email`, `direccion`, `ciudad`, `departamento`, `pais`, `moneda_base`, `fecha_registro`, `fecha_modificacion`) VALUES
(1, 'NF-001', 'NicaFood S.A.', NULL, 'J0310001000015', NULL, NULL, NULL, NULL, NULL, 'NI', 'NIO', '2026-05-05 02:33:06', '2026-05-05 02:33:06'),
(2, NULL, 'NicaFood S.A.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NI', 'NIO', '2026-05-05 16:01:57', '2026-05-05 16:01:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta_satisfaccion`
--

CREATE TABLE `encuesta_satisfaccion` (
  `id_encuesta` int(11) NOT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_encuesta` datetime DEFAULT current_timestamp(),
  `calificacion_general` int(11) DEFAULT 5,
  `calificacion_comida` int(11) DEFAULT 5,
  `calificacion_servicio` int(11) DEFAULT 5,
  `calificacion_limpieza` int(11) DEFAULT 5,
  `calificacion_ambiente` int(11) DEFAULT 5,
  `recomendaria` tinyint(1) DEFAULT 1,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio`
--

CREATE TABLE `envio` (
  `id_envio` int(11) NOT NULL,
  `codigo_envio` varchar(20) DEFAULT NULL,
  `id_factura` int(11) NOT NULL,
  `id_repartidor` int(11) DEFAULT NULL,
  `id_tarifa_zona` int(11) DEFAULT NULL,
  `direccion_entrega` text NOT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `costo_envio` decimal(10,2) DEFAULT NULL,
  `estado` enum('Pendiente','Asignado','En_Camino','Entregado','Fallido','Cancelado') DEFAULT 'Pendiente',
  `fecha_programada` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion_proveedor`
--

CREATE TABLE `evaluacion_proveedor` (
  `id_evaluacion` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `calidad_producto` int(11) DEFAULT 5,
  `tiempo_entrega` int(11) DEFAULT 5,
  `atencion_servicio` int(11) DEFAULT 5,
  `precio_competitividad` int(11) DEFAULT 5,
  `puntaje_total` decimal(3,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento_especial`
--

CREATE TABLE `evento_especial` (
  `id_evento` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('Cumpleanos','Boda','Corporativo','Graduacion','Otro') DEFAULT 'Otro',
  `fecha_evento` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `cantidad_invitados` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_empleado_responsable` int(11) DEFAULT NULL,
  `menu_personalizado` text DEFAULT NULL,
  `costo_total` decimal(12,2) DEFAULT NULL,
  `anticipo` decimal(12,2) DEFAULT 0.00,
  `estado` enum('Reservado','Confirmado','En_Proceso','Completado','Cancelado') DEFAULT 'Reservado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_cabecera`
--

CREATE TABLE `factura_cabecera` (
  `id_factura` int(11) NOT NULL,
  `numero_factura` varchar(20) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_proforma` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `id_cupon` int(11) DEFAULT NULL,
  `id_impuesto` int(11) DEFAULT NULL,
  `tipo_documento` enum('Venta','Devolucion','Ajuste') DEFAULT 'Venta',
  `fecha_emision` datetime DEFAULT current_timestamp(),
  `estado` enum('Borrador','Emitida','Pagada','Anulada','Devuelta') DEFAULT 'Borrador',
  `total_neto` decimal(12,2) DEFAULT 0.00,
  `total_impuesto` decimal(12,2) DEFAULT 0.00,
  `total_pagar` decimal(12,2) DEFAULT 0.00,
  `descuento` decimal(12,2) DEFAULT 0.00,
  `monto_pagado` decimal(12,2) DEFAULT 0.00,
  `saldo_pendiente` decimal(12,2) DEFAULT 0.00,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factura_cabecera`
--

INSERT INTO `factura_cabecera` (`id_factura`, `numero_factura`, `id_pedido`, `id_proforma`, `id_cliente`, `id_empleado`, `id_unidad`, `id_cupon`, `id_impuesto`, `tipo_documento`, `fecha_emision`, `estado`, `total_neto`, `total_impuesto`, `total_pagar`, `descuento`, `monto_pagado`, `saldo_pendiente`, `observaciones`) VALUES
(1, 'FAC-001', NULL, NULL, 1, 1, 1, NULL, 1, 'Venta', '2026-04-30 20:55:49', 'Pagada', 150.00, 22.50, 172.50, 0.00, 0.00, 0.00, NULL),
(2, 'FAC-20260505-6255', NULL, NULL, 2, 1, 1, NULL, NULL, 'Venta', '2026-05-04 21:28:39', 'Pagada', 45.00, 6.75, 51.75, 0.00, 0.00, 0.00, NULL),
(3, 'FAC-20260505-8833', NULL, NULL, 2, 1, 1, NULL, NULL, 'Venta', '2026-05-04 21:28:47', 'Pagada', 45.00, 6.75, 51.75, 0.00, 0.00, 0.00, NULL),
(4, 'FAC-20260505-3572', NULL, NULL, 2, 1, 1, NULL, NULL, 'Venta', '2026-05-04 21:30:11', 'Pagada', 45.00, 6.75, 51.75, 0.00, 0.00, 0.00, NULL),
(5, 'FAC-20260505-3677', NULL, NULL, 3, 1, 1, NULL, NULL, 'Venta', '2026-05-04 21:35:42', 'Pagada', 535.00, 80.25, 615.25, 0.00, 0.00, 0.00, NULL),
(6, 'FAC-20260505-2897', NULL, NULL, 4, 1, 1, NULL, NULL, 'Venta', '2026-05-04 21:57:34', 'Pagada', 525.00, 78.75, 603.75, 0.00, 0.00, 0.00, NULL),
(7, 'FAC-20260506-5827', NULL, NULL, 5, 2, 1, NULL, NULL, 'Venta', '2026-05-05 18:54:36', 'Pagada', 135.00, 20.25, 155.25, 0.00, 0.00, 0.00, NULL),
(9, 'FAC-20260506-3970', NULL, NULL, 7, 2, 1, NULL, NULL, 'Venta', '2026-05-05 21:15:33', 'Pagada', 215.00, 32.25, 247.25, 0.00, 0.00, 0.00, NULL),
(10, 'FAC-20260506-2389', NULL, NULL, 8, 2, 1, NULL, NULL, 'Venta', '2026-05-05 21:40:01', 'Pagada', 255.00, 38.25, 293.25, 0.00, 0.00, 0.00, NULL),
(11, 'FAC-20260506-3645', NULL, NULL, 9, 2, 1, NULL, NULL, 'Venta', '2026-05-06 09:20:46', 'Pagada', 350.00, 52.50, 402.50, 0.00, 0.00, 0.00, NULL),
(12, 'FAC-20260513-5958', NULL, NULL, 10, 4, 1, NULL, NULL, 'Venta', '2026-05-13 08:44:47', 'Pagada', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL),
(13, 'FAC-20260513-8674', NULL, NULL, 11, 18, 1, NULL, NULL, 'Venta', '2026-05-13 08:47:38', 'Pagada', 240.00, 36.00, 276.00, 0.00, 0.00, 0.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle`
--

CREATE TABLE `factura_detalle` (
  `id_det_fac` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_u` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factura_detalle`
--

INSERT INTO `factura_detalle` (`id_det_fac`, `id_factura`, `id_producto`, `cantidad`, `precio_u`, `subtotal`) VALUES
(1, 5, 1, 1, 70.00, 70.00),
(2, 5, 2, 1, 280.00, 280.00),
(3, 5, 15, 1, 20.00, 20.00),
(4, 5, 16, 1, 35.00, 35.00),
(5, 5, 17, 1, 50.00, 50.00),
(6, 5, 18, 1, 25.00, 25.00),
(7, 5, 19, 1, 15.00, 15.00),
(8, 5, 20, 1, 40.00, 40.00),
(9, 6, 18, 1, 25.00, 25.00),
(10, 6, 16, 1, 35.00, 35.00),
(11, 6, 19, 1, 15.00, 15.00),
(12, 7, 17, 1, 50.00, 50.00),
(13, 7, 20, 1, 40.00, 40.00),
(16, 9, 1, 2, 70.00, 140.00),
(17, 9, 18, 1, 25.00, 25.00),
(18, 9, 17, 1, 50.00, 50.00),
(19, 10, 17, 1, 50.00, 50.00),
(20, 10, 16, 1, 35.00, 35.00),
(21, 10, 1, 1, 70.00, 70.00),
(22, 10, 18, 4, 25.00, 100.00),
(23, 11, 1, 5, 70.00, 350.00),
(24, 13, 1, 2, 70.00, 140.00),
(25, 13, 17, 2, 50.00, 100.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto_operativo`
--

CREATE TABLE `gasto_operativo` (
  `id_gasto` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_centro_costo` int(11) DEFAULT NULL,
  `id_cuenta_contable` int(11) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha_gasto` date NOT NULL,
  `tipo_gasto` enum('Servicios','Mantenimiento','Publicidad','Impuestos','Otros') DEFAULT 'Otros',
  `metodo_pago` varchar(30) DEFAULT NULL,
  `id_empleado_registro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_fidelidad`
--

CREATE TABLE `historial_fidelidad` (
  `id_historial` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `tipo_mov` enum('Acumulacion','Canje','Ajuste','Expiracion') NOT NULL,
  `puntos` int(11) NOT NULL,
  `saldo_anterior` int(11) DEFAULT NULL,
  `saldo_posterior` int(11) DEFAULT NULL,
  `fecha_mov` datetime DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_precio`
--

CREATE TABLE `historial_precio` (
  `id_historial` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_anterior` decimal(10,2) DEFAULT NULL,
  `precio_nuevo` decimal(10,2) NOT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp(),
  `id_empleado_cambia` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_empleado`
--

CREATE TABLE `horario_empleado` (
  `id_horario` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo') DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `es_descanso` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

CREATE TABLE `impuesto` (
  `id_impuesto` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL,
  `tipo` enum('IVA','ISR','Municipal','Otro') DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `impuesto`
--

INSERT INTO `impuesto` (`id_impuesto`, `nombre`, `porcentaje`, `tipo`, `activo`) VALUES
(1, 'IVA', 15.00, 'IVA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo`
--

CREATE TABLE `insumo` (
  `id_insumo` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `unidad_medida` varchar(20) NOT NULL DEFAULT 'UN',
  `stock_minimo` decimal(10,3) DEFAULT 0.000,
  `costo_promedio` decimal(10,3) DEFAULT 0.000,
  `alergenos` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `insumo`
--

INSERT INTO `insumo` (`id_insumo`, `codigo`, `nombre`, `id_categoria`, `unidad_medida`, `stock_minimo`, `costo_promedio`, `alergenos`) VALUES
(1, 'INS-001', 'Harina de Trigo', 1, 'kg', 10.000, 12.500, NULL),
(2, 'INS-002', 'Aceite Vegetal', 1, 'lt', 5.000, 45.000, NULL),
(3, 'INS-003', 'Tomate Fresco', 1, 'kg', 8.000, 8.000, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_fisico`
--

CREATE TABLE `inventario_fisico` (
  `id_inventario` int(11) NOT NULL,
  `id_bodega` int(11) NOT NULL,
  `fecha_programada` date DEFAULT NULL,
  `fecha_ejecucion` date DEFAULT NULL,
  `estado` enum('Programado','En_Proceso','Completado','Cancelado') DEFAULT 'Programado',
  `id_empleado_responsable` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `id_lote` int(11) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `cantidad_actual` decimal(10,3) DEFAULT 0.000,
  `estado` enum('Disponible','Reservado','Cuarentena','Vencido') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresia`
--

CREATE TABLE `membresia` (
  `id_membresia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `costo_mensual` decimal(10,2) DEFAULT NULL,
  `beneficios` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `membresia`
--

INSERT INTO `membresia` (`id_membresia`, `nombre`, `descripcion`, `descuento_porcentaje`, `costo_mensual`, `beneficios`, `activo`) VALUES
(1, 'Bronce', NULL, 5.00, 0.00, 'Acumulación de puntos', 1),
(2, 'Plata', NULL, 10.00, 150.00, 'Puntos dobles + 10% descuento', 1),
(3, 'Oro', NULL, 15.00, 300.00, 'Puntos triples + 15% descuento + envío gratis', 1),
(4, 'Bronce', NULL, 5.00, 0.00, 'Acumulación de puntos', 1),
(5, 'Plata', NULL, 10.00, 150.00, 'Puntos dobles + 10% descuento', 1),
(6, 'Oro', NULL, 15.00, 300.00, 'Puntos triples + 15% descuento + envío gratis', 1),
(7, 'Bronce', NULL, 5.00, NULL, NULL, 1),
(8, 'Plata', NULL, 10.00, NULL, NULL, 1),
(9, 'Oro', NULL, 15.00, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `merma`
--

CREATE TABLE `merma` (
  `id_merma` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `id_bodega` int(11) DEFAULT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `tipo_merma` enum('Vencimiento','Deterioro','Error_Humano','Robo','Mermas_Proceso') DEFAULT 'Deterioro',
  `costo_merma` decimal(10,2) DEFAULT NULL,
  `id_empleado_registro` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id_mesa` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `numero_mesa` int(11) DEFAULT NULL,
  `zona` varchar(50) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `estado` enum('Libre','Ocupada','Reservada','Mantenimiento') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `id_metodo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` enum('Efectivo','Tarjeta_Debito','Tarjeta_Credito','Transferencia','Wallet','Cheque','Credito') DEFAULT 'Efectivo',
  `comision_porcentaje` decimal(5,2) DEFAULT 0.00,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`id_metodo`, `nombre`, `tipo`, `comision_porcentaje`, `activo`) VALUES
(1, 'Efectivo', 'Efectivo', 0.00, 1),
(2, 'Tarjeta Débito', 'Tarjeta_Debito', 0.00, 1),
(3, 'Tarjeta Crédito', 'Tarjeta_Credito', 0.00, 1),
(4, 'Transferencia', 'Transferencia', 0.00, 1),
(5, 'Wallet Móvil', 'Wallet', 0.00, 1),
(6, 'Efectivo', 'Efectivo', 0.00, 1),
(7, 'Tarjeta Débito', 'Tarjeta_Debito', 0.00, 1),
(8, 'Tarjeta Crédito', 'Tarjeta_Credito', 0.00, 1),
(9, 'Transferencia', 'Transferencia', 0.00, 1),
(10, 'Wallet Móvil', 'Wallet', 0.00, 1),
(11, 'Efectivo', 'Efectivo', 0.00, 1),
(12, 'Tarjeta Débito', '', 0.00, 1),
(13, 'Tarjeta Crédito', '', 0.00, 1),
(14, 'Transferencia', 'Transferencia', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_bodega`
--

CREATE TABLE `movimiento_bodega` (
  `id_mov` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `id_bodega` int(11) NOT NULL,
  `id_lote` int(11) DEFAULT NULL,
  `tipo` enum('Entrada','Salida','Transferencia','Ajuste','Merma','Consumo') DEFAULT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `costo_unitario` decimal(10,3) DEFAULT NULL,
  `fecha_movimiento` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_cabecera`
--

CREATE TABLE `nomina_cabecera` (
  `id_nomina` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `dias_trabajados` int(11) DEFAULT 30,
  `salario_base` decimal(10,2) DEFAULT NULL,
  `horas_extras` int(11) DEFAULT 0,
  `monto_horas_extras` decimal(10,2) DEFAULT 0.00,
  `bonificaciones` decimal(10,2) DEFAULT 0.00,
  `total_ingresos` decimal(10,2) DEFAULT 0.00,
  `total_deducciones` decimal(10,2) DEFAULT 0.00,
  `salario_neto` decimal(10,2) DEFAULT 0.00,
  `estado` enum('Calculado','Aprobado','Pagado','Anulado') DEFAULT 'Calculado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_detalle`
--

CREATE TABLE `nomina_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_nomina` int(11) NOT NULL,
  `id_concepto` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `tipo` enum('Ingreso','Deduccion') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_periodo`
--

CREATE TABLE `nomina_periodo` (
  `id_periodo` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `estado` enum('Abierto','Cerrado','Procesado','Pagado') DEFAULT 'Abierto',
  `total_empleados` int(11) DEFAULT 0,
  `total_bruto` decimal(14,2) DEFAULT 0.00,
  `total_deducciones` decimal(14,2) DEFAULT 0.00,
  `total_neto` decimal(14,2) DEFAULT 0.00,
  `id_empleado_procesa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion_sistema`
--

CREATE TABLE `notificacion_sistema` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo` enum('Info','Advertencia','Error','Urgente') DEFAULT 'Info',
  `titulo` varchar(150) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_lectura` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compra`
--

CREATE TABLE `orden_compra` (
  `id_orden` int(11) NOT NULL,
  `numero_orden` varchar(20) DEFAULT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_empleado_autoriza` int(11) DEFAULT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_entrega_esperada` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `impuesto` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `estado` enum('Borrador','Enviada','Aprobada','Recibida','Cancelada') DEFAULT 'Borrador',
  `condiciones_pago` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_produccion`
--

CREATE TABLE `orden_produccion` (
  `id_orden` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_solicitada` decimal(10,2) NOT NULL,
  `cantidad_producida` decimal(10,2) DEFAULT 0.00,
  `estado` enum('Pendiente','En_Proceso','Completada','Cancelada') DEFAULT 'Pendiente',
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `id_empleado_responsable` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `numero_referencia` varchar(50) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `id_empleado_registro` int(11) DEFAULT NULL,
  `estado` enum('Pendiente','Procesado','Completado','Rechazado','Reversado') DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_cabecera`
--

CREATE TABLE `pedido_cabecera` (
  `id_pedido` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_mesa` int(11) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `tipo` enum('Mesa','Para_Llevar','Delivery','Evento_Corporativo') DEFAULT NULL,
  `estado` enum('Abierto','Preparando','Listo','Entregado','Cancelado') DEFAULT 'Abierto',
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `accion` enum('Crear','Leer','Actualizar','Eliminar','Ejecutar','Reportar') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `codigo`, `nombre`, `descripcion`, `modulo`, `accion`) VALUES
(1, 'ventas_crear', 'Crear Venta', NULL, 'Ventas', 'Crear'),
(2, 'ventas_ver', 'Ver Ventas', NULL, 'Ventas', 'Leer'),
(3, 'ventas_anular', 'Anular Venta', NULL, 'Ventas', 'Eliminar'),
(4, 'inventario_ver', 'Ver Inventario', NULL, 'Inventario', 'Leer'),
(5, 'productos_crear', 'Crear Producto', NULL, 'Productos', 'Crear'),
(6, 'reportes_ver', 'Ver Reportes', NULL, 'Reportes', 'Reportar'),
(7, 'usuarios_crear', 'Crear Usuario', NULL, 'Usuarios', 'Crear'),
(8, 'nomina_ver', 'Ver Nómina', NULL, 'RRHH', 'Leer'),
(9, 'contabilidad_ver', 'Ver Contabilidad', NULL, 'Finanzas', 'Leer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `tipo_persona` enum('Nacional','Extranjera') DEFAULT 'Nacional',
  `tipo_identificacion` enum('Cedula','Pasaporte','RUC','DNI','Residencia','Nit') NOT NULL,
  `numero_identificacion` varchar(20) NOT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `Apellidos` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('Masculino','Femenino','Otro') DEFAULT 'Masculino',
  `genero` enum('Masculino','Femenino','No_Binario','Género_Fluido','Otro','Prefiero_no_decir') DEFAULT NULL,
  `orientacion_sexual` enum('Heterosexual','Homosexual','Bisexual','Pansexual','Asexual','Otro','Prefiero_no_decir') DEFAULT NULL,
  `pronombres_preferidos` enum('Él','Ella','Elle','Ellx','Prefiero_no_decir','No_especificar') DEFAULT NULL,
  `consentimiento_datos_sensibles` tinyint(1) DEFAULT 0,
  `fecha_consentimiento` datetime DEFAULT NULL,
  `estado_civil` enum('Soltero','Casado','Divorciado','Viudo','Union_Libre') DEFAULT 'Soltero',
  `email` varchar(100) DEFAULT NULL,
  `telefono_principal` varchar(20) DEFAULT NULL,
  `telefono_emergencia` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `departamento_estado` varchar(50) DEFAULT NULL,
  `pais` varchar(2) DEFAULT 'NI',
  `Enfermedades` text DEFAULT NULL,
  `Tipo_sangre` varchar(5) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_eliminacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `tipo_persona`, `tipo_identificacion`, `numero_identificacion`, `primer_nombre`, `segundo_nombre`, `Apellidos`, `fecha_nacimiento`, `sexo`, `genero`, `orientacion_sexual`, `pronombres_preferidos`, `consentimiento_datos_sensibles`, `fecha_consentimiento`, `estado_civil`, `email`, `telefono_principal`, `telefono_emergencia`, `direccion`, `ciudad`, `departamento_estado`, `pais`, `Enfermedades`, `Tipo_sangre`, `fecha_registro`, `fecha_modificacion`, `fecha_eliminacion`) VALUES
(1, 'Nacional', 'Cedula', '001-010595-1000A', 'Carlos', 'Alberto', 'Ulloa', '1995-05-01', 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', 'carlos@nicafood.ni', '+505 8800-1111', NULL, '', 'Managua', 'Managua', 'NI', NULL, NULL, '2026-05-01 02:37:00', '2026-05-13 03:36:24', NULL),
(2, '', 'Cedula', '000-000000-0000C', 'Cliente', '', NULL, NULL, 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', '', '0000-0000', NULL, 'N/A', 'Managua', NULL, 'NI', NULL, NULL, '2026-05-05 03:28:39', '2026-05-07 01:44:27', NULL),
(3, '', 'Cedula', '987654321b', 'Jose', '', 'antonio', NULL, 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', 'Apexl70m22@gmail.co', '+50522334455', NULL, 'N/A', 'Managua', NULL, 'NI', NULL, NULL, '2026-05-05 03:35:42', '2026-05-07 01:44:27', NULL),
(4, 'Nacional', 'Cedula', '001-010190-1000A', 'Alex', 'marck', 'Amador', '1990-01-01', 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', 'alexd@gmail.com', '+50558846875', NULL, 'N/A', 'Managua', 'Managua', 'NI', NULL, NULL, '2026-05-05 03:57:34', '2026-05-12 02:51:29', NULL),
(5, '', 'Cedula', '001-123456-1000B', 'Ana', 'María', 'López Vega', '1990-05-15', 'Femenino', 'Femenino', NULL, NULL, 0, NULL, 'Casado', 'ana.lopez@nicafood.ni', '+505 8800-2222', NULL, 'Res. Los Robles, Calle 15', 'Managua', 'Managua', 'NI', NULL, NULL, '2026-05-05 16:06:59', '2026-05-07 01:44:27', NULL),
(6, '', 'Cedula', '001-654321-1000C', 'Roberto', 'Antonio', 'García Ruiz', '1985-08-22', 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', 'roberto.garcia@nicafood.ni', '+505 8800-3333', NULL, 'Barrio San Judas, Casa 45', 'Managua', 'Managua', 'NI', NULL, NULL, '2026-05-05 16:06:59', '2026-05-07 01:44:27', NULL),
(7, '', 'Cedula', '001-987654-1000D', 'María', 'José', 'Torres Solís', '1995-03-10', 'Femenino', 'Femenino', NULL, NULL, 0, NULL, 'Soltero', 'maria.torres@nicafood.ni', '+505 8800-4444', NULL, 'Col. Centroamérica, Módulo 8', 'Managua', 'Managua', 'NI', NULL, NULL, '2026-05-05 16:06:59', '2026-05-07 01:44:27', NULL),
(8, '', 'Cedula', '001-170106-1017J', 'Victor', 'Alonso', 'Lopez', '2006-01-17', 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Divorciado', 'Alop107@gmail.com', '85456340', '', 'Masaya\n', 'Managua', 'Managua', 'NI', 'Mariscos', '', '2026-05-06 00:54:36', '2026-05-07 01:44:27', NULL),
(10, '', 'Cedula', '001-170101-1221J', 'Jose ', 'antionio', 'parrales', NULL, 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', 'jos123@gmail.com', '12345679', '98675423', 'Casa de color maron ', 'Managua', 'Managua', 'NI', NULL, NULL, '2026-05-06 03:15:33', '2026-05-07 01:44:27', NULL),
(13, '', 'Cedula', '001-170106-1017M', 'Jose ', 'antionio', 'gonzales', '2006-01-17', 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Casado', 'jos1234@gmail.com', '12345679', '32658195', 'Casa de color rojo frente en nicatronic', 'Managua', 'Managua', 'NI', 'Azucar', 'O+', '2026-05-06 03:40:01', '2026-05-07 01:44:27', NULL),
(14, 'Nacional', 'Cedula', '161-100805-1003M', 'Carlos', 'Gabriel', 'Hernadez', '2005-08-10', 'Masculino', 'Masculino', NULL, NULL, 0, NULL, 'Soltero', 'gabocarl09@gmail.com', '87082589', '84207677', 'Esteli', 'Managua', 'Managua', 'NI', 'Asma', 'O+', '2026-05-06 15:20:46', '2026-05-07 01:44:27', NULL),
(27, 'Nacional', 'Cedula', '001-151006-1056F', 'Darling ', 'Massiel', 'Sánchez Martínez ', '2006-10-15', NULL, NULL, NULL, NULL, 0, NULL, 'Casado', 'Massie1510@gmail.com', '7554 8165', NULL, 'En un lugar muy lelajo', 'Tipitapa', 'Tipitapa', 'NI', NULL, 'O+', '2026-05-13 01:54:50', '2026-05-13 01:54:50', NULL),
(28, 'Nacional', 'Cedula', '001-070105-1001A', 'Carlos Alberto ', 'Ulloa', 'Jarquin', '2005-01-07', 'Masculino', NULL, NULL, NULL, 0, NULL, 'Casado', 'ulloajarquin2005@gmail.com', '75516752', NULL, 'San Sebastián ', 'Managua', 'Managua', 'NI', NULL, 'O-', '2026-05-13 04:05:06', '2026-05-13 04:05:06', NULL),
(29, 'Nacional', 'Cedula', '401-110307-1004V', 'Luis', 'Efrain', 'Acevedo', '2007-03-11', 'Masculino', NULL, NULL, NULL, 0, NULL, 'Soltero', 'pepe0201@gmail.com', '89671537', '75918342', 'Masaya, lugar de mono', 'Masaya', 'Masaya', 'NI', NULL, 'A+', '2026-05-13 13:39:01', '2026-05-13 13:39:01', NULL),
(32, 'Nacional', 'Cedula', '161-100805-1003H', 'Carlos', 'Gabriel', 'Hernadez', '2005-08-10', 'Masculino', '', 'Heterosexual', 'Elle', 1, '2026-05-13 16:43:45', 'Soltero', 'gabocarl0906@gmail.com', '87082589', '88889577', 'al cielito mi estimado', 'Managua', 'Managua', 'NI', 'N/A', 'A-', '2026-05-13 14:44:47', '2026-05-13 14:44:47', NULL),
(33, 'Nacional', 'Cedula', '003-070701-2004A', 'Pepe', 'Antonio', 'Perex', '2001-07-07', 'Masculino', '', 'Heterosexual', 'Él', 0, '2026-05-13 16:45:28', 'Casado', 'pepe2001@gmail.com', '98674534', '15347829', 'En casa', 'Managua', 'Managua', 'NI', 'Al trabajo', 'B+', '2026-05-13 14:47:38', '2026-05-13 14:47:38', NULL),
(34, 'Nacional', 'Cedula', '001-090107-1036G', 'Marlon ', 'Ivan', 'Urbina Jarquin', '2007-01-09', 'Masculino', NULL, NULL, NULL, 0, NULL, 'Soltero', 'Marlo2007@gmail.com', '+50575029454', NULL, 'K12 Carretera Norte frente a la universidad La Agraria.', 'Managua', 'Managua', 'NI', NULL, 'A+', '2026-05-13 14:59:55', '2026-05-13 14:59:55', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantilla_email`
--

CREATE TABLE `plantilla_email` (
  `id_plantilla` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `cuerpo_html` text DEFAULT NULL,
  `cuerpo_texto` text DEFAULT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `plantilla_email`
--

INSERT INTO `plantilla_email` (`id_plantilla`, `codigo`, `nombre`, `asunto`, `cuerpo_html`, `cuerpo_texto`, `variables`, `activo`) VALUES
(1, 'CONF_PEDIDO', 'Confirmación de Pedido', 'Tu pedido #{numero} ha sido confirmado', '<html><body><h2>¡Gracias por tu pedido!</h2><p>Hemos recibido tu orden #{numero}.</p><p>Total: C${total}</p></body></html>', NULL, NULL, 1),
(2, 'FACTURA_EMAIL', 'Factura Digital', 'Factura #{numero} - NicaFood', '<html><body><h2>Factura Electrónica</h2><p>Adjuntamos su factura #{numero}.</p><p>Gracias por su compra.</p></body></html>', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuentas`
--

CREATE TABLE `plan_cuentas` (
  `id_cuenta` int(11) NOT NULL,
  `codigo_cuenta` varchar(20) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo` enum('Activo','Pasivo','Patrimonio','Ingreso','Gasto') NOT NULL,
  `subtipo` varchar(50) DEFAULT NULL,
  `nivel` int(11) DEFAULT 1,
  `cuenta_padre` int(11) DEFAULT NULL,
  `naturaleza` enum('Deudora','Acreedora') DEFAULT 'Deudora'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `codigo_producto` varchar(20) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `precio_menu` decimal(10,2) DEFAULT NULL,
  `costo_estimado` decimal(10,2) DEFAULT NULL,
  `tiempo_preparacion_min` int(11) DEFAULT NULL,
  `visible_pos` tinyint(1) DEFAULT 1,
  `disponible` tinyint(1) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1,
  `descripcion` text DEFAULT NULL,
  `calorias` int(11) DEFAULT NULL,
  `es_vegetariano` tinyint(1) DEFAULT 0,
  `es_vegano` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `codigo_producto`, `nombre`, `id_categoria`, `precio_venta`, `precio_menu`, `costo_estimado`, `tiempo_preparacion_min`, `visible_pos`, `disponible`, `activo`, `descripcion`, `calorias`, `es_vegetariano`, `es_vegano`) VALUES
(1, 'PROD014', 'Hamburguesa Clásica', 1, 70.00, 70.00, NULL, NULL, 1, 1, 1, NULL, NULL, 0, 0),
(2, 'PROD-002', 'Pizza Familiar', 1, 280.00, 280.00, NULL, NULL, 1, 1, 1, NULL, NULL, 0, 0),
(3, 'PROD-003', 'Refresco 500ml', 2, 35.00, 35.00, NULL, NULL, 1, 1, 1, NULL, NULL, 0, 0),
(4, 'PROD-004', 'Helado', 3, 60.00, 60.00, NULL, NULL, 1, 1, 1, NULL, NULL, 0, 0),
(5, 'POST001', 'Alfajores', 3, 25.00, 25.00, 12.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(6, 'POST002', 'Brownie', 3, 28.00, 28.00, 14.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(7, 'POST003', 'Flan', 3, 22.00, 22.00, 10.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(8, 'POST004', 'Pay de Limón', 3, 27.00, 27.00, 13.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(9, 'POST005', 'Pastel de Chocolate', 3, 30.00, 30.00, 15.00, 10, 1, 1, 1, NULL, NULL, 0, 0),
(10, 'POST006', 'Gelatina', 3, 18.00, 18.00, 8.00, 3, 1, 1, 1, NULL, NULL, 0, 0),
(11, 'POST007', 'Helado', 3, 20.00, 20.00, 10.00, 2, 1, 1, 1, NULL, NULL, 0, 0),
(12, 'POST008', 'Crepas', 3, 35.00, 35.00, 18.00, 8, 1, 1, 1, NULL, NULL, 0, 0),
(13, 'POST009', 'Enrejados', 3, 26.00, 26.00, 12.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(14, 'POST010', 'Frutas', 3, 23.00, 23.00, 15.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(15, 'PROD001', 'Sandwich', 1, 20.00, 20.00, 10.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(16, 'PROD002', 'Pizza', 1, 35.00, 35.00, 18.00, 15, 1, 1, 1, NULL, NULL, 0, 0),
(17, 'PROD003', 'Hamburguesa', 1, 50.00, 50.00, 25.00, 10, 1, 1, 1, NULL, NULL, 0, 0),
(18, 'PROD004', 'Ensalada', 1, 25.00, 25.00, 12.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(19, 'PROD005', 'Tacos', 1, 15.00, 15.00, 7.00, 5, 1, 1, 1, NULL, NULL, 0, 0),
(20, 'PROD006', 'Pupusa', 1, 40.00, 40.00, 20.00, 8, 1, 1, 1, NULL, NULL, 0, 0),
(21, 'PROD007', 'Agua Mineral', 2, 10.00, 10.00, 4.00, 1, 1, 1, 1, NULL, NULL, 0, 0),
(22, 'PROD008', 'Café', 2, 25.00, 25.00, 8.00, 3, 1, 1, 1, NULL, NULL, 0, 0),
(23, 'PROD009', 'Gaseosa', 2, 15.00, 15.00, 6.00, 1, 1, 1, 1, NULL, NULL, 0, 0),
(24, 'PROD010', 'Jugo de Naranja', 2, 20.00, 20.00, 10.00, 3, 1, 1, 1, NULL, NULL, 0, 0),
(25, 'PROD011', 'Limonada', 2, 15.00, 15.00, 6.00, 3, 1, 1, 1, NULL, NULL, 0, 0),
(26, 'PROD012', 'Rojita', 2, 22.00, 22.00, 9.00, 2, 1, 1, 1, NULL, NULL, 0, 0),
(27, 'PROD013', 'Té Helado', 2, 18.00, 18.00, 7.00, 3, 1, 1, 1, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma_cabecera`
--

CREATE TABLE `proforma_cabecera` (
  `id_proforma` int(11) NOT NULL,
  `numero_proforma` varchar(20) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `impuesto` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `estado` enum('Borrador','Enviada','Aprobada','Rechazada','Convertida') DEFAULT 'Borrador',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma_detalle`
--

CREATE TABLE `proforma_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_proforma` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `codigo_proveedor` varchar(20) DEFAULT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `nombre_comercial` varchar(150) DEFAULT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `pais` varchar(2) DEFAULT 'NI',
  `categoria` enum('Insumos','Bebidas','Limpieza','Equipamiento','Servicios') DEFAULT 'Insumos',
  `credito_dias` int(11) DEFAULT 0,
  `limite_credito` decimal(12,2) DEFAULT NULL,
  `calificacion` int(11) DEFAULT 5,
  `estado` enum('Activo','Inactivo','Suspendido') DEFAULT 'Activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `punto_cliente`
--

CREATE TABLE `punto_cliente` (
  `id_cliente` int(11) NOT NULL,
  `saldo_puntos` int(11) DEFAULT 0,
  `puntos_ganados_total` int(11) DEFAULT 0,
  `puntos_canjeados_total` int(11) DEFAULT 0,
  `id_membresia` int(11) DEFAULT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `queja_reclamo`
--

CREATE TABLE `queja_reclamo` (
  `id_reclamo` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `fecha_reclamo` datetime DEFAULT current_timestamp(),
  `tipo` enum('Calidad_Comida','Servicio','Limpieza','Tiempo_Espera','Precio','Otro') DEFAULT 'Otro',
  `descripcion` text NOT NULL,
  `prioridad` enum('Baja','Media','Alta','Critica') DEFAULT 'Media',
  `estado` enum('Abierto','En_Proceso','Resuelto','Cerrado') DEFAULT 'Abierto',
  `id_empleado_asigna` int(11) DEFAULT NULL,
  `fecha_resolucion` datetime DEFAULT NULL,
  `solucion_aplicada` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcion_compra`
--

CREATE TABLE `recepcion_compra` (
  `id_recepcion` int(11) NOT NULL,
  `numero_recepcion` varchar(20) DEFAULT NULL,
  `id_orden_compra` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_bodega` int(11) DEFAULT NULL,
  `fecha_recepcion` datetime DEFAULT current_timestamp(),
  `id_empleado_recibe` int(11) DEFAULT NULL,
  `estado` enum('Pendiente','Parcial','Completa','Rechazada') DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_cabecera`
--

CREATE TABLE `receta_cabecera` (
  `id_receta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_receta` varchar(150) DEFAULT NULL,
  `version` varchar(10) DEFAULT '1.0',
  `rendimiento_porcion` int(11) DEFAULT 1,
  `unidad_medida` varchar(20) DEFAULT NULL,
  `tiempo_preparacion_min` int(11) DEFAULT NULL,
  `tiempo_coccion_min` int(11) DEFAULT NULL,
  `dificultad` enum('Baja','Media','Alta') DEFAULT 'Media',
  `costo_total` decimal(10,2) DEFAULT NULL,
  `margen_objetivo` decimal(5,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_detalle`
--

CREATE TABLE `receta_detalle` (
  `id_receta` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `unidad_medida` varchar(20) DEFAULT NULL,
  `porcentaje_merma` decimal(5,2) DEFAULT 0.00,
  `es_opcional` tinyint(1) DEFAULT 0,
  `orden_preparacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repartidor`
--

CREATE TABLE `repartidor` (
  `id_repartidor` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `tipo_vehiculo` enum('Motocicleta','Bicicleta','Automovil','Furgoneta') DEFAULT 'Motocicleta',
  `placa_vehiculo` varchar(20) DEFAULT NULL,
  `licencia_conducir` varchar(30) DEFAULT NULL,
  `estado` enum('Disponible','Ocupado','Descanso','Inactivo') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_config`
--

CREATE TABLE `reporte_config` (
  `id_reporte` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('Ventas','Inventario','Nomina','Contable','Cliente','General') DEFAULT 'General',
  `query_sql` text DEFAULT NULL,
  `parametros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parametros`)),
  `frecuencia` enum('Diario','Semanal','Mensual','Anual','On_Demand') DEFAULT 'On_Demand',
  `ultimo_generado` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reporte_config`
--

INSERT INTO `reporte_config` (`id_reporte`, `nombre`, `descripcion`, `tipo`, `query_sql`, `parametros`, `frecuencia`, `ultimo_generado`, `activo`) VALUES
(1, 'Ventas del Día', NULL, 'Ventas', 'SELECT * FROM factura_cabecera WHERE DATE(fecha_emision) = CURDATE()', NULL, 'Diario', NULL, 1),
(2, 'Stock Bajo', NULL, 'Inventario', 'SELECT * FROM insumo WHERE stock_minimo > 0 AND stock_minimo > (SELECT SUM(cantidad_actual) FROM lote WHERE id_insumo = insumo.id_insumo)', NULL, 'Diario', NULL, 1),
(3, 'Nómina Mensual', NULL, 'Nomina', 'SELECT * FROM nomina_cabecera WHERE MONTH(fecha_pago) = MONTH(CURDATE())', NULL, 'Mensual', NULL, 1),
(4, 'Ventas del Día', NULL, 'Ventas', 'SELECT * FROM factura_cabecera WHERE DATE(fecha_emision) = CURDATE()', NULL, 'Diario', NULL, 1),
(5, 'Stock Bajo', NULL, 'Inventario', 'SELECT * FROM insumo WHERE stock_minimo > 0 AND stock_minimo > (SELECT SUM(cantidad_actual) FROM lote WHERE id_insumo = insumo.id_insumo)', NULL, 'Diario', NULL, 1),
(6, 'Nómina Mensual', NULL, 'Nomina', 'SELECT * FROM nomina_cabecera WHERE MONTH(fecha_pago) = MONTH(CURDATE())', NULL, 'Mensual', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_mesa` int(11) DEFAULT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_reserva` time NOT NULL,
  `cantidad_personas` int(11) NOT NULL,
  `estado` enum('Confirmada','Cancelada','Completada','No_Show') DEFAULT 'Confirmada',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'Administrador', 'Acceso total al sistema', '2026-05-01 02:37:00'),
(2, 'Cajero', 'Manejo de caja y ventas', '2026-05-01 02:37:00'),
(3, 'Cocinero', 'Gestión de cocina', '2026-05-01 02:37:00'),
(4, 'Mesero', 'Atención en sala', '2026-05-01 02:37:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol`, `id_permiso`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesion_activa`
--

CREATE TABLE `sesion_activa` (
  `id_sesion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT current_timestamp(),
  `fecha_fin` datetime DEFAULT NULL,
  `estado` enum('Activa','Expirada','Cerrada') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_cotizacion`
--

CREATE TABLE `solicitud_cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `id_empleado_solicitante` int(11) DEFAULT NULL,
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `fecha_limite_respuesta` date DEFAULT NULL,
  `estado` enum('Pendiente','En_Proceso','Completada','Cancelada') DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa_zona`
--

CREATE TABLE `tarifa_zona` (
  `id_tarifa` int(11) NOT NULL,
  `nombre_zona` varchar(50) NOT NULL,
  `radio_km` decimal(5,2) DEFAULT NULL,
  `costo_envio` decimal(10,2) NOT NULL,
  `tiempo_estimado_min` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tarifa_zona`
--

INSERT INTO `tarifa_zona` (`id_tarifa`, `nombre_zona`, `radio_km`, `costo_envio`, `tiempo_estimado_min`, `activo`) VALUES
(1, 'Zona Centro', 3.00, 30.00, 20, 1),
(2, 'Zona Norte', 5.00, 50.00, 30, 1),
(3, 'Zona Sur', 5.00, 50.00, 35, 1),
(4, 'Zona Este', 7.00, 70.00, 45, 1),
(5, 'Zona Oeste', 7.00, 70.00, 45, 1),
(6, 'Zona Centro', 3.00, 30.00, 20, 1),
(7, 'Zona Norte', 5.00, 50.00, 30, 1),
(8, 'Zona Sur', 5.00, 50.00, 35, 1),
(9, 'Zona Este', 7.00, 70.00, 45, 1),
(10, 'Zona Oeste', 7.00, 70.00, 45, 1),
(11, 'Zona Centro', NULL, 30.00, 20, 1),
(12, 'Zona Norte', NULL, 50.00, 35, 1),
(13, 'Zona Sur', NULL, 50.00, 35, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tracking_envio`
--

CREATE TABLE `tracking_envio` (
  `id_tracking` int(11) NOT NULL,
  `id_envio` int(11) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `estado` enum('En_Restaurante','En_Camino','Cerca_Destino','Entregado') DEFAULT 'En_Restaurante',
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_operativa`
--

CREATE TABLE `unidad_operativa` (
  `id_unidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `tipo` enum('Restaurante','Dark_Kitchen','Punto_Retiro','Almacen_Central','Oficina') DEFAULT 'Restaurante',
  `estado` enum('Activo','Inactivo','Mantenimiento') DEFAULT 'Activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_empresa` int(11) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `horario_apertura` time DEFAULT NULL,
  `horario_cierre` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `unidad_operativa`
--

INSERT INTO `unidad_operativa` (`id_unidad`, `nombre`, `codigo`, `tipo`, `estado`, `fecha_registro`, `id_empresa`, `telefono`, `email`, `direccion`, `ciudad`, `horario_apertura`, `horario_cierre`) VALUES
(1, 'Sucursal Principal', 'UNI-01', 'Restaurante', 'Activo', '2026-05-01 02:37:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `mfa_activo` tinyint(1) DEFAULT 0,
  `fecha_vencimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_empleado`, `id_rol`, `username`, `contraseña`, `estado`, `ultimo_acceso`, `fecha_registro`, `mfa_activo`, `fecha_vencimiento`) VALUES
(1, 1, 1, 'Carlos', 'Caja2026*', 1, '2026-05-13 08:00:47', '2026-05-01 02:37:00', 0, NULL),
(2, 2, 1, 'Darling', 'Massiel2026', 1, '2026-05-05 10:08:29', '2026-05-05 16:06:59', 0, NULL),
(3, 3, 1, 'roberto.garcia', 'Admin2026!', 1, NULL, '2026-05-05 16:06:59', 0, NULL),
(4, 4, 2, 'maria.torres', 'Caja2026*', 1, NULL, '2026-05-05 16:06:59', 0, NULL),
(5, 17, 1, 'jefa_Darling', 'Jefa12345', 1, '2026-05-13 08:53:15', '2026-05-13 01:54:50', 0, NULL),
(6, 18, 1, 'Carlos_07', 'Jefe2026*', 1, '2026-05-13 09:02:26', '2026-05-13 04:05:06', 0, NULL),
(7, 19, 3, 'Acevedo07', '12345', 1, NULL, '2026-05-13 13:39:01', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id_vacacion` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias_disfrutados` int(11) DEFAULT NULL,
  `dias_pendientes` int(11) DEFAULT NULL,
  `estado` enum('Pendiente','Aprobado','Rechazado','Disfrutado') DEFAULT 'Pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `webhook_integracion`
--

CREATE TABLE `webhook_integracion` (
  `id_webhook` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `metodo` enum('GET','POST','PUT','DELETE') DEFAULT 'POST',
  `eventos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`eventos`)),
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`headers`)),
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_disparo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activo_fijo`
--
ALTER TABLE `activo_fijo`
  ADD PRIMARY KEY (`id_activo`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_cuenta_contable` (`id_cuenta_contable`);

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id_area`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_depto` (`id_depto`);

--
-- Indices de la tabla `asiento_cabecera`
--
ALTER TABLE `asiento_cabecera`
  ADD PRIMARY KEY (`id_asiento`),
  ADD UNIQUE KEY `numero_asiento` (`numero_asiento`),
  ADD KEY `id_empleado_registro` (`id_empleado_registro`);

--
-- Indices de la tabla `asiento_detalle`
--
ALTER TABLE `asiento_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_asiento` (`id_asiento`),
  ADD KEY `id_cuenta` (`id_cuenta`),
  ADD KEY `id_centro_costo` (`id_centro_costo`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `idx_fecha_empleado` (`fecha`,`id_empleado`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `auditoria_inventario`
--
ALTER TABLE `auditoria_inventario`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `id_inventario` (`id_inventario`),
  ADD KEY `id_insumo` (`id_insumo`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`id_banco`);

--
-- Indices de la tabla `bitacora_log`
--
ALTER TABLE `bitacora_log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `bodega`
--
ALTER TABLE `bodega`
  ADD PRIMARY KEY (`id_bodega`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_unidad` (`id_unidad`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `centro_costo`
--
ALTER TABLE `centro_costo`
  ADD PRIMARY KEY (`id_centro`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_depto` (`id_depto`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD UNIQUE KEY `codigo_cliente` (`codigo_cliente`);

--
-- Indices de la tabla `cola_tareas`
--
ALTER TABLE `cola_tareas`
  ADD PRIMARY KEY (`id_tarea`);

--
-- Indices de la tabla `combo`
--
ALTER TABLE `combo`
  ADD PRIMARY KEY (`id_combo`);

--
-- Indices de la tabla `combo_detalle`
--
ALTER TABLE `combo_detalle`
  ADD PRIMARY KEY (`id_combo`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `concepto_nomina`
--
ALTER TABLE `concepto_nomina`
  ADD PRIMARY KEY (`id_concepto`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  ADD PRIMARY KEY (`id_config`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `contrato_laboral`
--
ALTER TABLE `contrato_laboral`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `contrato_proveedor`
--
ALTER TABLE `contrato_proveedor`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `control_temperatura`
--
ALTER TABLE `control_temperatura`
  ADD PRIMARY KEY (`id_control`),
  ADD KEY `id_bodega` (`id_bodega`),
  ADD KEY `id_empleado_registro` (`id_empleado_registro`);

--
-- Indices de la tabla `costeo_producto`
--
ALTER TABLE `costeo_producto`
  ADD PRIMARY KEY (`id_costeo`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  ADD PRIMARY KEY (`id_cuenta`),
  ADD UNIQUE KEY `numero_cuenta` (`numero_cuenta`),
  ADD KEY `id_banco` (`id_banco`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `cupon_descuento`
--
ALTER TABLE `cupon_descuento`
  ADD PRIMARY KEY (`id_cupon`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_depto`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `depreciacion_mensual`
--
ALTER TABLE `depreciacion_mensual`
  ADD PRIMARY KEY (`id_depreciacion`),
  ADD KEY `id_activo` (`id_activo`),
  ADD KEY `id_asiento_contable` (`id_asiento_contable`);

--
-- Indices de la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`,`id_proveedor`,`id_insumo`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_insumo` (`id_insumo`);

--
-- Indices de la tabla `detalle_orden_compra`
--
ALTER TABLE `detalle_orden_compra`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_orden` (`id_orden`),
  ADD KEY `id_insumo` (`id_insumo`);

--
-- Indices de la tabla `devolucion_proveedor`
--
ALTER TABLE `devolucion_proveedor`
  ADD PRIMARY KEY (`id_devolucion`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_recepcion` (`id_recepcion`);

--
-- Indices de la tabla `devolucion_venta`
--
ALTER TABLE `devolucion_venta`
  ADD PRIMARY KEY (`id_devolucion`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_factura_original` (`id_factura_original`),
  ADD KEY `id_empleado_autoriza` (`id_empleado_autoriza`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD UNIQUE KEY `codigo_emp` (`codigo_emp`),
  ADD KEY `id_unidad` (`id_unidad`),
  ADD KEY `id_area` (`id_area`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `encuesta_satisfaccion`
--
ALTER TABLE `encuesta_satisfaccion`
  ADD PRIMARY KEY (`id_encuesta`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `envio`
--
ALTER TABLE `envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD UNIQUE KEY `codigo_envio` (`codigo_envio`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_repartidor` (`id_repartidor`),
  ADD KEY `id_tarifa_zona` (`id_tarifa_zona`);

--
-- Indices de la tabla `evaluacion_proveedor`
--
ALTER TABLE `evaluacion_proveedor`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `evento_especial`
--
ALTER TABLE `evento_especial`
  ADD PRIMARY KEY (`id_evento`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado_responsable` (`id_empleado_responsable`);

--
-- Indices de la tabla `factura_cabecera`
--
ALTER TABLE `factura_cabecera`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_unidad` (`id_unidad`),
  ADD KEY `id_cupon` (`id_cupon`),
  ADD KEY `id_impuesto` (`id_impuesto`);

--
-- Indices de la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD PRIMARY KEY (`id_det_fac`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `gasto_operativo`
--
ALTER TABLE `gasto_operativo`
  ADD PRIMARY KEY (`id_gasto`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_centro_costo` (`id_centro_costo`),
  ADD KEY `id_cuenta_contable` (`id_cuenta_contable`),
  ADD KEY `id_empleado_registro` (`id_empleado_registro`);

--
-- Indices de la tabla `historial_fidelidad`
--
ALTER TABLE `historial_fidelidad`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `historial_precio`
--
ALTER TABLE `historial_precio`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_empleado_cambia` (`id_empleado_cambia`);

--
-- Indices de la tabla `horario_empleado`
--
ALTER TABLE `horario_empleado`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `impuesto`
--
ALTER TABLE `impuesto`
  ADD PRIMARY KEY (`id_impuesto`);

--
-- Indices de la tabla `insumo`
--
ALTER TABLE `insumo`
  ADD PRIMARY KEY (`id_insumo`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `inventario_fisico`
--
ALTER TABLE `inventario_fisico`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_bodega` (`id_bodega`),
  ADD KEY `id_empleado_responsable` (`id_empleado_responsable`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`id_lote`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_insumo` (`id_insumo`);

--
-- Indices de la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD PRIMARY KEY (`id_membresia`);

--
-- Indices de la tabla `merma`
--
ALTER TABLE `merma`
  ADD PRIMARY KEY (`id_merma`),
  ADD KEY `id_insumo` (`id_insumo`),
  ADD KEY `id_bodega` (`id_bodega`),
  ADD KEY `id_empleado_registro` (`id_empleado_registro`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id_mesa`),
  ADD KEY `id_unidad` (`id_unidad`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`id_metodo`);

--
-- Indices de la tabla `movimiento_bodega`
--
ALTER TABLE `movimiento_bodega`
  ADD PRIMARY KEY (`id_mov`),
  ADD KEY `id_insumo` (`id_insumo`),
  ADD KEY `id_bodega` (`id_bodega`),
  ADD KEY `id_lote` (`id_lote`);

--
-- Indices de la tabla `nomina_cabecera`
--
ALTER TABLE `nomina_cabecera`
  ADD PRIMARY KEY (`id_nomina`),
  ADD KEY `id_periodo` (`id_periodo`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `nomina_detalle`
--
ALTER TABLE `nomina_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_nomina` (`id_nomina`),
  ADD KEY `id_concepto` (`id_concepto`);

--
-- Indices de la tabla `nomina_periodo`
--
ALTER TABLE `nomina_periodo`
  ADD PRIMARY KEY (`id_periodo`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_empleado_procesa` (`id_empleado_procesa`);

--
-- Indices de la tabla `notificacion_sistema`
--
ALTER TABLE `notificacion_sistema`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  ADD PRIMARY KEY (`id_orden`),
  ADD UNIQUE KEY `numero_orden` (`numero_orden`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_empleado_autoriza` (`id_empleado_autoriza`);

--
-- Indices de la tabla `orden_produccion`
--
ALTER TABLE `orden_produccion`
  ADD PRIMARY KEY (`id_orden`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_empleado_responsable` (`id_empleado_responsable`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_metodo` (`id_metodo`),
  ADD KEY `id_empleado_registro` (`id_empleado_registro`);

--
-- Indices de la tabla `pedido_cabecera`
--
ALTER TABLE `pedido_cabecera`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_unidad` (`id_unidad`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `numero_identificacion` (`numero_identificacion`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `plantilla_email`
--
ALTER TABLE `plantilla_email`
  ADD PRIMARY KEY (`id_plantilla`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  ADD PRIMARY KEY (`id_cuenta`),
  ADD UNIQUE KEY `codigo_cuenta` (`codigo_cuenta`),
  ADD KEY `cuenta_padre` (`cuenta_padre`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `proforma_cabecera`
--
ALTER TABLE `proforma_cabecera`
  ADD PRIMARY KEY (`id_proforma`),
  ADD UNIQUE KEY `numero_proforma` (`numero_proforma`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `proforma_detalle`
--
ALTER TABLE `proforma_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_proforma` (`id_proforma`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `codigo_proveedor` (`codigo_proveedor`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `punto_cliente`
--
ALTER TABLE `punto_cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_membresia` (`id_membresia`);

--
-- Indices de la tabla `queja_reclamo`
--
ALTER TABLE `queja_reclamo`
  ADD PRIMARY KEY (`id_reclamo`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_empleado_asigna` (`id_empleado_asigna`);

--
-- Indices de la tabla `recepcion_compra`
--
ALTER TABLE `recepcion_compra`
  ADD PRIMARY KEY (`id_recepcion`),
  ADD UNIQUE KEY `numero_recepcion` (`numero_recepcion`),
  ADD KEY `id_orden_compra` (`id_orden_compra`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_bodega` (`id_bodega`),
  ADD KEY `id_empleado_recibe` (`id_empleado_recibe`);

--
-- Indices de la tabla `receta_cabecera`
--
ALTER TABLE `receta_cabecera`
  ADD PRIMARY KEY (`id_receta`),
  ADD UNIQUE KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `receta_detalle`
--
ALTER TABLE `receta_detalle`
  ADD PRIMARY KEY (`id_receta`,`id_insumo`),
  ADD KEY `id_insumo` (`id_insumo`);

--
-- Indices de la tabla `repartidor`
--
ALTER TABLE `repartidor`
  ADD PRIMARY KEY (`id_repartidor`),
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD UNIQUE KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `reporte_config`
--
ALTER TABLE `reporte_config`
  ADD PRIMARY KEY (`id_reporte`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_mesa` (`id_mesa`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `sesion_activa`
--
ALTER TABLE `sesion_activa`
  ADD PRIMARY KEY (`id_sesion`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `solicitud_cotizacion`
--
ALTER TABLE `solicitud_cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_empleado_solicitante` (`id_empleado_solicitante`);

--
-- Indices de la tabla `tarifa_zona`
--
ALTER TABLE `tarifa_zona`
  ADD PRIMARY KEY (`id_tarifa`);

--
-- Indices de la tabla `tracking_envio`
--
ALTER TABLE `tracking_envio`
  ADD PRIMARY KEY (`id_tracking`),
  ADD KEY `id_envio` (`id_envio`);

--
-- Indices de la tabla `unidad_operativa`
--
ALTER TABLE `unidad_operativa`
  ADD PRIMARY KEY (`id_unidad`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_unidad_empresa` (`id_empresa`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `id_empleado` (`id_empleado`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`id_vacacion`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `webhook_integracion`
--
ALTER TABLE `webhook_integracion`
  ADD PRIMARY KEY (`id_webhook`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activo_fijo`
--
ALTER TABLE `activo_fijo`
  MODIFY `id_activo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `asiento_cabecera`
--
ALTER TABLE `asiento_cabecera`
  MODIFY `id_asiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asiento_detalle`
--
ALTER TABLE `asiento_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auditoria_inventario`
--
ALTER TABLE `auditoria_inventario`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `id_banco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `bitacora_log`
--
ALTER TABLE `bitacora_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bodega`
--
ALTER TABLE `bodega`
  MODIFY `id_bodega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `centro_costo`
--
ALTER TABLE `centro_costo`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `cola_tareas`
--
ALTER TABLE `cola_tareas`
  MODIFY `id_tarea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `combo`
--
ALTER TABLE `combo`
  MODIFY `id_combo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `concepto_nomina`
--
ALTER TABLE `concepto_nomina`
  MODIFY `id_concepto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `contrato_laboral`
--
ALTER TABLE `contrato_laboral`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contrato_proveedor`
--
ALTER TABLE `contrato_proveedor`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_temperatura`
--
ALTER TABLE `control_temperatura`
  MODIFY `id_control` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `costeo_producto`
--
ALTER TABLE `costeo_producto`
  MODIFY `id_costeo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cupon_descuento`
--
ALTER TABLE `cupon_descuento`
  MODIFY `id_cupon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_depto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `depreciacion_mensual`
--
ALTER TABLE `depreciacion_mensual`
  MODIFY `id_depreciacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_orden_compra`
--
ALTER TABLE `detalle_orden_compra`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion_proveedor`
--
ALTER TABLE `devolucion_proveedor`
  MODIFY `id_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion_venta`
--
ALTER TABLE `devolucion_venta`
  MODIFY `id_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `encuesta_satisfaccion`
--
ALTER TABLE `encuesta_satisfaccion`
  MODIFY `id_encuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `envio`
--
ALTER TABLE `envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluacion_proveedor`
--
ALTER TABLE `evaluacion_proveedor`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evento_especial`
--
ALTER TABLE `evento_especial`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura_cabecera`
--
ALTER TABLE `factura_cabecera`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  MODIFY `id_det_fac` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `gasto_operativo`
--
ALTER TABLE `gasto_operativo`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_fidelidad`
--
ALTER TABLE `historial_fidelidad`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_precio`
--
ALTER TABLE `historial_precio`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horario_empleado`
--
ALTER TABLE `horario_empleado`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `impuesto`
--
ALTER TABLE `impuesto`
  MODIFY `id_impuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `insumo`
--
ALTER TABLE `insumo`
  MODIFY `id_insumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario_fisico`
--
ALTER TABLE `inventario_fisico`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `id_lote` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `merma`
--
ALTER TABLE `merma`
  MODIFY `id_merma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `movimiento_bodega`
--
ALTER TABLE `movimiento_bodega`
  MODIFY `id_mov` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina_cabecera`
--
ALTER TABLE `nomina_cabecera`
  MODIFY `id_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina_detalle`
--
ALTER TABLE `nomina_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina_periodo`
--
ALTER TABLE `nomina_periodo`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion_sistema`
--
ALTER TABLE `notificacion_sistema`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_produccion`
--
ALTER TABLE `orden_produccion`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_cabecera`
--
ALTER TABLE `pedido_cabecera`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `plantilla_email`
--
ALTER TABLE `plantilla_email`
  MODIFY `id_plantilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `proforma_cabecera`
--
ALTER TABLE `proforma_cabecera`
  MODIFY `id_proforma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proforma_detalle`
--
ALTER TABLE `proforma_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `queja_reclamo`
--
ALTER TABLE `queja_reclamo`
  MODIFY `id_reclamo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recepcion_compra`
--
ALTER TABLE `recepcion_compra`
  MODIFY `id_recepcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `receta_cabecera`
--
ALTER TABLE `receta_cabecera`
  MODIFY `id_receta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `repartidor`
--
ALTER TABLE `repartidor`
  MODIFY `id_repartidor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporte_config`
--
ALTER TABLE `reporte_config`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sesion_activa`
--
ALTER TABLE `sesion_activa`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_cotizacion`
--
ALTER TABLE `solicitud_cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tarifa_zona`
--
ALTER TABLE `tarifa_zona`
  MODIFY `id_tarifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tracking_envio`
--
ALTER TABLE `tracking_envio`
  MODIFY `id_tracking` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidad_operativa`
--
ALTER TABLE `unidad_operativa`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id_vacacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `webhook_integracion`
--
ALTER TABLE `webhook_integracion`
  MODIFY `id_webhook` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activo_fijo`
--
ALTER TABLE `activo_fijo`
  ADD CONSTRAINT `activo_fijo_ibfk_1` FOREIGN KEY (`id_cuenta_contable`) REFERENCES `plan_cuentas` (`id_cuenta`);

--
-- Filtros para la tabla `area`
--
ALTER TABLE `area`
  ADD CONSTRAINT `area_ibfk_1` FOREIGN KEY (`id_depto`) REFERENCES `departamento` (`id_depto`);

--
-- Filtros para la tabla `asiento_cabecera`
--
ALTER TABLE `asiento_cabecera`
  ADD CONSTRAINT `asiento_cabecera_ibfk_1` FOREIGN KEY (`id_empleado_registro`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `asiento_detalle`
--
ALTER TABLE `asiento_detalle`
  ADD CONSTRAINT `asiento_detalle_ibfk_1` FOREIGN KEY (`id_asiento`) REFERENCES `asiento_cabecera` (`id_asiento`) ON DELETE CASCADE,
  ADD CONSTRAINT `asiento_detalle_ibfk_2` FOREIGN KEY (`id_cuenta`) REFERENCES `plan_cuentas` (`id_cuenta`),
  ADD CONSTRAINT `asiento_detalle_ibfk_3` FOREIGN KEY (`id_centro_costo`) REFERENCES `centro_costo` (`id_centro`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `auditoria_inventario`
--
ALTER TABLE `auditoria_inventario`
  ADD CONSTRAINT `auditoria_inventario_ibfk_1` FOREIGN KEY (`id_inventario`) REFERENCES `inventario_fisico` (`id_inventario`),
  ADD CONSTRAINT `auditoria_inventario_ibfk_2` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`);

--
-- Filtros para la tabla `bitacora_log`
--
ALTER TABLE `bitacora_log`
  ADD CONSTRAINT `bitacora_log_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `bodega`
--
ALTER TABLE `bodega`
  ADD CONSTRAINT `bodega_ibfk_1` FOREIGN KEY (`id_unidad`) REFERENCES `unidad_operativa` (`id_unidad`);

--
-- Filtros para la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD CONSTRAINT `cargo_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`);

--
-- Filtros para la tabla `centro_costo`
--
ALTER TABLE `centro_costo`
  ADD CONSTRAINT `centro_costo_ibfk_1` FOREIGN KEY (`id_depto`) REFERENCES `departamento` (`id_depto`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `combo_detalle`
--
ALTER TABLE `combo_detalle`
  ADD CONSTRAINT `combo_detalle_ibfk_1` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contrato_laboral`
--
ALTER TABLE `contrato_laboral`
  ADD CONSTRAINT `contrato_laboral_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `contrato_proveedor`
--
ALTER TABLE `contrato_proveedor`
  ADD CONSTRAINT `contrato_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `control_temperatura`
--
ALTER TABLE `control_temperatura`
  ADD CONSTRAINT `control_temperatura_ibfk_1` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  ADD CONSTRAINT `control_temperatura_ibfk_2` FOREIGN KEY (`id_empleado_registro`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `costeo_producto`
--
ALTER TABLE `costeo_producto`
  ADD CONSTRAINT `costeo_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  ADD CONSTRAINT `cuenta_bancaria_ibfk_1` FOREIGN KEY (`id_banco`) REFERENCES `banco` (`id_banco`),
  ADD CONSTRAINT `cuenta_bancaria_ibfk_2` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`);

--
-- Filtros para la tabla `depreciacion_mensual`
--
ALTER TABLE `depreciacion_mensual`
  ADD CONSTRAINT `depreciacion_mensual_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activo_fijo` (`id_activo`),
  ADD CONSTRAINT `depreciacion_mensual_ibfk_2` FOREIGN KEY (`id_asiento_contable`) REFERENCES `asiento_cabecera` (`id_asiento`);

--
-- Filtros para la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD CONSTRAINT `detalle_cotizacion_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `solicitud_cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `detalle_cotizacion_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `detalle_cotizacion_ibfk_3` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`);

--
-- Filtros para la tabla `detalle_orden_compra`
--
ALTER TABLE `detalle_orden_compra`
  ADD CONSTRAINT `detalle_orden_compra_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `orden_compra` (`id_orden`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_orden_compra_ibfk_2` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`);

--
-- Filtros para la tabla `devolucion_proveedor`
--
ALTER TABLE `devolucion_proveedor`
  ADD CONSTRAINT `devolucion_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `devolucion_proveedor_ibfk_2` FOREIGN KEY (`id_recepcion`) REFERENCES `recepcion_compra` (`id_recepcion`);

--
-- Filtros para la tabla `devolucion_venta`
--
ALTER TABLE `devolucion_venta`
  ADD CONSTRAINT `devolucion_venta_ibfk_1` FOREIGN KEY (`id_factura_original`) REFERENCES `factura_cabecera` (`id_factura`),
  ADD CONSTRAINT `devolucion_venta_ibfk_2` FOREIGN KEY (`id_empleado_autoriza`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_unidad`) REFERENCES `unidad_operativa` (`id_unidad`),
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `empleado_ibfk_4` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`);

--
-- Filtros para la tabla `encuesta_satisfaccion`
--
ALTER TABLE `encuesta_satisfaccion`
  ADD CONSTRAINT `encuesta_satisfaccion_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura_cabecera` (`id_factura`),
  ADD CONSTRAINT `encuesta_satisfaccion_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `envio`
--
ALTER TABLE `envio`
  ADD CONSTRAINT `envio_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura_cabecera` (`id_factura`),
  ADD CONSTRAINT `envio_ibfk_2` FOREIGN KEY (`id_repartidor`) REFERENCES `repartidor` (`id_repartidor`),
  ADD CONSTRAINT `envio_ibfk_3` FOREIGN KEY (`id_tarifa_zona`) REFERENCES `tarifa_zona` (`id_tarifa`);

--
-- Filtros para la tabla `evaluacion_proveedor`
--
ALTER TABLE `evaluacion_proveedor`
  ADD CONSTRAINT `evaluacion_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `evento_especial`
--
ALTER TABLE `evento_especial`
  ADD CONSTRAINT `evento_especial_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `evento_especial_ibfk_2` FOREIGN KEY (`id_empleado_responsable`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `factura_cabecera`
--
ALTER TABLE `factura_cabecera`
  ADD CONSTRAINT `factura_cabecera_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `factura_cabecera_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `factura_cabecera_ibfk_3` FOREIGN KEY (`id_unidad`) REFERENCES `unidad_operativa` (`id_unidad`),
  ADD CONSTRAINT `factura_cabecera_ibfk_4` FOREIGN KEY (`id_cupon`) REFERENCES `cupon_descuento` (`id_cupon`),
  ADD CONSTRAINT `factura_cabecera_ibfk_5` FOREIGN KEY (`id_impuesto`) REFERENCES `impuesto` (`id_impuesto`);

--
-- Filtros para la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD CONSTRAINT `factura_detalle_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura_cabecera` (`id_factura`),
  ADD CONSTRAINT `factura_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `gasto_operativo`
--
ALTER TABLE `gasto_operativo`
  ADD CONSTRAINT `gasto_operativo_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `gasto_operativo_ibfk_2` FOREIGN KEY (`id_centro_costo`) REFERENCES `centro_costo` (`id_centro`),
  ADD CONSTRAINT `gasto_operativo_ibfk_3` FOREIGN KEY (`id_cuenta_contable`) REFERENCES `plan_cuentas` (`id_cuenta`),
  ADD CONSTRAINT `gasto_operativo_ibfk_4` FOREIGN KEY (`id_empleado_registro`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `historial_fidelidad`
--
ALTER TABLE `historial_fidelidad`
  ADD CONSTRAINT `historial_fidelidad_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `historial_fidelidad_ibfk_2` FOREIGN KEY (`id_factura`) REFERENCES `factura_cabecera` (`id_factura`);

--
-- Filtros para la tabla `historial_precio`
--
ALTER TABLE `historial_precio`
  ADD CONSTRAINT `historial_precio_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `historial_precio_ibfk_2` FOREIGN KEY (`id_empleado_cambia`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `horario_empleado`
--
ALTER TABLE `horario_empleado`
  ADD CONSTRAINT `horario_empleado_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `insumo`
--
ALTER TABLE `insumo`
  ADD CONSTRAINT `insumo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `inventario_fisico`
--
ALTER TABLE `inventario_fisico`
  ADD CONSTRAINT `inventario_fisico_ibfk_1` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  ADD CONSTRAINT `inventario_fisico_ibfk_2` FOREIGN KEY (`id_empleado_responsable`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `lote`
--
ALTER TABLE `lote`
  ADD CONSTRAINT `lote_ibfk_1` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`);

--
-- Filtros para la tabla `merma`
--
ALTER TABLE `merma`
  ADD CONSTRAINT `merma_ibfk_1` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`),
  ADD CONSTRAINT `merma_ibfk_2` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  ADD CONSTRAINT `merma_ibfk_3` FOREIGN KEY (`id_empleado_registro`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD CONSTRAINT `mesa_ibfk_1` FOREIGN KEY (`id_unidad`) REFERENCES `unidad_operativa` (`id_unidad`);

--
-- Filtros para la tabla `movimiento_bodega`
--
ALTER TABLE `movimiento_bodega`
  ADD CONSTRAINT `movimiento_bodega_ibfk_1` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`),
  ADD CONSTRAINT `movimiento_bodega_ibfk_2` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  ADD CONSTRAINT `movimiento_bodega_ibfk_3` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id_lote`);

--
-- Filtros para la tabla `nomina_cabecera`
--
ALTER TABLE `nomina_cabecera`
  ADD CONSTRAINT `nomina_cabecera_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `nomina_periodo` (`id_periodo`),
  ADD CONSTRAINT `nomina_cabecera_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `nomina_detalle`
--
ALTER TABLE `nomina_detalle`
  ADD CONSTRAINT `nomina_detalle_ibfk_1` FOREIGN KEY (`id_nomina`) REFERENCES `nomina_cabecera` (`id_nomina`) ON DELETE CASCADE,
  ADD CONSTRAINT `nomina_detalle_ibfk_2` FOREIGN KEY (`id_concepto`) REFERENCES `concepto_nomina` (`id_concepto`);

--
-- Filtros para la tabla `nomina_periodo`
--
ALTER TABLE `nomina_periodo`
  ADD CONSTRAINT `nomina_periodo_ibfk_1` FOREIGN KEY (`id_empleado_procesa`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `notificacion_sistema`
--
ALTER TABLE `notificacion_sistema`
  ADD CONSTRAINT `notificacion_sistema_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  ADD CONSTRAINT `orden_compra_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `orden_compra_ibfk_2` FOREIGN KEY (`id_empleado_autoriza`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `orden_produccion`
--
ALTER TABLE `orden_produccion`
  ADD CONSTRAINT `orden_produccion_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `orden_produccion_ibfk_2` FOREIGN KEY (`id_empleado_responsable`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura_cabecera` (`id_factura`),
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`id_metodo`) REFERENCES `metodo_pago` (`id_metodo`),
  ADD CONSTRAINT `pago_ibfk_3` FOREIGN KEY (`id_empleado_registro`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `pedido_cabecera`
--
ALTER TABLE `pedido_cabecera`
  ADD CONSTRAINT `pedido_cabecera_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `pedido_cabecera_ibfk_2` FOREIGN KEY (`id_unidad`) REFERENCES `unidad_operativa` (`id_unidad`);

--
-- Filtros para la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  ADD CONSTRAINT `plan_cuentas_ibfk_1` FOREIGN KEY (`cuenta_padre`) REFERENCES `plan_cuentas` (`id_cuenta`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `proforma_cabecera`
--
ALTER TABLE `proforma_cabecera`
  ADD CONSTRAINT `proforma_cabecera_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `proforma_cabecera_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `proforma_detalle`
--
ALTER TABLE `proforma_detalle`
  ADD CONSTRAINT `proforma_detalle_ibfk_1` FOREIGN KEY (`id_proforma`) REFERENCES `proforma_cabecera` (`id_proforma`) ON DELETE CASCADE,
  ADD CONSTRAINT `proforma_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `punto_cliente`
--
ALTER TABLE `punto_cliente`
  ADD CONSTRAINT `punto_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `punto_cliente_ibfk_2` FOREIGN KEY (`id_membresia`) REFERENCES `membresia` (`id_membresia`);

--
-- Filtros para la tabla `queja_reclamo`
--
ALTER TABLE `queja_reclamo`
  ADD CONSTRAINT `queja_reclamo_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `queja_reclamo_ibfk_2` FOREIGN KEY (`id_factura`) REFERENCES `factura_cabecera` (`id_factura`),
  ADD CONSTRAINT `queja_reclamo_ibfk_3` FOREIGN KEY (`id_empleado_asigna`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `recepcion_compra`
--
ALTER TABLE `recepcion_compra`
  ADD CONSTRAINT `recepcion_compra_ibfk_1` FOREIGN KEY (`id_orden_compra`) REFERENCES `orden_compra` (`id_orden`),
  ADD CONSTRAINT `recepcion_compra_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `recepcion_compra_ibfk_3` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  ADD CONSTRAINT `recepcion_compra_ibfk_4` FOREIGN KEY (`id_empleado_recibe`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `receta_cabecera`
--
ALTER TABLE `receta_cabecera`
  ADD CONSTRAINT `receta_cabecera_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `receta_detalle`
--
ALTER TABLE `receta_detalle`
  ADD CONSTRAINT `receta_detalle_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `receta_cabecera` (`id_receta`) ON DELETE CASCADE,
  ADD CONSTRAINT `receta_detalle_ibfk_2` FOREIGN KEY (`id_insumo`) REFERENCES `insumo` (`id_insumo`);

--
-- Filtros para la tabla `repartidor`
--
ALTER TABLE `repartidor`
  ADD CONSTRAINT `repartidor_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `repartidor_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_mesa`) REFERENCES `mesa` (`id_mesa`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sesion_activa`
--
ALTER TABLE `sesion_activa`
  ADD CONSTRAINT `sesion_activa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitud_cotizacion`
--
ALTER TABLE `solicitud_cotizacion`
  ADD CONSTRAINT `solicitud_cotizacion_ibfk_1` FOREIGN KEY (`id_empleado_solicitante`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `tracking_envio`
--
ALTER TABLE `tracking_envio`
  ADD CONSTRAINT `tracking_envio_ibfk_1` FOREIGN KEY (`id_envio`) REFERENCES `envio` (`id_envio`);

--
-- Filtros para la tabla `unidad_operativa`
--
ALTER TABLE `unidad_operativa`
  ADD CONSTRAINT `fk_unidad_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `vacaciones_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
