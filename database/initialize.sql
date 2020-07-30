CREATE TABLE users(
	id VARCHAR(100) PRIMARY KEY,
	first_name VARCHAR(100) NOT NULL,
	last_name VARCHAR(100) NOT NULL,
	password_hashed VARCHAR(150) NOT NULL,
	permissions JSON NOT NULL DEFAULT 'null'
)ENGINE=InnoDB;

CREATE TABLE countries(
	code CHAR(2) PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	image VARCHAR(100) NULL DEFAULT NULL
)ENGINE=InnoDB;

CREATE TABLE persons(
	id SMALLINT(3) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	first_name VARCHAR(100) NOT NULL,
	second_name VARCHAR(100) NULL DEFAULT NULL,
	last_name VARCHAR(100) NOT NULL,
	birthdate DATE NULL DEFAULT NULL,
	
	birth_country CHAR(2) NOT NULL,
	INDEX fk_birth_country(birth_country),
	FOREIGN KEY (birth_country) REFERENCES countries(code) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE editors(
	id SMALLINT(3) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL
)ENGINE=InnoDB;

CREATE TABLE collections(
	id SMALLINT(3) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	editor SMALLINT(3) UNSIGNED NOT NULL,
	INDEX fk_editor(editor),
	FOREIGN KEY (editor) REFERENCES editors(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books(
	id SMALLINT(4) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(200) NOT NULL,
	INDEX FULLTEXT idx_title(title),
	editor SMALLINT(3) UNSIGNED NULL DEFAULT NULL,
	collection SMALLINT(3) UNSIGNED NULL DEFAULT NULL,
	cover VARCHAR(100) NULL DEFAULT NULL,
	back_cover VARCHAR(100) NULL DEFAULT NULL,
	spinner VARCHAR(100) NULL DEFAULT NULL,
	INDEX fk_collection(collection),
	FOREIGN KEY (collection) REFERENCES collections(id) ON DELETE CASCADE ON UPDATE CASCADE,
	INDEX fk_editor(editor),
	FOREIGN KEY (editor) REFERENCES editors(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_contributors(
	book SMALLINT(4) UNSIGNED NOT NULL,
	person SMALLINT(4) UNSIGNED NOT NULL,
	`type` ENUM('AUTHOR', 'TRANSLATOR', 'ILLUSTRATOR'),
	PRIMARY KEY (book, person, `type`),
	FOREIGN KEY (book) REFERENCES books(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (person) REFERENCES persons(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE books_lists(
	book SMALLINT(4) UNSIGNED NOT NULL,
	reader VARCHAR(100) NOT NULL,
	`type` ENUM('READING', 'OWNERSHIP', 'WISH'),
	PRIMARY KEY (book, reader, `type`),
	INDEX fk_reader(reader),
	FOREIGN KEY (book) REFERENCES books(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (reader) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE persons_followed(
	reader VARCHAR(100) NOT NULL,
	person SMALLINT(3) UNSIGNED NOT NULL,
	`type` ENUM('AUTHOR', 'TRANSLATOR', 'ILLUSTRATOR'),
	
	PRIMARY KEY (reader, person, `type`),
	
	INDEX fk_person(person),
	
	FOREIGN KEY (reader) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (person) REFERENCES persons(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE file_resources(
	id SMALLINT(5) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	
	parent_id SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	INDEX fk_parent_id(parent_id),
	FOREIGN KEY (parent_id) REFERENCES file_resources(id) ON DELETE CASCADE ON UPDATE CASCADE,
	
	public_id VARCHAR(20) NULL DEFAULT NULL,
	UNIQUE idx_public_id(public_id),
	
	`type` VARCHAR(20) NOT NULL,
	
	content MEDIUMBLOB NOT NULL,
	
	extension VARCHAR(50) NOT NULL,
	
	version VARCHAR(20) NOT NULL,
	
	UNIQUE idx_parent_id_version(parent_id, version),
	
	data TEXT NOT NULL
)ENGINE=InnoDB;

--
ALTER TABLE books.countries 
    ADD new_image SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    ADD INDEX idx_image(new_image),
    ADD FOREIGN KEY (new_image) REFERENCES resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE
;

ALTER TABLE books.books 
    ADD new_cover SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    ADD INDEX idx_cover(new_cover),
    ADD FOREIGN KEY (new_cover) REFERENCES resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE,
    
    ADD new_back_cover SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    ADD INDEX idx_back_cover(new_back_cover),
    ADD FOREIGN KEY (new_back_cover) REFERENCES resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE,

    ADD new_spinner SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    ADD INDEX idx_spinner(new_spinner),
    ADD FOREIGN KEY (new_spinner) REFERENCES resources.file_resources(id) ON DELETE SET NULL ON UPDATE CASCADE
;

ALTER TABLE countries 
    DROP image,
    CHANGE `new_image` `image` SMALLINT(5) UNSIGNED NULL DEFAULT NULL
;

ALTER TABLE books
    DROP cover,
    DROP back_cover, 
    DROP spinner,
    CHANGE `new_cover` `cover` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    CHANGE `new_back_cover` `back_cover` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    CHANGE `new_spinner` `spinner` SMALLINT(5) UNSIGNED NULL DEFAULT NULL
;  

ALTER TABLE file_resources 
	ADD public_id VARCHAR(20) NULL DEFAULT NULL AFTER parent_id,
	ADD UNIQUE idx_public_id(public_id)
;

# Après exécution du script 
ALTER TABLE `file_resources` CHANGE `public_id` `public_id` VARCHAR(20) NOT NULL; 