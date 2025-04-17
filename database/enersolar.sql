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

-- ========================  --
-- CREATED TABLE ROLES --
-- ========================  --
CREATE TABLE roles(
	id_rol INT NOT NULL AUTO_INCREMENT,
    rol VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_rol)
);

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

-- ========================  --
-- CREATED TABLE PERMITS --
-- ========================  --
CREATE TABLE permits(
	id_permit INT NOT NULL AUTO_INCREMENT,
    code_permit INT,
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
    image VARCHAR(255),
    address VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
	phone VARCHAR(80),
    token VARCHAR(255),
    method VARCHAR(255),
    id_rol INT,
    id_state INT DEFAULT 1,
	created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user),
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol),
    FOREIGN KEY (id_state) REFERENCES states(id_state)
);

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
    code_product INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    slug VARCHAR(100) NOT NULL,
    stock INT NOT NULL,
    price INT NOT NULL,
    id_category INT,
    id_state INT DEFAULT 1,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id_product),
    FOREIGN KEY (id_category) REFERENCES categories (id_category),
    FOREIGN KEY (id_state) REFERENCES states(id_state)
);

-- ========================  --
-- CREATED TABLE FEATURES PRODUCTS --
-- ========================  --
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
    code_order INT,
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