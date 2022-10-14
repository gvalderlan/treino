<?php

class DbData
{
  const OBJECT = 1;
  const ARRAY_ASSOC = 2;
  const ARRAY_NUM = 3;
  const ARRAY_BOTH = 4;
  #####Esta linha esta alterada
  $var = "teste 02 - gabriel";

  private $o_PDOStatment;

  /**
   * Retorna dados de uma consulta SQL
   *
   * @param int $f_tout - Usada para formatar a saída dos dados
   * @example $ob->getData(DbData::ARRAY_NUM)
   *   Use
   *   DbData::ARRAY_NUM - para retornar um array multidimensional de índices numéricos
   *   DbData::OBJECT - para retornar um array contendo como valores, objetos stdClass com os dados de cada linha
   *   DbData::ARRAY_ASSOC - para retornar um array multidimensional contendo como índices os nomes dos campos
   *   DbData::ARRAY_BOTH - para retornar um array contendo índices numericos e os nomes dos campos
   * @return array
   * @throws DbException
   */
  public function getData($f_tout = self::OBJECT)
  {
    if(!is_a($this->o_PDOStatment,'PDOStatement'))
      throw new DbException('Nothing to return');

    if($this->o_PDOStatment->columnCount() > 0)
    {
      $v_return = array();

      try
      {
        switch($f_tout)
        {
          case self::OBJECT:
            while($o_line = $this->o_PDOStatment->fetchObject())
              array_push($v_return, $o_line);
            break;

          case self::ARRAY_ASSOC:
            $v_return = $this->o_PDOStatment->fetchAll(PDO::FETCH_ASSOC);
            break;

          case self::ARRAY_NUM;
            $v_return = $this->o_PDOStatment->fetchAll(PDO::FETCH_NUM);
            break;

          case self::ARRAY_BOTH;
            $v_return = $this->o_PDOStatment->fetchAll(PDO::FETCH_BOTH);
            break;
        }
      }
      catch(PDOException $e)
      {
        throw  new DbException($e->getMessage());
      }
    }
    else
      $v_return = FALSE;
    return $v_return;
  }

  /**
   * Recebe um objeto da classe PDOStatment,
   * este objeto será usado apenas pela classe DbDatabase
   * @param PDOStatement $o_PDOStatment
   */
  public function setData(PDOStatement $o_PDOStatment)
  {
    $this->o_PDOStatment = $o_PDOStatment;
  }

  /**
   * Retorna o numero de linhas geradas na consulta SQL
   * @throws DbException
   * @return integer
   */
  public function getNRows()
  {
    if(!is_a($this->o_PDOStatment,'PDOStatement'))
      throw new DbException('Nothing to return');

    return $this->o_PDOStatment->rowCount();
  }

  /**
   * Retorma o numero de colunas geradas na consulta SQL
   * @return integer
   * @throws DbException
   */
  public function getNCols()
  {
    if(!is_a($this->o_PDOStatment,'PDOStatement'))
      throw new DbException('Nothing to return');

    return $this->o_PDOStatment->columnCount();
  }
}
