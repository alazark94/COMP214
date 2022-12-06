<?php

class Database
{

    protected $pdo;

    public function __construct($config, $username = 'root', $password = '')
    {
        $dsn = "mysql:" . urldecode(http_build_query($config, "", ";",));

        // $dsn = "mysql:host=localhost;port=3306;dbname=kidproject;user=alazar;password=Alutiye!0;charset=utf8mb4";

        $this->pdo = new PDO($dsn,  $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query)
    {

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        return $statement;
    }
}
