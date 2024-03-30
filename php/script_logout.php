<?php
session_start(); //iniciando sessão
session_destroy(); //encerrar as sessões abertas

// redimensionando user para a tela inicial
header("Location: ../index.php");
