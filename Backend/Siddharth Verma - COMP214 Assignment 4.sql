-- Task 1-1 

DELIMITER //
CREATE PROCEDURE Employee_hire_sp (
	IN p_first_name VARCHAR(255),
    IN p_last_name VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_phone VARCHAR(255),
    IN p_salary DECIMAL(8,2),
    IN p_hire_date DATE,
    IN p_job_id VARCHAR(255),
    IN p_manager_id INT,
    IN p_department_id INT)
BEGIN
    DECLARE max_id INT;
    SELECT MAX( employee_id ) into max_id FROM employees;
    INSERT INTO employees (EMPLOYEE_ID, FIRST_NAME, LAST_NAME, EMAIL,PHONE_NUMBER, HIRE_DATE,JOB_ID, SALARY, MANAGER_ID, DEPARTMENT_ID) 
    VALUES (max_id+1, p_first_name, p_last_name, p_email, p_phone, p_hire_date, p_job_id, p_salary, p_manager_id, p_department_id); 

END //

DELIMITER ;

CALL Employee_hire_sp('John', 'Wilson', 'JWILSON', '905.456.4567',22000, '22-02-01','FI_ACCOUNT', 108, 100);

select * from employees;

-- Task 1-3
DELIMITER //
CREATE PROCEDURE Employee_update (
	
    IN p_id INT,
    IN p_email VARCHAR(255),
    IN p_phone VARCHAR(255),
	IN p_salary DECIMAL(8,2)
)
BEGIN
    UPDATE employees SET salary = p_salary, phone_number = p_phone, email = p_email WHERE employee_id = p_id;

END //

DELIMITER ;

CALL Employee_update(207, 'JWILS', '905.456.4568',21000);


-- Task 2-1
DELIMITER $$

CREATE FUNCTION get_job(
	p_job_id VARCHAR(255)
) 
RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
    DECLARE local_variable VARCHAR(200);
	SELECT job_title INTO local_variable FROM jobs WHERE job_id = p_job_id;
	RETURN local_variable;
END$$
DELIMITER ;

drop function get_job;

SELECT GET_JOB('IT_WEB');

select * from jobs;




-- Task 2-3
DELIMITER //
CREATE PROCEDURE new_job (
	IN p_job_id VARCHAR(255),
    IN p_job_title VARCHAR(255),
    IN p_min_salary DECIMAL(8,0),
    IN p_max_salary DECIMAL(8,0)
)
BEGIN
    INSERT INTO jobs (job_id, job_title, min_salary, max_salary) 
    VALUES (p_job_id, p_job_title, p_min_salary, p_max_salary); 
    SELECT 'New row added successfully' AS SUCCESS, CONCAT_WS(' ', p_job_id, p_job_title,p_min_salary,p_max_salary) AS NEW_JOB_ADDED;
END //
COMMIT;
DELIMITER ;

CALL new_job('AS_MAN', 'ASSISTANT MANAGER', 3500, 5500);


-- Task 2-2
DELIMITER //
CREATE PROCEDURE update_job (
	IN p_job_id VARCHAR(255),
    IN p_job_title VARCHAR(255),
    IN p_min_salary DECIMAL(8,0),
    IN p_max_salary DECIMAL(8,0)
)
BEGIN
	
    UPDATE jobs SET min_salary = p_min_salary , max_salary = p_max_salary, job_title = p_job_title WHERE job_id = UPPER(p_job_id);
    COMMIT;
END //

DELIMITER ;

UPDATE employees SET job_id = 'HR_REP' where employee_id = 115;



-- Task 3
DELIMITER //
CREATE PROCEDURE check_salary (
	IN p_job_id VARCHAR(255),
    IN p_salary DECIMAL(8,0)
)
BEGIN
	DECLARE v_min_sal DECIMAL(8,0);
	DECLARE v_max_sal DECIMAL(8,0);
    SELECT min_salary, max_salary INTO v_min_sal, v_max_sal FROM jobs WHERE job_id = UPPER(p_job_id);
    IF p_salary NOT BETWEEN v_min_sal AND v_max_sal THEN 
		set @message_text = CONCAT('Invalid Salary $',p_salary, '. Salary for job ',p_job_id,' must be between $',v_min_sal,' and $',v_max_sal,'.');
		signal sqlstate '45000' 
        set message_text =  @message_text;
    END IF;
