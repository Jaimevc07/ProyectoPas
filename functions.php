<?php
if (!defined("TIMEZONE")) {
    define("TIMEZONE", "America/Bogota");
}
if (!defined("RFID_STATUS_FILE")) {
    define("RFID_STATUS_FILE", "rfid_status");
}
if (!defined("RFID_STATUS_READING")) {
    define("RFID_STATUS_READING", "r");
}
if (!defined("RFID_STATUS_PAIRING")) {
    define("RFID_STATUS_PAIRING", "p");
}
if (!defined("RFID_STATUS_DELETING")) {
    define("RFID_STATUS_DELETING", "d");
}
if (!defined("PAIRING_EMPLOYEE_ID_FILE")) {
    define("PAIRING_EMPLOYEE_ID_FILE", "pairing_employee_id_file");
}

function Unaccent($string)
{
    return preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
}

function getEmployeesWithRfid()
{
    $query = "SELECT employee_id, rfid_serial FROM employee_rfid";
    $db = getDatabase();
    $statement = $db->query($query);
    return $statement->fetchAll();
}

function onRfidSerialRead($rfidSerial)
{
    if (getReaderStatus() === RFID_STATUS_PAIRING) {
        if (pairEmployeeWithRfid($rfidSerial, getPairingEmployeeId())){
            setReaderStatus(RFID_STATUS_READING);
        }
    } else if (getReaderStatus() === RFID_STATUS_DELETING) {
        if (unpairEmployeeWithRfid($rfidSerial, getPairingEmployeeId())) {
            $e = getEmployeeStatusById(getPairingEmployeeId());
            if ($e->status === "presence"){
                saveEmployeeAttendance(getPairingEmployeeId());
            }
            setReaderStatus(RFID_STATUS_READING);
        }
    } else {
        $employee = getEmployeeByRfidSerial($rfidSerial);
        if ($employee) {
            saveEmployeeAttendance($employee->id);
        }
    }
}

function deleteEmployeeAttendanceByIdAndDate($employeeId, $date)
{

    $query = "DELETE FROM employee_attendance where employee_id = ? and date = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    return $statement->execute([$employeeId, $date]);
}

include_once "get_time_worked.php";

function saveEmployeeAttendance($employeeId)
{
    $date = date("Y-m-d");
    deleteEmployeeAttendanceByIdAndDate($date, $employeeId);
    $employee = getEmployeeCentinela($employeeId, $date);
    //print_r($employee);
    if (count($employee) != 0) {
        $status = "absence";
        $hour_out = date('Y-m-d H:i:s');
        $hour_worked = calculartiempo($employee[0]->hour, $hour_out);
        $query = "UPDATE employee_attendance SET hour_out = ?, status = ?, hour_worked = ? WHERE hour_out = -1 AND date = ? AND employee_id = ?";
        $db = getDatabase();
        $statement = $db->prepare($query);
        return $statement->execute([$hour_out, $status, $hour_worked, $date, $employeeId]);
    }
    $status = "presence";
    $hour = date('Y-m-d H:i:s');
    $query = "INSERT INTO employee_attendance(employee_id, date, hour, hour_out, status, hour_worked) VALUES (?, ?, ?, ?, ?, ?)";
    $db = getDatabase();
    $statement = $db->prepare($query);
    return $statement->execute([$employeeId, $date, $hour, "-1", $status, "0"]);
}

function getEmployeeCentinela($employeeId, $date)
{
    $query = "SELECT * FROM employee_attendance  where employee_id = ? and date = ? and hour_out = -1";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeId, $date]);
    return $statement->fetchAll();
}

function getEmployeeAttendanceByIdAndDate($employeeId, $date)
{
    $query = "SELECT * FROM employee_attendance where employee_id = ? and date = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeId, $date]);
    return $statement->fetchAll();
}

function setReaderForEmployeePairing($employeeId)
{
    setReaderStatus(RFID_STATUS_PAIRING);
    setPairingEmployeeId($employeeId);
}

function setReaderForEmployeeUnPairing($employeeId)
{
    setReaderStatus(RFID_STATUS_DELETING);
    setPairingEmployeeId($employeeId);
}

