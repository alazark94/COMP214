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
                    require 'functions.php';  // By Alazar
                    require 'Database.php'; // By Alazar

                    $config = require('config.php'); // By Alazar
                    //---------------------------------------------------------------------

                    try {
                        $db = new Database($config['database'], 'root', $config['database']['password']); // By Alazar
                        $sql = "select SYSDATE() as date";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
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
                            require 'functions.php';  // By Alazar
                            require 'Database.php'; // By Alazar

                            $config = require('config.php'); // By Alazar

                            try {
                                $db = new Database($config['database'], 'root', $config['database']['password']); // By Alazar
                                //---------------------------------------------------------------------

                                echo "<option selected='selected'></option>";
                                $sql = "select * from jobs";
                                $result = $db->query($sql);

                                if ($result->rowCount() > 0) {
                                    while ($row = $result->fetchAll()) {
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
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "";
                            $conn = mysqli_connect($servername, $username, $password, $dbname);
                            //---------------------------------------------------------------------
                            if (!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            }

                            echo "<option selected='selected'></option>";
                            $sql = "select employees.employee_id, employees.first_name, employees.last_name, departments.department_name from 
                                        employees join departments on departments.manager_id = employees.employee_id order by employee_id";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $row['employee_id'] . "'>" . $row['department_name'] . ':' . " " . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                }
                            } else {
                                echo "0 results";
                            }
                            mysqli_close($conn);
                            ?>
                        </select>
                    </div>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "";
                    $conn = mysqli_connect($servername, $username, $password, $dbname);
                    //---------------------------------------------------------------------
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    echo "<div class='department'>
                                    <label for='dep'>Department:</label>
                                    <select name='deptDropdown' id='dep'>
                                    <option selected=''selected'></option>";
                    $sql = "select * from departments";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['department_id'] . "'>" . $row['department_name'] . "</option>";
                        }
                    } else {
                        echo "0 results";
                    }
                    echo "</select>
                                <button type='submit' id='jobDescButton' name='getJobDesc'>Get Job Description</button>
                                </div>";
                    mysqli_close($conn);
                    ?>
                </fieldset>
                <input class="btn submit" type="submit" name="hireEmp" value="Hire" style="border-style: solid; cursor: pointer;" />
                <input class="btn" type="reset" value="Cancel" style="color: red; border-style: solid; border-color: blue; cursor: pointer;" />
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "";
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                //---------------------------------------------------------------------
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                if (isset($_POST['getJobDesc'])) {
                    $departmentID = $_POST['deptDropdown'];
                    echo "<br><table id='departmentTable'>
                                <tr>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                </tr>";
                    try {
                        $sql = "select * from departments where department_id = $departmentID";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>
                             <td>" . $row['department_id'] . " </td>
                             <td>" . $row['department_name'] . "</td>
                            </tr>";
                            }
                        } else {
                            echo "0 results";
                        }
                        echo "</table>";
                    } catch (Exception $e) {
                        echo ("Unable to get department information: " . mysqli_error($conn));
                    }
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
                        try {
                            $sql = "CALL Employee_hire_sp('$fName', '$lName', '$email', '$phone','$salary', '$hireDate','$jobID', '$manID', '$deptID')";
                            $result = mysqli_query($conn, $sql);
                            echo "<br>Successfully created employee record for $fName $lName!";
                        } catch (Exception $e) {
                            echo ("Unable to create employee record: " . mysqli_error($conn));
                        }
                    }
                }
                mysqli_close($conn);

                ?>
                <input type='hidden' name='depID' id='deptIDField'>
            </form>
        </div>
    </div>
    <script src="./scripts/groupAssignment4.js"></script>
</body>

</html>