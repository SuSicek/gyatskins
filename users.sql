
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL



);


INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$e.emsMKuzlDuvWxjMBItZe6SujvFKCI3zXCQg9i5uRkZiOeQwCXFq');

INSERT INTO users (username, password) 
VALUES ('guest', '$2y$10$e.emsMKuzlDuvWxjMBItZe6SujvFKCI3zXCQg9i5uRkZiOeQwCXFq');