function setPairingEmployeeId($employeeId)
{
    file_put_contents(PAIRING_EMPLOYEE_ID_FILE, $employeeId);
}

function getPairingEmployeeId()
{
    return file_get_contents(PAIRING_EMPLOYEE_ID_FILE);
}

function pairEmployeeWithRfid($rfidSerial, $employeeId)
{
    $query = "SELECT * FROM employee_rfid WHERE rfid_serial = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$rfidSerial]);
    if (empty($statement->fetchAll())) {
        $query = "INSERT INTO employee_rfid(employee_id, rfid_serial) VALUES (?, ?)";
        $db = getDatabase();
        $statement = $db->prepare($query);
        $statement->execute([$employeeId, $rfidSerial]);
        return true;
    } else {
        return false;
    }
}

function unpairEmployeeWithRfid($rfidSerial, $employeeId)
{
    $query = "SELECT rfid_serial FROM employee_rfid WHERE employee_id = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeId]);
    $employee = $statement->fetchAll()[0];
    if ($employee->rfid_serial === $rfidSerial) {
        removeRfidFromEmployee($rfidSerial);
        return true;
    }
    return false;
}

function removeRfidFromEmployee($rfidSerial)
{
    $query = "DELETE FROM employee_rfid WHERE rfid_serial = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    return $statement->execute([$rfidSerial]);
}

function getEmployeeByRfidSerial($rfidSerial)
{
    $query = "SELECT e.id, e.name FROM employees e INNER JOIN employee_rfid
    ON employee_rfid.employee_id = e.id
    WHERE employee_rfid.rfid_serial = ?";

    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$rfidSerial]);
    return $statement->fetchObject();
}

function getEmployeeNameByRfidSerial($rfidSerialN)
{
    $query = "SELECT name FROM employees JOIN employee_rfid 
    ON employees.id = employee_rfid.employee_id
    WHERE employee_rfid.rfid_serial = ?";

    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$rfidSerialN]);
    return $statement->fetchObject();
}

function getEmployeeRfidById($employeeId)
{
    $query = "SELECT rfid_serial FROM employee_rfid WHERE employee_id = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeId]);
    return $statement->fetchObject();
}

function getEmployeeNameById($employeeName)
{
    $query = "SELECT name FROM employees WHERE id = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeName]);
    return $statement->fetchObject();
}

function getEmployeeStatusByRfid($employeeStatus)
{
    $query = "SELECT status FROM employee_attendance JOIN employee_rfid 
    ON employee_attendance.employee_id = employee_rfid.employee_id 
    WHERE employee_rfid.rfid_serial = ? 
    ORDER BY `employee_attendance`.`date` DESC, `employee_attendance`.`hour` DESC 
    limit 1";

    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeStatus]);
    return $statement->fetchObject();
}

function getEmployeeStatusById($employeeId)
{
    $query = "SELECT status FROM employee_attendance 
    WHERE employee_id = ? 
    ORDER BY `employee_attendance`.`date` DESC, `employee_attendance`.`hour` DESC 
    limit 1";

    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeId]);
    return $statement->fetchObject();
}

function getEmployeeIdByRfid($employeeRfid)
{
    $query = "SELECT employee_id FROM employee_rfid WHERE rfid_serial = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$employeeRfid]);
    return $statement->fetchObject();
}


function getReaderStatus()
{
    return file_get_contents(RFID_STATUS_FILE);
}

function setReaderStatus($newStatus)
{
    if (!in_array($newStatus, [RFID_STATUS_PAIRING, RFID_STATUS_READING, RFID_STATUS_DELETING])) {
        return;
    }

    file_put_contents(RFID_STATUS_FILE, $newStatus);
}

function getEmployeesWithAttendanceCount($start, $end)
{
    $query = "select employees.name, 
sum(case when status = 'presence' then 1 else 0 end) as presence_count,
sum(case when status = 'absence' then 1 else 0 end) as absence_count 
 from employee_attendance
 inner join employees on employees.id = employee_attendance.employee_id
 where date >= ? and date <= ?
 group by employee_id, name;";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$start, $end]);
    return $statement->fetchAll();
}

