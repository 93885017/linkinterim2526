<?php
class Member
{
    private $accessToken;
    private $userID;

    public function login($conn, $username, $email)
    {
        $member = $this->checkLogin($conn, $username, $email);
        if ($member) {
            // update last login time add access_token
            $accessToken = md5(uniqid(rand(), true));
            // update table last login time and access_token
            // query without sql injection
            $sql = "UPDATE member SET last_login_at = NOW(), access_token = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $accessToken, $member['id']);
            mysqli_stmt_execute($stmt);
            // redirect to the home page
            $this->setAccessToken($accessToken);
            $this->setUserID($member['id']);
            return true;
        }
        return false;
    }

    public function checkLogin($conn, $username, $email)
    {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM member WHERE BINARY username = '$username' AND password = MD5('$email') AND status = 'active'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        // var_dump($row);exit;
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }

    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    public function getUserID()
    {
        return $this->userID;
    }


    public function logout()
    {
        include_once 'config/config.php';
        return '<script>
            setCookie("' . SITE_SESSION_KEY . 'username", "");
            setCookie("' . SITE_SESSION_KEY . 'token", "");
            </script>';
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function checkIsValidAccessToken($conn, $inToken, $inUserName)
    {
        $sql = "SELECT id FROM member WHERE access_token = ? AND username = ? AND status = 'active'";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $inToken, $inUserName);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $numRows = mysqli_stmt_num_rows($stmt);

        if ($numRows > 0) {
            return true;
        }

        return false;
    }
}
