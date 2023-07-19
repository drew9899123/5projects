CREATE TABLE teacher (
    teacher_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL
);

CREATE TABLE department (
    dept_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(60) NOT NULL,
    teacher_id INT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
);

CREATE TABLE studyfield (
    field_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    field_name VARCHAR(60) NOT NULL,
    dept_id INT NOT NULL,
    FOREIGN KEY (dept_id) REFERENCES department(dept_id)
);

CREATE TABLE study_group (
    group_id VARCHAR(9) NOT NULL PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    teacher_id INT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
);

CREATE TABLE student (
    std_id VARCHAR(11) NOT NULL PRIMARY KEY,
    password VARCHAR(12) NOT NULL,
    prefix VARCHAR(10) NOT NULL,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30),
    level VARCHAR(30) NOT NULL,
    field_id INT NOT NULL,
    group_id VARCHAR(9) NOT NULL,
    system VARCHAR(10) NOT NULL,
    tel VARCHAR(100),
    vac_id INT NULL,
    pat_id INT NULL,
    progression_id INT NULL,
    FOREIGN KEY (field_id) REFERENCES studyfield(field_id),
    FOREIGN KEY (group_id) REFERENCES study_group(group_id),
    FOREIGN KEY (vac_id) REFERENCES vacinated_status(vac_id),
    FOREIGN KEY (pat_id) REFERENCES patien_status(pat_id),
    FOREIGN KEY (progression_id) REFERENCES progression(progression_id)
);

create table schedule(
    schedule_id int AUTO_INCREMENT PRIMARY KEY,
	term_year varchar(6) not null,
    start_date date not null,
    finish_date date not null,
    doc_return_date date not null
);

create table supervision (
    	dept_id int not null PRIMARY KEY,
    	teacher_id int,
    	FOREIGN KEY (dept_id) REFERENCES department(dept_id),
    	FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
    );

    create table app_system (
	system_id int AUTO_INCREMENT PRIMARY KEY not null,
    system_name varchar(60) not null,
    activation int not null
)

create table admin (
    	admin_id int not null AUTO_INCREMENT PRIMARY KEY,
    	username varchar(30) not null,
    	password varchar(12) not null,
    	rank varchar(20) not null
    );