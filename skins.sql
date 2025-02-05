
CREATE TABLE skins (
    DESCRIBE skins;
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    rarity VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
    ALTER TABLE skins ADD COLUMN user_id INT NOT NULL;
    UPDATE skins SET user_id = 1 WHERE id = 1;


);

