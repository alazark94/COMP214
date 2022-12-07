
<?php

require 'functions.php';  
require 'Database.php'; 

$config = require('config.php'); 

// will catch error when DB connection failed 
try {

    $db = new Database($config['database'], 'root', $config['database']['password']);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Job Description</title>
</head>

<body>
    <div>
        <form>
            <fieldset style="margin-top: 250px">
                <div style="text-align: center">
                    <h2 style="color: red">Job Description</h2>
                    Job ID: <input type="text"><br><br>
                    <h3 style="color: black; font-weight: 100">Description:</h3><div><span></span></div>
                </div>
            </fieldset>
        </form>
    </div>
</body>