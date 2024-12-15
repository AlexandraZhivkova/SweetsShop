
CREATE DATABASE sweets_shop;
CREATE USER 'sweetsshop_admin'@'localhost' IDENTIFIED BY 'Sweets_2024';
GRANT ALL PRIVILEGES ON sweets_shop.* TO `sweetsshop_admin`@`localhost`;