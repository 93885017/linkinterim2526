<?php
class HelperClass
{
    private $conn;
    private $currentSection = "";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkCurrentSection($sectionName, $setionValue = '')
    {
        $sql = "SELECT meta_value FROM site_config WHERE meta_key = 'enable_section'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_array($result);
        $this->currentSection = $row['meta_value'];
        $redirectTo = '';
        if ($sectionName != 'any' && $row) {
            if (
                $row['meta_value'] == 'video_page_1' &&
                $row['meta_value'] == 'video_page_2' &&
                $setionValue == 'login'
            ) {
                $redirectTo = '';
            } elseif ($row['meta_value'] !== $sectionName) {

                switch ($row['meta_value']) {
                    case 'contingency_page':
                        $redirectTo = 'contingency_page.php';
                        break;
                    case "ready_page_1":
                    case "ready_page_2":
                    case "video_page_1":
                    case "video_page_2":
                        $redirectTo = "home.php";
                        break;
                    case "palyback_page":
                        $redirectTo = "playback.php";
                        break;
                    case "finish_page_1":
                    case "finish_page_2":
                        $redirectTo = "finish.php";
                        break;
                    default:
                        $redirectTo = "index.php";
                        break;
                }

                if ($redirectTo == 'finish.php' && $sectionName == 'finish_page') {
                    $redirectTo = ($row['meta_value'] == 'finish_page_1' || $row['meta_value'] == 'finish_page_2') ? '' : "finish.php";
                }
            } else {
                $redirectTo = '';
            }
        }
        if ($redirectTo != '') {
            $currTime = time();
            echo '<script>
                window.location = "' . $redirectTo . '?t=' . $currTime . '";
                </script>';
            exit;
        }

    }

    public function getRedirectPage($section)
    {
        switch ($section) {
            case 'contingency_page':
                return 'contingency_page.php';
            case "ready_page_1":
            case "ready_page_2":
            case "video_page_1":
            case "video_page_2":
                return "index.php";
            case "palyback_page":
                return "playback.php";
            case "finish_page_1":
            case "finish_page_2":
                return "finish.php";
            default:
                return "index.php";
        }
    }

    public function updateSiteConfig($key, $value)
    {
        try {
            $sql = "UPDATE site_config SET meta_value = ?, updated_at = NOW() WHERE meta_key = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmt, 'ss', $value, $key);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            return ['result' => false, 'message' => 'Failed to update the section. Please contact the system admin. (' . $e->getMessage() . ')'];
        }

        $sql = "UPDATE site_config SET meta_value = ?, updated_at = NOW() WHERE meta_key = ?";
        $updateVersionStm = mysqli_prepare($this->conn, $sql);
        $currTimestamp = time();
        $versionKey = "update_version";
        mysqli_stmt_bind_param($updateVersionStm, 'ss', $currTimestamp, $versionKey);
        mysqli_stmt_execute($updateVersionStm);

        return ['result' => true, 'message' => 'Updated successfully'];
    }

    public function getSiteConfig($metaKey)
    {
        $sql = "SELECT meta_value FROM site_config WHERE meta_key = '$metaKey'";
        $result = mysqli_query($this->conn, $sql);

        $row = mysqli_fetch_array($result);
        if ($row) {
            return $row['meta_value'];
        } else {
            return false;
        }
    }

    public function checkCampaignStart($testMode)
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $now = time();

        if ($testMode) {
            $target = mktime(3, 12, 0, 7, 9, 2023);
        } else {
            $startTime = $this->getSiteConfig('start_time');
            $target = strtotime($startTime);
        }

        // if ($target > $now) {
        //     echo 'The compare time is in the future.';
        // } elseif ($target < $now) {
        //     echo 'The compare time is in the past.';
        // } else {
        //     echo 'The compare time is the same as the current time.';
        // }

        return ($now > $target);
    }

    public function checkCampaignEnd($testMode)
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $now = time();

        if ($testMode) {
            $target = mktime(3, 12, 0, 7, 20, 2023);
        } else {
            $endTime = $this->getSiteConfig('end_time');
            $target = strtotime($endTime);
        }
        return ($now > $target);
    }

    public function getCurrentSection()
    {
        return $this->currentSection;
    }

    public function getDisplayVideo($no, $selected)
    {
        $value = '';
        switch ($selected) {
            case '2':
                $constantName = "VIDEO_{$no}_URL_ENGLISH";
                break;
            case '3':
                $constantName = "VIDEO_{$no}_URL_CANTONESE";
                break;
            case '4':
                $constantName = "VIDEO_{$no}_URL_PUTONGHUA";
                break;
            default:
                // 1 as default
                $constantName = "VIDEO_{$no}_URL_FLOOR";
                break;
        }
        return constant($constantName);
    }

    public function getUserIP()
    {
        // Get the user's IP address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check for shared internet/ISP IP
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check for IPs passing through proxies
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // Use the remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Creates or updates a session variable with a time limit.
     *
     * @param string $key The session key.
     * @param mixed $value The value to store in the session.
     * @param int $lifetime The lifetime of the session in seconds.
     */
    public function createSessionWithLifetime($key, $value, $lifetime = 300)
    {
        session_start();

        // Store the value and expiration time in the session
        $_SESSION[$key] = [
            'value' => $value,
            'expires_at' => time() + $lifetime
        ];
    }

    /**
     * Retrieves a session variable if it hasn't expired.
     *
     * @param string $key The session key.
     * @return mixed|null The session value, or null if expired or not set.
     */
    public function getSessionWithLifetime($key)
    {
        session_start();

        if (isset($_SESSION[$key])) {
            $sessionData = $_SESSION[$key];
            // Check if the session has expired
            if (time() < $sessionData['expires_at']) {
                return $sessionData['value'];
            } else {
                // Remove the expired session
                unset($_SESSION[$key]);
            }
        }

        return null; // Return null if the session doesn't exist or has expired
    }

}
