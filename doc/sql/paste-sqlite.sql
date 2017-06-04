DROP TABLE paste;
DROP TABLE language;

CREATE TABLE paste (
  pas_id          INTEGER PRIMARY KEY,

  lan_id          INTEGER   NOT NULL, -- FK language
  pas_title       VARCHAR(100)  NULL,
  pas_author      TEXT      NULL,
  pas_source      TEXT      NOT NULL,
  pas_source_text TEXT      NOT NULL,
  pas_length      INTEGER   NOT NULL DEFAULT 0,
  pas_view_qt     INTEGER   NOT NULL DEFAULT 0,
  pas_usr_ip      VARCHAR(15) NOT NULL,
  pas_add_dt      DATETIME  NULL
);

CREATE TRIGGER trigger_pasteInsert AFTER INSERT ON paste
BEGIN
  UPDATE paste SET
    pas_add_dt = DATETIME('NOW')
  WHERE
    rowid = new.rowid;
END;

CREATE TABLE language (
  lan_id        INTEGER PRIMARY KEY,
  lan_name      VARCHAR(20) NOT NULL UNIQUE,
  lan_file      VARCHAR(20) NOT NULL,
  lan_add_dt    DATETIME    NULL
);

CREATE TRIGGER trigger_languageInsert AFTER INSERT ON language
BEGIN
  UPDATE language SET
    lan_add_dt = DATETIME('NOW')
  WHERE
    rowid = new.rowid;
END;

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
