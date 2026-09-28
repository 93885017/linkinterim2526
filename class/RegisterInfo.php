<?php
class RegisterInfo
{
    protected $tableName = "register_info";

    public function saveData($conn, $firstname, $lastname, $comapnyname, $device)
    {
        $sql = "INSERT INTO " . $this->tableName . " (first_name, last_name, company_name, ip, device) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        $ip = $_SERVER['REMOTE_ADDR'];
        mysqli_stmt_bind_param($stmt, 'sssss', $firstname, $lastname, $comapnyname, $ip, $device);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    public function getLogData($conn)
    {
        $sql = "SELECT id, first_name, last_name, company_name, created_at, ip, device FROM " . $this->tableName;
        $result = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_array($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function exportCSV($conn)
    {
        $rows = $this->getLogData($conn);

        $filename = 'register_info_' . time() . '.csv';

        $csv_data = '';

        // add heders
        $header = array('id', 'first name', 'last name', 'company name', 'time', 'ip', 'device');

        $csv_data .= implode(',', $header) . "\n";

        //add rows
        foreach ($rows as $row) {
            // $csv_data .= implode(',', $row) . "\n";
            $csv_data .= '"' . $row['id'] . '",';
            $csv_data .= '"' . $row['first_name'] . '",';
            $csv_data .= '"' . $row["last_name"] . '",';
            $csv_data .= '"' . $row["company_name"] . '",';
            $csv_data .= '"' . $row["created_at"] . '",';
            $csv_data .= '"' . $row["ip"] . '",';
            $csv_data .= '"' . $row["device"] . '"';
            $csv_data .= "\n";
        }

        if (isset($_GET['debug']) && $_GET['debug'] === 'y') {
            echo $csv_data;
            exit;
        }
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        echo $csv_data;
    }

}