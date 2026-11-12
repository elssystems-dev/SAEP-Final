<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h5 class="fw-bold mb-0"><?= htmlspecialchars($titulo) ?></h5>
    <a href="/agendamentos/novo" class="btn btn-primary">Novo agendamento</a>
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
                        <th>Data</th>
                        <th>Animal</th>
                        <th>Tutor</th>
                        <th>Procedimento</th>
                        <th>Status</th>
                        <th class="text-end pe-4" style="width: 160px;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($agendamentos)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Nenhum agendamento cadastrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($agendamentos as $ag): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= (int)$ag['id'] ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars(date('d/m/Y', strtotime($ag['data_agendamento']))) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars(substr($ag['hora_agendamento'], 0, 5)) ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($ag['pet_nome']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($ag['pet_especie']) ?> &bull; <?= htmlspecialchars($ag['pet_raca']) ?></small>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($ag['tutor_nome']) ?></div>
                                    <small class="text-muted d-block"><?= htmlspecialchars($ag['tutor_telefone']) ?></small>
                                    <small class="text-muted d-block"><?= htmlspecialchars($ag['tutor_email']) ?></small>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($ag['servico']) ?></span>
                                    <?php if (!empty($ag['observacoes'])): ?>
                                        <small class="text-muted d-block fst-italic"><?= htmlspecialchars($ag['observacoes']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($ag['status'] === 'CONCLUIDO'): ?>
                                        <span class="badge bg-success">Concluído</span>
                                    <?php elseif ($ag['status'] === 'CANCELADO'): ?>
                                        <span class="badge bg-danger">Cancelado</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Agendado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="/agendamentos/editar-data?id=<?= (int)$ag['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        Alterar data
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