END //
COMMIT;
DELIMITER ;

drop procedure check_salary;

DELIMITER //
CREATE TRIGGER check_salary_trg_1
BEFORE INSERT ON EMPLOYEES
FOR EACH ROW
	CALL check_salary(new.job_id, new.salary);
//

DELIMITER //
CREATE TRIGGER check_salary_trg_2
BEFORE UPDATE ON EMPLOYEES
FOR EACH ROW
	CALL check_salary(new.job_id, new.salary);
//



CALL Employee_hire_sp('Elenor', 'Beh', 'abc@abc', '905.456.4569',22000, '22-02-01','SA_REP', 145, 30);

UPDATE EMPLOYEES SET SALARY = 2000 WHERE EMPLOYEE_ID = '115';

UPDATE EMPLOYEES SET JOB_ID = 'HR_REP' WHERE EMPLOYEE_ID = '115';




select * from employees;




/* CREATE PROCEDURE Employee_hire_sp
(
    p_first_name IN hr_employees.first_name%TYPE,
    p_last_name IN hr_employees.last_name%TYPE,
    p_email IN hr_employees.email%TYPE,
    p_phone IN hr_employees.phone_number%TYPE,
    p_salary IN hr_employees.salary%TYPE,
    p_hire_date IN hr_employees.hire_date%TYPE,
    p_job_id IN hr_employees.job_id%TYPE,
    p_manager_id IN hr_employees.manager_id%TYPE,
    p_department_id IN hr_employees.department_id%TYPE
)
IS
NO_ROWS_INSERTED EXCEPTION;
BEGIN
    INSERT INTO hr_employees (EMPLOYEE_ID, FIRST_NAME, LAST_NAME, EMAIL,PHONE_NUMBER, HIRE_DATE,JOB_ID, SALARY, MANAGER_ID, DEPARTMENT_ID) 
    VALUES (HR_employees_seq.NEXTVAL, p_first_name, p_last_name, p_email, p_phone, p_hire_date, p_job_id, p_salary, p_manager_id, p_department_id); 

EXCEPTION
    WHEN NO_ROWS_INSERTED THEN
    DBMS_OUTPUT.PUT_LINE('Unable to add employee');
COMMIT;
END Employee_hire_sp;

EXECUTE Employee_hire_sp('John', 'Wilson', 'JWILSON', '905.456.4567',22000, '22-02-01','FI_ACCOUNT', 108, 100);


-- Task 1-3
CREATE OR REPLACE PROCEDURE Employee_update
(
    p_id IN hr_employees.employee_id%TYPE,
    p_email IN hr_employees.email%TYPE,
    p_phone IN hr_employees.phone_number%TYPE,
    p_salary IN hr_employees.salary%TYPE
)
IS
UPDATE_ERROR EXCEPTION;
local_var_rows NUMBER;
BEGIN
    UPDATE hr_employees SET salary = p_salary, phone_number = p_phone, email = p_email WHERE employee_id = p_id; 
    local_var_rows := SQL%ROWCOUNT;
    
    IF SQL%NOTFOUND THEN
        RAISE UPDATE_ERROR;
    ELSE
        DBMS_OUTPUT.PUT_LINE('Updated ' || local_var_rows || ' record(s)');
    END IF;
EXCEPTION
    WHEN UPDATE_ERROR THEN
        DBMS_OUTPUT.PUT_LINE('Unable to update employee(s)');
COMMIT;
END Employee_update;

EXECUTE Employee_update(209, 'JWILS', '905.456.4568',21000);

SELECT * FROM HR_EMPLOYEES;
select * from hr_jobs;

-- Task 2-1
CREATE OR REPLACE FUNCTION get_job
(
    p_job_id IN VARCHAR2
)
RETURN VARCHAR2 IS
local_variable VARCHAR(200);
BEGIN
    SELECT job_title INTO local_variable FROM HR_JOBS WHERE job_id = p_job_id;
    RETURN local_variable;

EXCEPTION
    WHEN NO_DATA_FOUND
        THEN DBMS_OUTPUT.PUT_LINE('Invalid job id. Please try again.');
END get_job;

EXECUTE DBMS_OUTPUT.PUT_LINE (get_job ('IT_WEB')); */

