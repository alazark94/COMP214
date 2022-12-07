<?php

/** Instead of creating multiple instance for every request
 * creating it once at the top and calling that instacne 
 * for every query is much better.
 */
require 'functions.php';  // Added by KD
require 'Database.php'; // By Added by KD

$config = require('config.php'); // By Added by KD

// will catch error whne DB connection failed 
try {

    $db = new Database($config['database'], 'root', $config['database']['password']);
} catch (Exception $e) {
    dd($e->getMessage());
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
                    <h3>Employee Hiring Form</h3>
                    <label for="fName"> First Name:</label>
                    <input type="text" name="fName" id="firstName" />
                    <br>
                    <label for="lName"> Last Name:</label>
                    <input type="text" name="lName" id="lastName" />
                    <br>
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="empEmail" />
                    <br>
                    <label for="phone"> Phone:</label>
                    <input type="text" name="phone" id="empPhone" />
                    <br>
                    <label for="hDate"> Hire Date:</label>
                    <?php
                    //---------------------------------------------------------------------

                    try {
                        $sql = "select SYSDATE() as date";
                        $nRows = $db->query('SELECT COUNT(*) FROM `date`')->fetchColumn();
                        $result = $db->query($sql);
                        if ($nRows > 0) {
                            while ($row = $result->fetch()) {
                                echo "<input type='date' name='hDate' readonly id='hireDate' value=" . $row['date'] . " />";
                            }
                        } else {
                            echo "0 results";
                        }
                    } catch (Exception $e) {
                        dd($e->getMessage());
                    }

                    ?>
                    <br>
                    <label for="salary">Salary:</label>
                    <input type="text" name="salary" id="empSalary" />
                    <br>
                    <input type="hidden" name="jobTitle" id="jTitle" />
                    <input type="hidden" name="jobID" id="jID" />
                    <input type="hidden" name="managerID" id="manID">
                    <br>
                    <div class="job">
                        <label for="job">Job:</label>
                        <select name="jobDropdown" id="job">
                            <?php

                            try { // Added by KD (exception)
                                //---------------------------------------------------------------------

                                echo "<option selected='selected'></option>";
                                $nRows = $db->query('SELECT COUNT(*) FROM `jobs`')->fetchColumn();
                                $sql = "select * from jobs";
                                $result = $db->query($sql);

                                if ($nRows > 0) {
                                    while ($row = $result->fetch()) {
                                        echo "<option value='" . $row['job_id'] . "'>" . $row['job_title'] . "</option>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                            } catch (Exception $e) {
                                dd($e->getMessage());
                            }


                            ?>
                        </select>
                    </div>
                    <div class="manager">
                        <label for="manager">Manager:</label>
                        <select name="manager" id="managerDropdown">
                            <?php

                            //---------------------------------------------------------------------
                            try {
                                echo "<option selected='selected'></option>";
                                $sql = "select employees.employee_id, employees.first_name, employees.last_name, departments.department_name from 
                                        employees join departments on departments.manager_id = employees.employee_id order by employee_id";
                                $nRows = $db->query('SELECT COUNT(*) FROM 
                                `employees` JOIN `departments` ON `departments.manager_id` = `employees.employee_id` ORDER BY `employee_id`')->fetchColumn();
                                $result = $db->query($sql);
                                if ($nRows > 0) {
                                    while ($row = $result->fetch()) {
                                        echo "<option value='" . $row['employee_id'] . "'>" . $row['department_name'] . ':' . " " . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                            } catch (Exception $e) {
                                dd($e->getMessage());
                            }


                            ?>
                        </select>
                    </div>
                    <?php
                    //---------------------------------------------------------------------
                    try {
                        echo "<div class='department'>
                                    <label for='dep'>Department:</label>
                                    <select name='deptDropdown' id='dep'>
                                    <option selected=''selected'></option>";
                        $sql = "select * from departments";
                        $nRows = $db->query('SELECT COUNT(*) FROM `departments`')->fetchColumn();
                        $result = $db->query($sql);
                        if ($nRows > 0) {
                            while ($row = $result->fetch()) {
                                echo "<option value='" . $row['department_id'] . "'>" . $row['department_name'] . "</option>";
                            }
                        } else {
                            echo "0 results";
                        }
                        echo "</select>
                                <button type='submit' id='jobDescButton' name='getJobDesc'>Get Job Description</button>
                                </div>";
                    } catch (Exception $e) {
                        dd($e->getMessage());
                    }
                    ?>
                </fieldset>
                <input class="btn submit" type="submit" name="hireEmp" value="Hire" style="border-style: solid; cursor: pointer;" />
                <input class="btn" type="reset" value="Cancel" style="color: red; border-style: solid; border-color: blue; cursor: pointer;" />
                <?php

                try {
                    if (isset($_POST['getJobDesc'])) {
                        $departmentID = $_POST['deptDropdown'];
                        echo "<br><table id='departmentTable'>
                                    <tr>
                                    <th>Department ID</th>
                                    <th>Department Name</th>
                                    </tr>";

                        $sql = "select * from departments where department_id = $departmentID";
                        $nRows = $db->query('SELECT COUNT(*) FROM `department` WHERE `department_id` = ' . $departmentID)->fetchColumn();
                        $result = $db->query($sql);
                        if ($nRows > 0) {
                            while ($row = $result->fetch()) {
                                echo "<tr>
                                 <td>" . $row['department_id'] . " </td>
                                 <td>" . $row['department_name'] . "</td>
                                </tr>";
                            }
                        } else {
                            echo "0 results";
                        }
                        echo "</table>";
                    }

                    if (isset($_POST['hireEmp'])) {
                        $fName = $_POST['fName'];
                        $lName = $_POST['lName'];
                        $email = $_POST['email'];
                        $phone = $_POST['phone'];
                        $salary = $_POST['salary'];
                        $hireDate = $_POST['hDate'];
                        $jobID = $_POST['jobID'];
                        $manID = $_POST['managerID'];
                        $deptID = $_POST['depID'];
                        if (empty($fName)) {
                            echo "Name is empty";
                        } else {
                            $sql = "CALL Employee_hire_sp('$fName', '$lName', '$email', '$phone','$salary', '$hireDate','$jobID', '$manID', '$deptID')";
                            $result = $db->query($sql);
                            echo "<br>Successfully created employee record for $fName $lName!";
                        }
                    }
                } catch (Exception $e) {
                    dd($e->getMessage());
                }

                ?>
                <input type='hidden' name='depID' id='deptIDField'>
            </form>
        </div>
    </div>
    <script src="./scripts/groupAssignment4.js"></script>
</body>

</html>