<?php

class Database {
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $connection;

    public function __construct($host, $username, $password, $dbname) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    // Method to connect to the database
    public function connect() {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);

        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    // Method to execute a query
    public function query($sql) {
        $result = mysqli_query($this->connection, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($this->connection));
        }

        return $result;
    }

    // Method to fetch results as an associative array for SELECT queries
    public function fetchResults($result) {
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Method to close the connection
    public function close() {
        mysqli_close($this->connection);
    }
}

?>
