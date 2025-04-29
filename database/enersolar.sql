CREATE DATABASE enersolar CHARACTER SET utf8 collate utf8_general_ci;
USE enersolar;

-- ========================  --
-- CREATED TABLE STATES --
-- ========================  --
CREATE TABLE states(
	id_state INT NOT NULL AUTO_INCREMENT,
    state VARCHAR(50),
    PRIMARY KEY(id_state)
);

-- ===================================================
-- DUMPING DATA INTO THE STATES TABLE
-- ===================================================
INSERT INTO states() VALUES
(1, 'Activo'),
(2, 'En Proceso'),
(3, 'Inactivo'),
(4, 'Disponible'),
(5, 'Sin stock');

-- ========================  --
-- CREATED TABLE ROLES --
-- ========================  --
CREATE TABLE roles(
	id_rol INT NOT NULL AUTO_INCREMENT,
    rol VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_rol)
);

-- ===================================================
-- DUMPING DATA INTO THE ROLES TABLE
-- ===================================================
INSERT INTO roles (rol) VALUES
('Administrador'),
('Colaborador'),
('Proveedor'),
('Cliente');


-- ========================  --
-- CREATED TABLE CATEGORIES --
-- ========================  --

CREATE TABLE categories(
	id_category INT NOT NULL AUTO_INCREMENT,
    category VARCHAR(80) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    id_state INT DEFAULT 1,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id_category),
    FOREIGN KEY (id_state) REFERENCES states(id_state)
);

-- ===================================================
-- DUMPING DATA INTO THE CATEGORIES TABLE
-- ===================================================
INSERT INTO categories (category, slug) VALUES
('Panel Solar', 'panel-sola'),
('Reguladores', 'reguladores'),
('Baterias', 'baterias'),
('Inversores', 'inversores'),
('Ventiladores', 'ventiladores'),
('Radios', 'radios'),
('Kits Solares', 'kits-solares');

-- ========================  --
-- CREATED TABLE PERMITS --
-- ========================  --
CREATE TABLE permits(
	id_permit INT NOT NULL AUTO_INCREMENT,
    code_permit VARCHAR(255),
    permit VARCHAR(100) NOT NULL,
	description VARCHAR(150),
    id_state INT DEFAULT 1,
	created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id_permit),
    FOREIGN KEY (id_state) REFERENCES states (id_state)
);

-- ========================  --
-- CREATED TABLE SOCIAL NETWORK--
-- ========================  --
CREATE TABLE social_network(
	id_social INT NOT NULL AUTO_INCREMENT,
    icon VARCHAR(50),
    social VARCHAR(80),
    url VARCHAR(100), 
    PRIMARY KEY(id_social)
);


-- ========================  --
-- CREATED TABLE SETTINGS --
-- ========================  --
CREATE TABLE settings(
	id_setting INT NOT NULL AUTO_INCREMENT,
    nit INT,
    name VARCHAR(200),
    address VARCHAR(80),
    image VARCHAR(255),
    phone VARCHAR(25),
    id_social INT,
    PRIMARY KEY (id_setting),
    FOREIGN KEY (id_social) REFERENCES social_network (id_social)
);


-- ========================  --
-- CREATED TABLE USERS --
-- ========================  --

CREATE TABLE users(
	id_user INT NOT NULL AUTO_INCREMENT,
    code_user INT,
    first_name VARCHAR(45) NOT NULL,
    second_name VARCHAR(45),
    first_surname VARCHAR(45) NOT NULL,
    second_surname VARCHAR(45),
    image VARCHAR(255)  DEFAULT 'user.png',
    address VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
	phone VARCHAR(80),
    token VARCHAR(255),
    exp_token VARCHAR(255),
    method VARCHAR(255),
    id_rol INT NOT NULL DEFAULT 4,
    id_state INT NOT NULL DEFAULT 1,
    super_root INT DEFAULT 0,
	created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user),
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol),
    FOREIGN KEY (id_state) REFERENCES states(id_state)
);
-- ===================================================
-- DUMPING DATA INTO THE USERS TABLE
-- ===================================================
INSERT INTO users (code_user, first_name, second_name, first_surname, second_surname, image, address, email, password, phone, id_rol, id_state, super_root) VALUES
(101, 'Admin', 'Admin','Admin', 'Admin','admin.png','CARRERA 98 A # 10-20','admin@gmail.com','admin123','3140000000', 1, 1, 1),
(102, 'Jorge', 'Andres','Perez', 'Morales','user.png','TV 65 BIS  # 89-04','jorgeperez10@gmail.com','jorge123','3150000000', 4, 1, 0);

