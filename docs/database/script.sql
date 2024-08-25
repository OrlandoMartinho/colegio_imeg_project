-- Criação da base de dados
CREATE DATABASE IF NOT EXISTS colegio_imeg_bd;

-- Seleção da base de dados
USE colegio_imeg_bd;

-- Tabela users
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    gender VARCHAR(9),
    email VARCHAR(100),
    room INT,
    id_curso INT,
    type INT,
    token TEXT,
    password TEXT,
    photo LONGBLOB,
    FOREIGN KEY (id_curso) REFERENCES courses(id_curso)
);

-- Tabela courses
CREATE TABLE IF NOT EXISTS courses (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(45)
);

-- Tabela subjects
CREATE TABLE IF NOT EXISTS subjects (
    id_subject INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(45),
    id_curso INT,
    FOREIGN KEY (id_curso) REFERENCES courses(id_curso)
);

-- Tabela Grades
CREATE TABLE IF NOT EXISTS Grades (
    id_grade INT AUTO_INCREMENT PRIMARY KEY,
    id_subject INT,
    id_user INT,
    score DECIMAL(5, 2),
    FOREIGN KEY (id_subject) REFERENCES subjects(id_subject),
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);

-- Tabela highlights
CREATE TABLE IF NOT EXISTS highlights (
    id_highlight INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description VARCHAR(105),
    photo LONGBLOB
);

-- Tabela contacts
CREATE TABLE IF NOT EXISTS contacts (
    id_contact INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    subject VARCHAR(45),
    message VARCHAR(255)
);

-- Inserção do administrador com ID 1, email e senha predefinidos
INSERT INTO users (id_user, name, gender, email, room, id_curso, type, token, password, photo)
VALUES (1, 'Admin', 'N/A', 'admin@example.com', 0, NULL, 0, NULL, '12345678', NULL)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    gender = VALUES(gender),
    email = VALUES(email),
    room = VALUES(room),
    id_curso = VALUES(id_curso),
    type = VALUES(type),
    token = VALUES(token),
    password = VALUES(password),
    photo = VALUES(photo);
