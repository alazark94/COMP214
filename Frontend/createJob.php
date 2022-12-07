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
    <title>Employee Hiring Form</title>
</head>

<body>

    <div class="flex-container">

        <div>
            <form name="contact" method="post">
                <fieldset>
                    <h3>Create Job</h3>
                    <label for="jobID"> Job_ID:</label>
                    <input type="text" name="jobID" id="jobID" />
                    <br>
                    <label for="jobID"> Title:</label>
                    <input type="text" name="jobID" id="jobID" />
                    <br>
                    <label for="minSalary">Minimum Salary:</label>
                    <input type="text" name="minSalary" id="minSalary" />
                    <br>
                    <label for="maxSalary"> Maximum Salary:</label>
                    <input type="text" name="maxSalary" id="maxSalary" />
                    <br>
                <input class="createJob" type="submit" name="createJob" value="Create Job" />
                
        </div>
    </div>
    <script src="./scripts/groupAssignment4.js"></script>
</body>
</html>