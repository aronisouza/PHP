<?php
// CONFIGRAÇÕES DO BANCO
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBSA', 'banco');

// AUTO LOAD DE CLASSES
function __autoload($Class){
	$cDir = 'Class';
    $iDir = null;

    if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $Class . '.class.php') && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $Class . '.class.php')):
        include_once (__DIR__ . DIRECTORY_SEPARATOR. $Class . '.class.php');
        $iDir = true;
    endif;

	if (!$iDir):
	    trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
	    die;
	endif;
}

// TRATAMENTO DE ERROS # CSS constantes
define('AD_SUSSESS', 'alert-success');
define('AD_INFO', 'alert-info');
define('AD_ALERT', 'alert-warning');
define('AD_ERROR', 'alert-danger');

define('SITE', 'SGC-AroniSouza');

// Apenas para verificar alguns arrays :: Uso administativo
function getPreA(array $string){
    echo '<pre>';
        print_r($string);
    echo '</pre>';
}

function getPre($string){
    echo '<pre>';
		print_r($string);
    echo '</pre>';
}

// ADErro :: Exibe erros lançados
function ADErro($ErrMsg, $ErrNo, $ErrDie = null){
	$CssClass = ($ErrNo == E_USER_NOTICE ? AD_INFOR : ($ErrNo == E_USER_WARNING ? AD_ALERT : ($ErrNo == E_USER_ERROR ? AD_ERROR : $ErrNo)));
	echo "<div class=\"alert {$CssClass} alert-dismissable\">
	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">x</button>
	{$ErrMsg}
	</div>";
	if ($ErrDie):
	    die;
	endif;
}


// PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine){
	$CssClass = ($ErrNo == E_USER_NOTICE ? AD_INFOR : ($ErrNo == E_USER_WARNING ? AD_ALERT : ($ErrNo == E_USER_ERROR ? AD_ERROR : $ErrNo)));
    echo "<div class=\"alert {$CssClass} alert-dismissable\">";
	echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">x</button>";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
	echo "<small>{$ErrFile}</small>";
    echo "</div>";
    if ($ErrNo == E_USER_ERROR):
	    die;
	endif;
}
set_error_handler('PHPErro');