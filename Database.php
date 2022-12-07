<!-- database connection -->
<?php

class Database
{

    protected $pdo;

    public function __construct($config, $username = 'root', $password = '')
    {
        $dsn = "mysql:" . urldecode(http_build_query($config, "", ";",));

      

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
