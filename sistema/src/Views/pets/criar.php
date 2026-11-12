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

                <?php if (empty($tutores)): ?>
                    <div class="alert alert-warning">
                        Nenhum tutor cadastrado. Cadastre um tutor antes de registrar um pet.
                        <div class="mt-2">
                            <a href="/tutores/novo" class="btn btn-sm btn-primary">Cadastrar tutor</a>
                        </div>
                    </div>
                <?php else: ?>
                    <form action="/pets/salvar" method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                        <div class="mb-3">
                            <label for="tutor_id" class="form-label">Tutor <span class="text-danger">*</span></label>
                            <select id="tutor_id" name="tutor_id" class="form-select" required>
                                <option value="">Selecione o tutor</option>
                                <?php foreach ($tutores as $t): ?>
                                    <option value="<?= (int)$t['id'] ?>">
                                        <?= htmlspecialchars($t['nome']) ?> (<?= htmlspecialchars($t['telefone']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do pet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex.: Thor, Mia, Bob, Pipoca">
                        </div>

                        <div class="mb-3">
                            <label for="especie" class="form-label">Espécie <span class="text-danger">*</span></label>
                            <select id="especie" name="especie" class="form-select" required>
                                <option value="Canina">Canina</option>
                                <option value="Felina">Felina</option>
                                <option value="Aves">Aves</option>
                                <option value="Roedores">Roedores</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="raca" class="form-label">Raça <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="raca" name="raca" required placeholder="Ex.: Golden Retriever, Siamês, SRD">
                        </div>

                        <div class="mb-4">
                            <label for="data_nascimento" class="form-label">Data de nascimento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required max="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <a href="/pets" class="btn btn-outline-secondary">Voltar</a>
                            <button type="submit" class="btn btn-primary px-4">Salvar</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
