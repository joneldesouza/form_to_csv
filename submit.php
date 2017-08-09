<?php

$path = 'file.csv';
$lines = file($path);
$count_lines = count($lines);
$search = $_REQUEST['cpf'];
$cpf = false;

/*
 * Verifica quantidade de linhas adicionadas no CSV
 */
if ($_GET['limit'] == 1) {
    if ($count_lines >= 50) {
        print "limite_atingido";
        exit;
    } else {
        print "limite_nao_atingido";
    }
}

/*
 * Verifica se o CPF já existe no CSV
 */
if ($_GET['cpf']) {
    while (list($key, $line) = each($lines) and !$cpf) {
       $cpf = (strpos($line, $search) !== FALSE);
    }
    if ($cpf){
       print "cpf_exists";
       exit;
    } else {
       print "cpf_not_exist";
    }
}

/*
 * Salva os dados do formulário no CSV
 */
if ($_POST['valid'] == 1) {
    function mynl2br($text){
        return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
    }
    if ($_POST) {
        $string = "";
        $i = 1;
        $cont = count($_POST);
        foreach ($_POST as $campo => $valor) {
            $valor = str_replace("'", "\"", $valor);
            $valor = str_replace(";", ",", $valor);
            $valor = mynl2br($valor);
            $valor = strip_tags($valor);
            if ($i < $cont) {
                $string .= "".$valor.";";
                $i++;
            } else {
                $string .= "".$valor."";
            }
        }
        $fp = fopen($path, 'a');
        fputcsv($fp, explode(";", $string), ";");
        fclose($fp);
        $aPag = explode("?", $_SERVER['HTTP_REFERER']);
        $pagina = $aPag[0];
        print "success";
    } else {
        print "error";
    }
}
