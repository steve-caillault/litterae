CREATE DATABASE books_demo_resources CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';
CREATE DATABASE books_demo CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';

/***/

CREATE TABLE books_demo_resources.file_resources(
	id SMALLINT(5) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	
	parent_id SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	INDEX fk_parent_id(parent_id),
	FOREIGN KEY (parent_id) REFERENCES file_resources(id) ON DELETE CASCADE ON UPDATE CASCADE,
	
	public_id VARCHAR(20) NOT NULL,
	UNIQUE idx_public_id(public_id),
	
	
	`type` VARCHAR(20) NOT NULL,
	
	content MEDIUMBLOB NOT NULL,
	
	extension VARCHAR(50) NOT NULL,
	
	version VARCHAR(20) NOT NULL,
	
	UNIQUE idx_parent_id_version(parent_id, version),
	
	data TEXT NOT NULL
)ENGINE=InnoDB;

/***/

CREATE TABLE books_demo.users(
	id VARCHAR(100) PRIMARY KEY,
	first_name VARCHAR(100) NOT NULL,
	last_name VARCHAR(100) NOT NULL,
	password_hashed VARCHAR(150) NOT NULL,
	permissions VARCHAR(250) NULL DEFAULT NULL
)ENGINE=InnoDB;

CREATE TABLE books_demo.countries(
	code CHAR(2) PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	image SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	INDEX fk_image(image),
	FOREIGN KEY (image) REFERENCES books_demo_resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_demo.persons(
	id SMALLINT(3) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	first_name VARCHAR(100) NOT NULL,
	second_name VARCHAR(100) NULL DEFAULT NULL,
	last_name VARCHAR(100) NOT NULL,
	birthdate DATE NULL DEFAULT NULL,
	
	birth_country CHAR(2) NOT NULL,
	INDEX fk_birth_country(birth_country),
	FOREIGN KEY (birth_country) REFERENCES countries(code) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_demo.editors(
	id SMALLINT(3) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL
)ENGINE=InnoDB;

CREATE TABLE books_demo.collections(
	id SMALLINT(3) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	editor SMALLINT(3) UNSIGNED NOT NULL,
	INDEX fk_editor(editor),
	FOREIGN KEY (editor) REFERENCES editors(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_demo.books(
	id SMALLINT(4) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(200) NOT NULL,
	FULLTEXT idx_title(title),
	editor SMALLINT(3) UNSIGNED NULL DEFAULT NULL,
	collection SMALLINT(3) UNSIGNED NULL DEFAULT NULL,
	
	cover SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	INDEX fk_cover(cover),
	FOREIGN KEY (cover) REFERENCES books_demo_resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE,
	
	back_cover SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	INDEX fk_back_cover(back_cover),
	FOREIGN KEY (back_cover) REFERENCES books_demo_resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE,
	
	spinner SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	INDEX idx_spinner(spinner),
    FOREIGN KEY (spinner) REFERENCES books_demo_resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE,
	
	INDEX fk_collection(collection),
	FOREIGN KEY (collection) REFERENCES collections(id) ON DELETE CASCADE ON UPDATE CASCADE,
	INDEX fk_editor(editor),
	FOREIGN KEY (editor) REFERENCES editors(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_demo.books_contributors(
	book SMALLINT(4) UNSIGNED NOT NULL,
	person SMALLINT(4) UNSIGNED NOT NULL,
	`type` ENUM('AUTHOR', 'TRANSLATOR', 'ILLUSTRATOR'),
	PRIMARY KEY (book, person, `type`),
	FOREIGN KEY (book) REFERENCES books(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (person) REFERENCES persons(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_demo.books_lists(
	book SMALLINT(4) UNSIGNED NOT NULL,
	reader VARCHAR(100) NOT NULL,
	`type` ENUM('READING', 'OWNERSHIP', 'WISH'),
	PRIMARY KEY (book, reader, `type`),
	INDEX fk_reader(reader),
	FOREIGN KEY (book) REFERENCES books(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (reader) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_demo.persons_followed(
	reader VARCHAR(100) NOT NULL,
	person SMALLINT(3) UNSIGNED NOT NULL,
	`type` ENUM('AUTHOR', 'TRANSLATOR', 'ILLUSTRATOR'),
	
	PRIMARY KEY (reader, person, `type`),
	
	INDEX fk_person(person),
	
	FOREIGN KEY (reader) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (person) REFERENCES persons(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;
