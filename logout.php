<?php
session_start();
session_destroy(); // ENCERRA A SESSÃO
header('Location: login.php');
exit;