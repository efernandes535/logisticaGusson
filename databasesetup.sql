-- Cria o banco de dados controle_viagens
CREATE DATABASE IF NOT EXISTS controle_viagens;

-- Usa o banco de dados controle_viagens
USE controle_viagens;

-- Cria a tabela viagens
CREATE TABLE IF NOT EXISTS viagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_viagem DATE NOT NULL,
    destino VARCHAR(255) NOT NULL,
    hora_saida TIME,
    hora_chegada TIME,
    carro_placa VARCHAR(20),
    km_inicial DECIMAL(10, 2),
    km_final DECIMAL(10, 2),
    motorista VARCHAR(255),
    observacao TEXT
);

-- Cria a tabela usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Cria a tabela carros
CREATE TABLE IF NOT EXISTS carros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(20) UNIQUE NOT NULL
);

-- Cria a tabela motoristas
CREATE TABLE IF NOT EXISTS motoristas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) UNIQUE NOT NULL,
     ultima_viagem DATETIME
);

-- Insere um usuário admin (senha: dsiders)
INSERT INTO usuarios (usuario, senha) VALUES ('Everton', '$2y$10$f8eWpZ8yJt5H8z8X0V0.wOaX7a3H.f9l3.iXm5.5s7Gq7p7gU.u.');
-- Configura a chave estrangeira para a tabela viagens
ALTER TABLE viagens
ADD CONSTRAINT fk_viagens_motoristas
FOREIGN KEY (motorista) REFERENCES motoristas(nome) ON DELETE SET NULL;