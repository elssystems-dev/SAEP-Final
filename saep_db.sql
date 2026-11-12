-- SisVet - Sistema de Gestão para Clínica Veterinária
-- Banco de dados: PostgreSQL

CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil VARCHAR(64) NOT NULL CHECK (perfil IN ('admin', 'veterinario', 'recepcionista')),
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tutores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(128) NOT NULL,
    cpf_criptografado VARCHAR(255) NOT NULL,
    telefone VARCHAR(64) NOT NULL,
    email VARCHAR(128) NOT NULL,
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pets (
    id SERIAL PRIMARY KEY,
    tutor_id INTEGER NOT NULL REFERENCES tutores(id) ON DELETE CASCADE,
    nome VARCHAR(128) NOT NULL,
    especie VARCHAR(64) NOT NULL,
    raca VARCHAR(128) NOT NULL,
    data_nascimento DATE NOT NULL,
    cadastrado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS agendamentos (
    id SERIAL PRIMARY KEY,
    tutor_id INTEGER NOT NULL REFERENCES tutores(id) ON DELETE CASCADE,
    pet_id INTEGER NOT NULL REFERENCES pets(id) ON DELETE CASCADE,
    usuario_id INTEGER NOT NULL REFERENCES usuarios(id),
    data_agendamento DATE NOT NULL,
    hora_agendamento TIME NOT NULL,
    servico VARCHAR(150) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'AGENDADO' CHECK (status IN ('AGENDADO', 'CONCLUIDO', 'CANCELADO')),
    observacoes TEXT,
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Carga inicial para testes

-- Usuários (senha: 123456)
INSERT INTO usuarios (id, nome, email, senha_hash, perfil) VALUES
(1, 'Dra. Camila Alencar', 'camila.vet@sisvet.com', '$2y$12$y02WggCnhXZdyDZjbAiqAe6rCckX2V9g3rbGEBSBSDbkWys2mpKim', 'veterinario'),
(2, 'Lucas Fernandes', 'lucas.recepcao@sisvet.com', '$2y$12$y02WggCnhXZdyDZjbAiqAe6rCckX2V9g3rbGEBSBSDbkWys2mpKim', 'recepcionista'),
(3, 'Mariana Rocha', 'gestao@sisvet.com', '$2y$12$y02WggCnhXZdyDZjbAiqAe6rCckX2V9g3rbGEBSBSDbkWys2mpKim', 'admin')
ON CONFLICT (id) DO NOTHING;

-- Tutores (CPFs de teste: 111.222.333-44, 555.666.777-88, 999.888.777-66)
INSERT INTO tutores (id, nome, cpf_criptografado, telefone, email) VALUES
(1, 'Carlos Eduardo Silva', '98TT7cBOSYIdqhIIoR8ab+vcj3oGqWTm7gZQT6Fq8S4=', '(11) 98765-4321', 'carlos.silva@email.com'),
(2, 'Beatriz Souza Martins', '773GyhICyVWqDf1JGR9o86qExMECYq5sdDYE2zQ8Sqk=', '(11) 97654-3210', 'beatriz.martins@email.com'),
(3, 'Fernando Henrique Lima', 'V8Ia2FjEGJmRB+WqsEmo5yov1KxT70vENQqitA1ePX8=', '(19) 99123-4567', 'fernando.lima@email.com')
ON CONFLICT (id) DO NOTHING;

-- Pets
INSERT INTO pets (id, tutor_id, nome, especie, raca, data_nascimento) VALUES
(1, 1, 'Thor', 'Canina', 'Golden Retriever', '2021-05-10'),
(2, 2, 'Mia', 'Felina', 'Siamês', '2022-08-15'),
(3, 3, 'Bob', 'Canina', 'Bulldog Francês', '2020-02-20')
ON CONFLICT (id) DO NOTHING;

-- Agendamentos
INSERT INTO agendamentos (id, tutor_id, pet_id, usuario_id, data_agendamento, hora_agendamento, servico, status, observacoes) VALUES
(1, 1, 1, 1, '2024-11-15', '09:30:00', 'Consulta Geral e Vacinação Anual', 'CONCLUIDO', 'Animal dócil, vermifugação em dia'),
(2, 2, 2, 1, '2025-06-20', '14:00:00', 'Profilaxia Dentária e Tartarectomia', 'CONCLUIDO', 'Exames pré-operatórios normais'),
(3, 3, 3, 2, '2026-03-10', '10:30:00', 'Check-up Preventivo e Exame de Sangue', 'AGENDADO', 'Paciente alérgico a dipirona')
ON CONFLICT (id) DO NOTHING;

SELECT setval('usuarios_id_seq', (SELECT COALESCE(MAX(id), 1) FROM usuarios));
SELECT setval('tutores_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tutores));
SELECT setval('pets_id_seq', (SELECT COALESCE(MAX(id), 1) FROM pets));
SELECT setval('agendamentos_id_seq', (SELECT COALESCE(MAX(id), 1) FROM agendamentos));
