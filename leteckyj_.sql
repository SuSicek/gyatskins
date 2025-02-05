CREATE DATABASE leteckyj_;

USE leteckyj_;

CREATE TABLE skins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    rarity VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

INSERT INTO skins (name, rarity, price, image_url) VALUES
('AWP | Dragon Lore', 'Covert', 2000.00, 'https://example.com/dragon_lore.png'),
('AK-47 | Redline', 'Classified', 20.00, 'https://example.com/redline.png');
