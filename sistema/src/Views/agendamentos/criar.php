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

                <form action="/agendamentos/salvar" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                    <div class="mb-3">
                        <label for="tutor_id" class="form-label">Tutor <span class="text-danger">*</span></label>
                        <select id="tutor_id" name="tutor_id" class="form-select" required>
                            <option value="">Selecione o tutor</option>
                            <?php foreach ($tutores as $t): ?>
                                <option value="<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['nome']) ?> (<?= htmlspecialchars($t['telefone']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pet_id" class="form-label">Pet <span class="text-danger">*</span></label>
                        <select id="pet_id" name="pet_id" class="form-select" required>
                            <option value="">Selecione o pet</option>
                            <?php foreach ($pets as $p): ?>
                                <option value="<?= (int)$p['id'] ?>">
                                    <?= htmlspecialchars($p['nome']) ?> (<?= htmlspecialchars($p['especie']) ?> — <?= htmlspecialchars($p['tutor_nome']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="data_agendamento" class="form-label">Data <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="data_agendamento" name="data_agendamento" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="hora_agendamento" class="form-label">Horário <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="hora_agendamento" name="hora_agendamento" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="servico" class="form-label">Procedimento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="servico" name="servico" required placeholder="Ex.: Consulta geral, Vacinação, Cirurgia, Exames">
                    </div>

                    <div class="mb-4">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="2" placeholder="Histórico relevante ou recomendações..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <a href="/agendamentos" class="btn btn-outline-secondary">Voltar</a>
                        <button type="submit" class="btn btn-primary px-4">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
