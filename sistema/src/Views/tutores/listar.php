<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h5 class="fw-bold mb-0"><?= htmlspecialchars($titulo) ?></h5>
    <a href="/tutores/novo" class="btn btn-primary">Cadastrar tutor</a>
</div>

<?php if (!empty($sucesso)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($sucesso) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
<?php endif; ?>

<?php if (!empty($erro)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($erro) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body py-2">
        <form action="/tutores" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-9">
                <input type="text" name="q" class="form-control" placeholder="Buscar por nome, e-mail ou telefone..." value="<?= htmlspecialchars($busca ?? '') ?>">
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-fill">Filtrar</button>
                <?php if (!empty($busca)): ?>
                    <a href="/tutores" class="btn btn-outline-secondary" title="Limpar">×</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 70px;">#</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th class="text-end pe-4" style="width: 160px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tutores)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <?php if (!empty($busca)): ?>
                                    Nenhum tutor encontrado para "<strong><?= htmlspecialchars($busca) ?></strong>".
                                <?php else: ?>
                                    Nenhum tutor cadastrado.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tutores as $t): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= (int)$t['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($t['nome']) ?></td>
                                <td><span class="text-muted"><?= htmlspecialchars($t['cpf']) ?></span></td>
                                <td><?= htmlspecialchars($t['telefone']) ?></td>
                                <td><?= htmlspecialchars($t['email']) ?></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/tutores/editar?id=<?= (int)$t['id'] ?>" class="btn btn-outline-secondary">Editar</a>
                                        <form action="/tutores/excluir" method="POST" class="d-inline" onsubmit="return confirm('Remover <?= htmlspecialchars(addslashes($t['nome'])) ?>? Os pets e agendamentos vinculados também serão removidos.');">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                                            <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
