create DATABASE eventos_academicos;

USE eventos_academicos;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    senha VARCHAR(225) NOT NULL
);

CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    dia DATETIME NOT NULL,
    descricao TEXT,
    lugar VARCHAR(200),
    categoria VARCHAR(100),
    imagem VARCHAR(255),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);