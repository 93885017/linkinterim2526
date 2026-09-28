<?php
class LoginLog
{
    public function LogData($conn, $username, $device)
    {
        $sql = "INSERT INTO login_log (login, ip, device) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $_SERVER['REMOTE_ADDR'], $device);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    public function getLogData($conn) {
        $sql = "SELECT id, login, CONVERT_TZ(timestamp, '+00:00', '+08:00') AS timestamp, ip, device FROM login_log";
        $result = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_array($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function exportCSV($conn) {
        $rows = $this->getLogData($conn);
        
        $filename = 'login_log_' . time() . '.csv';
        
        $csv_data = '';

        // add heders
        $header = array('id', 'login', 'time', 'ip', 'device');
        
        $csv_data .= implode(',', $header) . "\n";

        //add rows
        foreach ($rows as $row) {
            // $csv_data .= implode(',', $row) . "\n";
            $csv_data .= $row['id'].",";
            $csv_data .= $row['login'].",";
            $csv_data .= $row['timestamp'].",";
            $csv_data .= $row['ip'].",";
            $csv_data .= $row['device'];
            $csv_data .= "\n";
        }
        
        if (isset($_GET['debug']) && $_GET['debug'] === 'y') {
            echo $csv_data;
            exit;
        }
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        
        echo $csv_data;
    }

}