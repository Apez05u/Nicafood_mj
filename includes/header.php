<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$pagina_publica = in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'index.php']);
if (!$pagina_publica && !isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo_pagina ?? 'NicaFood ERP' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    
</body>
<style>
        body { 
            background: #f8f9fa; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .top-navbar {
            display: none;
            background: #0b3185;
            padding: 10px 15px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1028;
        }
        @media (max-width: 992px) {
            .top-navbar { display: flex; }
        }
    </style>
</head>
<body>

<nav class="top-navbar navbar navbar-dark">
    <button class="btn btn-link text-white" onclick="toggleSidebar()">
        <i class="fas fa-bars fa-lg"></i>
    </button>
    <span class="navbar-brand mb-0 h1 ms-2">NicaFood</span>
</nav>