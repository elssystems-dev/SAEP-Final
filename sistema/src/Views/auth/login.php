<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border" style="box-shadow: none; border-color: #d4d4d4;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">SisVet</h4>
                <p class="text-muted small mb-4">Sistema de Gestão Veterinária</p>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($erro) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($aviso)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($aviso) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>

                <form action="/login" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
