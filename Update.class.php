<?php
class Update extends Conn {
	private $Tabela;
	private $Dados;
	private $Termos;
	private $Places;
	private $Result;
	
	/** @var PDOStatement */
	private $Update;
	
	/** @var PDO */
	private $Conn;
	
	/**
	 * Executa uma atualização simplificada com Prepared Statments. 
	 * Basta informar o nome da tabela, os dados a serem atualizados em um Array Atribuitivo,
	 * as condições e uma analize em cadeia (ParseString) para executar.
	 * STRING $Tabela = Nome da tabela
	 * ARRAY $Dados = [ NomeDaColuna ] => Valor ( Atribuição )
	 * STRING $Termos = WHERE coluna = :link AND.. OR..
	 * STRING $ParseString = link={$link}&link2={$link2}
	 */
	public function ExeUpdate($Tabela, array $Dados, $Termos, $ParseString) {
		$this->Tabela = (string) $Tabela;
		$this->Dados = $Dados;
		$this->Termos = (string) $Termos;
		parse_str($ParseString, $this->Places);
		$this->getSyntax();
		$this->Execute();
	}
	
	/**
	 * Retorna TRUE se não ocorrer erros, ou FALSE. Mesmo não alterando os dados se uma query
	 * for executada com sucesso o retorno será TRUE. Para verificar alterações execute o getRowCount();
	 * retorna BOOL True ou False
	 */
	public function getResult() {
		return $this->Result;
	}
	
	/**
	 * Contar Registros: Retorna o número de linhas alteradas no banco!
	 * retorna INT Quantidade de linhas alteradas
	 */
	public function getRowCount() {
		return $this->Update->rowCount();
	}
	
	/**
	 * Método pode ser usado para atualizar com Stored Procedures. 
	 * Modificando apenas os valores da condição.
	 * Usar este método para editar múltiplas linhas!
	 * STRING $ParseString = id={$id}&..
	 */
	public function setPlaces($ParseString) {
		parse_str($ParseString, $this->Places);
		$this->getSyntax();
		$this->Execute();
	}
	
	// Obtém o PDO e Prepara a query
	private function Connect() {
		$this->Conn = parent::getConn();
		$this->Update = $this->Conn->prepare($this->Update);
	}
	// Cria a sintaxe da query para Prepared Statements
	private function getSyntax() {
		foreach ($this->Dados as $Key => $Value):
		    $Places[] = $Key . ' = :' . $Key;
		endforeach;
		$Places = implode(', ', $Places);
		$this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";
	}
	//Obtém a Conexão e a Syntax, executa a query!
	private function Execute() {
		$this->Connect();
		try {
			$this->Update->execute(array_merge($this->Dados, $this->Places));
			$this->Result = true;
		} catch (PDOException $e) {
			$this->Result = null;
			ADErro("<b>Erro ao Ler:</b> {$e->getMessage()}", $e->getCode());
		}
	}
}