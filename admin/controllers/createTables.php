<?php
    $statements =[
        'CREATE TABLE IF NOT EXISTS country(
            id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(10) NOT NULL,
            currency VARCHAR(10) NOT NULL 
        )',
        'CREATE TABLE IF NOT EXISTS exchange_rate(
            id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            s_country INT(5) NOT NULL,
            r_country INT(5) NOT NULL,
            s_rate DECIMAL(10,2) NOT NULL,
            r_rate DECIMAL(10,2) NOT NULL,
            CONSTRAINT excahange_rate_fk1
                FOREIGN KEY (s_country)
                REFERENCES country (id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT,
            CONSTRAINT excahange_rate_fk2
                FOREIGN KEY (r_country)
                REFERENCES country (id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        )',
        'CREATE TABLE IF NOT EXISTS sender(
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_phone VARCHAR(20) NOT NULL UNIQUE,
            sender_name VARCHAR(255) NOT NULL,
            sender_country INT(5) NOT NULL,
            date_inserted DATETIME,
            CONSTRAINT sender_fk1
                FOREIGN KEY(sender_country)
                REFERENCES country(id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        )',
        'CREATE TABLE IF NOT EXISTS transaction (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            transaction_id VARCHAR(100) NOT NULL UNIQUE,
            reciever_phone VARCHAR(20) NOT NULL,
            reciever_name VARCHAR(255) NOT NULL,
            reciever_country INT(5) NOT NULL,
            reciever_payment_mode VARCHAR(50) NOT NULL,
            s_amount DECIMAL(24,4) NOT NULL,
            r_amount DECIMAL(24,4) NOT NULL,
            exchange_rate INT(3) NOT NULL,
            agent_commission DECIMAL(10,2) NOT NULL,
            admin_commission DECIMAL(10,2) NOT NULL,
            total_commission FLOAT NOT NULL,
            sender_id INT(11) NOT NULL,
            date_inserted DATETIME,
            date_updated DATETIME,
            status VARCHAR(20) NOT NULL,
            CONSTRAINT transaction_fk1
                FOREIGN KEY(sender_id)
                REFERENCES sender(id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT,
            CONSTRAINT transaction_fk2
                FOREIGN KEY(reciever_country)
                REFERENCES country(id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT,
            CONSTRAINT transaction_fk4
                FOREIGN KEY(exchange_rate)
                REFERENCES exchange_rate(id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        )',
        'CREATE TABLE IF NOT EXISTS settings(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            prop VARCHAR(20) NOT NULL,
            value VARCHAR(255) NOT NULL
        )',
    ];

    try {
        require_once '../../controllers/db_controller.php';
        $main = new Main(new SqlStringBuilder(), new Model());
        foreach ($statements as $sql) {
            $main->createAuthTable($sql);
        }
    } catch (\Throwable $e) {
        die($e->getMessage());
    }
?>