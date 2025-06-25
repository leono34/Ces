<?php
$input = "123456";
$hashDesdeBD = '123456';

if (password_verify($input, $hashDesdeBD)) {
    echo "Coincide";
} else {
    echo "No coincide";
}
?>
