DROP TABLE language CASCADE;
DROP TABLE paste CASCADE;

CREATE TABLE language (
  lan_id        SERIAL  NOT NULL PRIMARY KEY,
  lan_name      TEXT    NOT NULL UNIQUE,
  lan_file      TEXT    NOT NULL,
  lan_add_dt    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE paste (
  lan_id        INT     NOT NULL, -- FK language

  pas_id        SERIAL  NOT NULL PRIMARY KEY,
  pas_author    TEXT    NULL,
  pas_title     TEXT    NULL,
  pas_source    TEXT    NOT NULL,
  pas_source_text TEXT  NOT NULL,
  pas_length    INT     NOT NULL DEFAULT 0,
  pas_view_qt   INT     NOT NULL DEFAULT 0,
  pas_usr_ip    INET    NOT NULL,
  pas_md5       TEXT    NOT NULL UNIQUE,
  pas_add_dt    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE paste ADD
  FOREIGN KEY (lan_id) REFERENCES language (lan_id)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

INSERT INTO language (lan_name,lan_file) VALUES ('ActionScript', 'actionscript');
INSERT INTO language (lan_name,lan_file) VALUES ('Ada', 'ada');
INSERT INTO language (lan_name,lan_file) VALUES ('Apache Log File', 'apache');
INSERT INTO language (lan_name,lan_file) VALUES ('ASM (NASM based)', 'asm');
INSERT INTO language (lan_name,lan_file) VALUES ('ASP', 'asp');
INSERT INTO language (lan_name,lan_file) VALUES ('Bash', 'bash');
INSERT INTO language (lan_name,lan_file) VALUES ('C', 'c');
INSERT INTO language (lan_name,lan_file) VALUES ('C++', 'cpp');
INSERT INTO language (lan_name,lan_file) VALUES ('C#', 'csharp');
INSERT INTO language (lan_name,lan_file) VALUES ('CSS', 'css');
INSERT INTO language (lan_name,lan_file) VALUES ('Delphi', 'delphi');
INSERT INTO language (lan_name,lan_file) VALUES ('HTML', 'html4strict');
INSERT INTO language (lan_name,lan_file) VALUES ('Java', 'java');
INSERT INTO language (lan_name,lan_file) VALUES ('Javascript', 'javascript');
INSERT INTO language (lan_name,lan_file) VALUES ('Lisp', 'lisp');
INSERT INTO language (lan_name,lan_file) VALUES ('Lua', 'lua');
INSERT INTO language (lan_name,lan_file) VALUES ('Objective C', 'objc');
INSERT INTO language (lan_name,lan_file) VALUES ('Pascal', 'pascal');
INSERT INTO language (lan_name,lan_file) VALUES ('Perl', 'perl');
INSERT INTO language (lan_name,lan_file) VALUES ('PHP', 'php');
INSERT INTO language (lan_name,lan_file) VALUES ('Python', 'python');
INSERT INTO language (lan_name,lan_file) VALUES ('Quick BASIC', 'qbasic');
INSERT INTO language (lan_name,lan_file) VALUES ('SQL', 'sql');
INSERT INTO language (lan_name,lan_file) VALUES ('VisualBasic', 'vb');
INSERT INTO language (lan_name,lan_file) VALUES ('VB.NET', 'vbnet');
INSERT INTO language (lan_name,lan_file) VALUES ('XML', 'xml');
