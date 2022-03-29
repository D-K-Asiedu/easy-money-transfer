<?php 

    $statements = [
        'CREATE TABLE IF NOT EXISTS admin( 
            id   INT(5) AUTO_INCREMENT,
            username  VARCHAR(100) NOT NULL, 
            password VARCHAR(255) NULL, 
            name   VARCHAR(100) NULL,
            PRIMARY KEY(id)
        )',
        'CREATE TABLE IF NOT EXISTS admin_files (
            id   INT(11) NOT NULL, 
            path VARCHAR(255) NOT NULL,
            description VARCHAR(255),
            restricted TINYINT(1) NOT NULL,
            date_inserted DATETIME,
            PRIMARY KEY(id)   
        )',
        'CREATE TABLE IF NOT EXISTS admin_perm (
        id   INT(11) NOT NULL, 
        permission VARCHAR(255) NOT NULL,
        date_inserted DATETIME,
        description VARCHAR(255),
        restricted TINYINT(1) NOT NULL,
        PRIMARY KEY(id)   
        )',
        'CREATE TABLE IF NOT EXISTS admin_role (
            id   INT(11) NOT NULL, 
            custom_id VARCHAR(50) NOT NULL UNIQUE,
            role VARCHAR(255) NOT NULL,
            date_inserted DATETIME,
            PRIMARY KEY(id)  
        )',
       'CREATE TABLE IF NOT EXISTS admin_role_files_link (
            id   INT(11) NOT NULL, 
            role_id INT(11) NOT NULL,
            file_id INT(11) NOT NULL,
            arf_link_restricted TINYINT(1) NOT NULL,
            date_inserted DATETIME,
            PRIMARY KEY(id),  
            CONSTRAINT adm_rol_fil_fk1 
                FOREIGN KEY(file_id) 
                REFERENCES admin_files(id) 
                ON UPDATE CASCADE
                ON DELETE RESTRICT, 
            CONSTRAINT adm_rol_fil_fk2  
                FOREIGN KEY(role_id) 
                REFERENCES admin_role(id) 
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        )',
        'CREATE TABLE IF NOT EXISTS admin_role_link (
            id   INT(11) NOT NULL, 
            role_id INT(11) NOT NULL,
            admin_id INT(11) NOT NULL,
            date_inserted DATETIME,
            PRIMARY KEY(id),  
            CONSTRAINT admin_role_link_fk1 
                FOREIGN KEY(admin_id) 
                REFERENCES admin(id) 
                ON UPDATE CASCADE
                ON DELETE RESTRICT, 
            CONSTRAINT admin_role_link_fk2  
                FOREIGN KEY(role_id) 
                REFERENCES admin_role(id) 
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        )',
        'CREATE TABLE IF NOT EXISTS admin_role_perm (
            id   INT(11) NOT NULL, 
            role_id INT(11) NOT NULL,
            permission_id INT(11) NOT NULL,
            ar_link_restricted INT(11) NOT NULL,
            date_inserted DATETIME,
            PRIMARY KEY(id),  
            CONSTRAINT role_perm_fk1 
                FOREIGN KEY(role_id) 
                REFERENCES admin_role(id) 
                ON UPDATE CASCADE
                ON DELETE RESTRICT,
            CONSTRAINT role_perm_fk2 
                FOREIGN KEY(permission_id) 
                REFERENCES admin_perm(id) 
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        )'
    ];

    try {
        require_once '../controllers/db_controller.php';
        $main = new Main(new SqlStringBuilder(), new Model());
        foreach ($statements as $sql) {
            $main->createAuthTable($sql);
        }
    } catch (\Throwable $e) {
        die($e->getMessage());
    }

?>