function saveAttendanceData($date, $hour, $employees)
{
    deleteAttendanceDataByDate($date);
    $db = getDatabase();
    $db->beginTransaction();
    $statement = $db->prepare("INSERT INTO employee_attendance(employee_id, date, hour, status) VALUES (?, ?, ?, ?)");
    foreach ($employees as $employee) {
        $statement->execute([$employee->id, $date, $hour, $employee->status]);
    }
    $db->commit();
    return true;
}

function deleteAttendanceDataByDate($date)
{
    $db = getDatabase();
    $statement = $db->prepare("DELETE FROM employee_attendance WHERE date = ?");
    return $statement->execute([$date]);
}
function getAttendanceDataByDate($date)
{
    $db = getDatabase();
    $statement = $db->prepare("SELECT employee_id, status, hour, hour_out, hour_worked FROM employee_attendance WHERE date = ?");
    $statement->execute([$date]);
    return $statement->fetchAll();
}


function deleteEmployee($id)
{
    $db = getDatabase();
    $statement = $db->prepare("DELETE FROM employees WHERE id = ?");
    return $statement->execute([$id]);
}

function updateEmployee($name, $last_name, $phone, $email, $area, $id)
{
    $db = getDatabase();
    $statement = $db->prepare("UPDATE employees SET name = ?, last_name = ?, phone = ?, email = ?, area = ? WHERE id = ?");
    //$statement = $db->prepare("UPDATE employees SET name = ? WHERE id = ?");
    return $statement->execute([$name, $last_name, $phone, $email, $area, $id]);
}
function getEmployeeById($id)
{
    $db = getDatabase();
    $statement = $db->prepare("SELECT * FROM employees WHERE id = ?");
    $statement->execute([$id]);
    return $statement->fetchObject();
}

function saveEmployee($identificacion, $name, $last_name, $phone, $email, $area)
{
    $db = getDatabase();
    $statement = $db->prepare("INSERT INTO employees(identificacion, name, last_name, phone, email, area) VALUES (?, ?, ?, ?, ?, ?)");
    return $statement->execute([$identificacion, $name, $last_name, $phone, $email, $area]);
}

function getEmployees()
{
    $db = getDatabase();
    $statement = $db->query("SELECT id, identificacion, name, last_name, area FROM employees");
    return $statement->fetchAll();
}

function isEmployeeExists($cedula)
{
    $db = getDatabase();
    $statement = $db->prepare("SELECT * FROM employees WHERE identificacion = ?");
    $statement->execute([$cedula]);
    return !empty($statement->fetchAll());
}

function getUsuario()
{
    $db = getDatabase();
    $statement = $db->query("SELECT id, nombres FROM usuarios");
    return $statement->fetchAll();
}

function getDatosHistory($startf, $endf)
{
    $db = getDatabase();
    $statement = $db->prepare('SELECT e.id, e.identificacion, e.name, e.last_name, e.area, a.hour, a.hour_out, a.hour_worked, a.date FROM employees e inner join employee_attendance a on a.employee_id = e.id WHERE a.date BETWEEN ? AND ?');
    $statement->execute([$startf, $endf]);
    return $statement->fetchAll();
}

function getVarFromEnvironmentVariables($key)
{
    if (defined("_ENV_CACHE")) {
        $vars = _ENV_CACHE;
    } else {
        $file = "env.php";
        if (!file_exists($file)) {
            throw new Exception("The environment file ($file) does not exists. Please create it");
        }
        $vars = parse_ini_file($file);
        define("_ENV_CACHE", $vars);
    }
    if (isset($vars[$key])) {
        return $vars[$key];
    } else {
        throw new Exception("The specified key (" . $key . ") does not exist in the environment file");
    }
}

function getDatabase()
{
    date_default_timezone_set(TIMEZONE);
    $password = getVarFromEnvironmentVariables("MYSQL_PASSWORD");
    $user = getVarFromEnvironmentVariables("MYSQL_USER");
    $dbName = getVarFromEnvironmentVariables("MYSQL_DATABASE_NAME");
    $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}
