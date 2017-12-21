<?php
class Read extends Conn {
	private $Select;
	private $Places;
	private $Result;
	
	//PDOStatement
	private $Read;
	
	// PDO
	private $Conn;
	
	/**
	 * Executa uma leitura simplificada com Prepared Statments. 
	 * Basta informar o nome da tabela, os termos da seleção e uma analize em cadeia (ParseString) para executar.
	 *
	 * STRING $Tabela = Nome da tabela
	 * STRING $Termos = WHERE | ORDER | LIMIT :limit | OFFSET :offset
	 * STRING $ParseString = link={$link}&link2={$link2}
	 */
	public function ExeRead($Tabela, $Termos = null, $ParseString = null) {
		if (!empty($ParseString)):
		parse_str($ParseString, $this->Places);
		endif;
		$this->Select = "SELECT * FROM {$Tabela} {$Termos}";
		$this->Execute();
	}
	
	/**
	 * Retorna um array com todos os resultados obtidos. Envelope primário númérico.
	 * Para obter um resultado chame o índice getResult()[0]!
	 * retorna ARRAY $this = Array ResultSet
	 */
	public function getResult() {
		return $this->Result;
	}
	
	/**
	 * Contar Registros: Retorna o número de registros encontrados pelo select!
	 * retorna INT $Var = Quantidade de registros encontrados
	 */
	public function getRowCount() {
		return $this->Read->rowCount();
	}
	
	public function FullRead($Query, $ParseString = null) {
		$this->Select = (string) $Query;
		if (!empty($ParseString)):
		parse_str($ParseString, $this->Places);
		endif;
		$this->Execute();
	}
	/**
	 * Executa leitura de dados via query que deve ser montada manualmente para possibilitar
	 * seleção de multiplas tabelas em uma única query!
	 *
	 * STRING $Query = Query Select Syntax
	 * STRING $ParseString = link={$link}&link2={$link2}
	 */
	public function setPlaces($ParseString) {
		parse_str($ParseString, $this->Places);
		$this->Execute();
	}
	
	// Obtém o PDO e Prepara a query
	private function Connect() {
		$this->Conn = parent::getConn();
		$this->Read = $this->Conn->prepare($this->Select);
		$this->Read->setFetchMode(PDO::FETCH_ASSOC);
	}
	
	// Cria a sintaxe da query para Prepared Statements
	private function getSyntax() {
		if ($this->Places):
		foreach ($this->Places as $Vinculo => $Valor):
		if ($Vinculo == 'limit' || $Vinculo == 'offset'):
		$Valor = (int) $Valor;
		endif;
		$this->Read->bindValue(":{$Vinculo}", $Valor, ( is_int($Valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
		endforeach;
		endif;
	}
	
	// Obtém a Conexão e a Syntax, executa a query!
	private function Execute() {
		$this->Connect();
		try {
			$this->getSyntax();
			$this->Read->execute();
			$this->Result = $this->Read->fetchAll();
		} catch (PDOException $e) {
			$this->Result = null;
			ADErro("<b>Erro ao Ler:</b> {$e->getMessage()}", $e->getCode());
		}
	}
}