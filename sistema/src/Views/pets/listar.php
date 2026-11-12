<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h5 class="fw-bold mb-0"><?= htmlspecialchars($titulo) ?></h5>
    <a href="/pets/novo" class="btn btn-primary">Cadastrar pet</a>
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

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 70px;">#</th>
                        <th>Nome</th>
                        <th>Espécie</th>
                        <th>Raça</th>
                        <th>Nascimento</th>
                        <th>Tutor</th>
                        <th class="pe-4">Contato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pets)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Nenhum pet cadastrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pets as $p): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= (int)$p['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($p['nome']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($p['especie']) ?></span></td>
                                <td><?= htmlspecialchars($p['raca']) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($p['data_nascimento']))) ?></td>
                                <td><?= htmlspecialchars($p['tutor_nome']) ?></td>
                                <td class="pe-4">
                                    <small class="text-muted d-block"><?= htmlspecialchars($p['tutor_telefone']) ?></small>
                                    <small class="text-muted d-block"><?= htmlspecialchars($p['tutor_email']) ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
