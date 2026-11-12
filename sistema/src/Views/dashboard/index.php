<div class="mb-4">
    <h5 class="fw-bold mb-0"><?= htmlspecialchars($usuarioNome) ?></h5>
    <small class="text-muted"><?= htmlspecialchars($usuarioPerfil) ?></small>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card h-100 border-start border-4" style="border-color: #1a5276 !important;">
            <div class="card-body">
                <div class="text-muted small mb-1">Tutores</div>
                <h2 class="fw-bold mb-0"><?= (int)$totalTutores ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="/tutores" class="btn btn-sm btn-outline-secondary w-100">Ver tutores</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card h-100 border-start border-4 border-secondary">
            <div class="card-body">
                <div class="text-muted small mb-1">Pets</div>
                <h2 class="fw-bold mb-0"><?= (int)$totalPets ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="/pets" class="btn btn-sm btn-outline-secondary w-100">Ver pets</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body">
                <div class="text-muted small mb-1">Agendamentos</div>
                <h2 class="fw-bold mb-0"><?= (int)$totalAgendamentos ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="/agendamentos" class="btn btn-sm btn-outline-secondary w-100">Ver agendamentos</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Próximas consultas</h6>
        <a href="/agendamentos/novo" class="btn btn-sm btn-primary">Novo agendamento</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Data</th>
                        <th>Animal</th>
                        <th>Tutor</th>
                        <th>Procedimento</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proximosAgendamentos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Nenhuma consulta agendada.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($proximosAgendamentos as $ag): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold"><?= htmlspecialchars(date('d/m/Y', strtotime($ag['data_agendamento']))) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars(substr($ag['hora_agendamento'], 0, 5)) ?></small>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($ag['pet_nome']) ?></strong>
                                    <span class="text-muted small ms-1"><?= htmlspecialchars($ag['pet_especie']) ?> &bull; <?= htmlspecialchars($ag['pet_raca']) ?></span>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($ag['tutor_nome']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($ag['tutor_telefone']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($ag['servico']) ?></td>
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
