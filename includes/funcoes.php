<?php

function test_input($data) {
    $data = trim($data);
    $data = ltrim($data, "0");
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>