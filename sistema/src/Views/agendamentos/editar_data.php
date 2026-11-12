<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($titulo) ?></h6>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($erro) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>

                <div class="mb-4 p-3 bg-light rounded border">
                    <div class="row small text-muted">
                        <div class="col-6">
                            <span class="d-block fw-semibold text-dark mb-1">Pet</span>
                            <?= htmlspecialchars($agendamento['pet_nome']) ?>
                        </div>
                        <div class="col-6">
                            <span class="d-block fw-semibold text-dark mb-1">Tutor</span>
                            <?= htmlspecialchars($agendamento['tutor_nome']) ?>
                        </div>
                    </div>
                </div>

                <form action="/agendamentos/atualizar-data" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                    <input type="hidden" name="id" value="<?= (int)$agendamento['id'] ?>">

                    <div class="mb-3">
                        <label for="data_agendamento" class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="data_agendamento" name="data_agendamento" value="<?= htmlspecialchars($agendamento['data_agendamento']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="hora_agendamento" class="form-label">Horário</label>
                        <input type="time" class="form-control" id="hora_agendamento" name="hora_agendamento" value="<?= htmlspecialchars(substr($agendamento['hora_agendamento'], 0, 5)) ?>">
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <a href="/agendamentos" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
