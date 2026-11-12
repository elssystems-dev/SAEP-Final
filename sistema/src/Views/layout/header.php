<?php
    $usuarioLogado = $_SESSION['usuario_nome'] ?? null;
    $usuarioPerfil = $_SESSION['usuario_perfil'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'SisVet') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0ee;
            color: #1c1c1c;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .navbar {
            background-color: #1a5276;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .card {
            border: 1px solid #d4d4d4;
            box-shadow: none;
            border-radius: 0.375rem;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(26, 82, 118, 0.04);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/">SisVet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navConteudo" aria-controls="navConteudo" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navConteudo">
            <?php if ($usuarioLogado): ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">Painel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/tutores">Tutores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/pets">Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/agendamentos">Agendamentos</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <div class="text-white text-end d-none d-md-block">
                        <div class="fw-semibold"><?= htmlspecialchars($usuarioLogado) ?></div>
                        <small class="text-white-50 text-uppercase"><?= htmlspecialchars((string)$usuarioPerfil) ?></small>
                    </div>
                    <form action="/logout" method="POST" class="d-inline m-0">
                        <button type="submit" class="btn btn-outline-light btn-sm d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3" href="/login">Entrar</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4">
