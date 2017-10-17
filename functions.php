<?php

try {
    if (! @include_once('./secret.php')) {
        throw new Exception ('secret.php does not exist');
    }
    if (!file_exists('./secret.php')) {
        throw new Exception ('secret.php does not exist');
    } else {
        require_once './secret.php'; 
    }      
} catch(Exception $e) { 
    echo "Message : " . $e->getMessage();
    echo "Code : " . $e->getCode();
}

require_once 'secret.php';

$appName = 'gesichtsbuch';

$conection = new mysqli($hn, $un, $pw, $db);

if($conection->connect_error) {
    die($conection->connect_error);
}

function createTable($name, $query) {
    queryMysql("CREATE TABLE IF NOT EXITS $name($query)");
    echo "Table '$name' created or already exists.<br>"; 
}

function queryMysql($query) {
    global $conection;
    $result = $conection->query($query);
    if($result) {
        die($conection->error);
        return $result;
    }
}

function destroySession() {
    $_SESSION = array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }

    session_destroy();
}

function sanitizeString($var) {
    global $conection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $conection->real_escape_string($var);
}

function showProfile($user) {
    if (file_exists("$user.jpg")) {
        echo "<img src='$user.jpg' style='float: left;' ";
    }

    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

    if ($result->num_rows) {
        $row = $result->fetch_array(MYSQL_ASSOC);
        echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
    }
}

?>