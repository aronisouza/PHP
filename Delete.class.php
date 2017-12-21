<?php
class Delete extends Conn {
	private $Tabela;
	private $Termos;
	private $Places;
	private $Result;
	
	// PDOStatement
	private $Delete;
	
	// PDO
	private $Conn;
	
	public function ExeDelete($Tabela, $Termos, $ParseString) {
		$this->Tabela = (string) $Tabela;
		$this->Termos = (string) $Termos;
		parse_str($ParseString, $this->Places);
		$this->getSyntax();
		$this->Execute();
	}
	
	// Retorna os resultados
	public function getResult() {
		return $this->Result;
	}
	
	// Retorna a quantidade de linhas
	public function getRowCount() {
		return $this->Delete->rowCount();
	}
	
	public function setPlaces($ParseString) {
		parse_str($ParseString, $this->Places);
		$this->getSyntax();
		$this->Execute();
	}
	
	// Obtém o PDO e Prepara a query
	private function Connect() {
		$this->Conn = parent::getConn();
		$this->Delete = $this->Conn->prepare($this->Delete);
	}
	
	// Cria a sintaxe da query para Prepared Statements
	private function getSyntax() {
		$this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
	}
	
	// Obtém a Conexão e a Syntax, executa a query!
	private function Execute() {
		$this->Connect();
		try {
			$this->Delete->execute($this->Places);
			$this->Result = true;
		} catch (PDOException $e) {
			$this->Result = null;
			ADErro("<b>Erro ao Deletar:</b> {$e->getMessage()}", $e->getCode());
		}
	}
}