-- ========================  --
-- CREATED TABLE ASSIGN PERMITS --
-- ========================  --
CREATE TABLE assign_permits(
	id INT NOT NULL AUTO_INCREMENT,
    id_user INT,
    id_permit INT,
    id_state INT DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES users (id_user),
	FOREIGN KEY (id_permit) REFERENCES permits (id_permit),
	FOREIGN KEY (id_state) REFERENCES states (id_state)
);

-- ========================  --
-- CREATED TABLE PRODUCTS --
-- ========================  --
CREATE TABLE products(
	id_product INT NOT NULL AUTO_INCREMENT,
    code_product VARCHAR(255),
    name VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT 'product.png',
    slug VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    id_category INT NOT NULL,
    id_state INT NOT NULL DEFAULT 1,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id_product),
    FOREIGN KEY (id_category) REFERENCES categories (id_category),
    FOREIGN KEY (id_state) REFERENCES states(id_state)
);

-- ===================================================
-- DUMPING DATA INTO THE PRODUCTS TABLE
-- ===================================================
INSERT INTO products (code_product, name, description, image, slug, price, id_category, id_state) VALUES
('1001', 'Panel Solar 100W', 'Panel Solar de 100W', 'panel-solar-100W.jpg', 'panel-solar', 800000, 1,1),
('2001', 'Regulador Solar 100W', 'Regulador Solar de 100W', 'regulador-100W.jpg', 'regulador-100w', 300000, 2, 1),
('7001', 'Kit Solar 100W', 'Kit Solar de 100W', 'kit-solar-100W.jpg', 'kit-solar', 5800000, 7, 1);

-- ========================  --
-- CREATED TABLE INVENTORIES --
-- ========================  --
CREATE TABLE inventories(
	id_inventory INT NOT NULL AUTO_INCREMENT,
	id_product INT,
    stock INT NOT NULL,
	date_entry TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    location VARCHAR (100) DEFAULT NULL,
	PRIMARY KEY(id_inventory),
    FOREIGN KEY (id_product) REFERENCES products(id_product)
);


-- ================================
-- CREATED TABLE FEATURES PRODUCTS
-- ================================
CREATE TABLE feature_products(
	id INT NOT NULL AUTO_INCREMENT,
    feature VARCHAR(50),
    value_feature VARCHAR(80),
    id_product INT,
    PRIMARY KEY (id),
    FOREIGN KEY (id_product) REFERENCES products (id_product)
);

-- ========================  --
-- CREATED TABLE SHOPPING CART --
-- ========================  --

CREATE TABLE shopping_cart(
	id_shopping INT NOT NULL AUTO_INCREMENT,
    id_user INT,
    id_product INT,
    quantity INT,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_shopping),
    FOREIGN KEY (id_user) REFERENCES users (id_user),
    FOREIGN KEY (id_product) REFERENCES products(id_product)
);

-- ========================  --
-- CREATED TABLE ORDERS --
-- ========================  --
CREATE TABLE orders(
	id_order INT NOT NULL AUTO_INCREMENT,
    code_order VARCHAR(255),
    total INT,
    payment_method VARCHAR(200),
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_user INT,
    id_state INT,
    PRIMARY KEY (id_order),
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_state) REFERENCES states(id_state)
);

-- ========================  --
-- CREATED TABLE DETAILS ORDERS --
-- ========================  --

CREATE TABLE details_orders(
	id INT NOT NULL AUTO_INCREMENT,
    id_order INT,
    id_product INT,
    quantity INT,
    unite_price INT,
    PRIMARY KEY (id),
    FOREIGN KEY (id_order) REFERENCES orders(id_order),
    FOREIGN KEY (id_product) REFERENCES products(id_product)
);