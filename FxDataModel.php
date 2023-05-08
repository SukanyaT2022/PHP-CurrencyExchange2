<?php
define("FX_CALC_INI_FILE","fxCalc.ini");

class FxDataModel
{
  const FX_DATA_MODEL_KEY = 'fx.data.model';
  const FX_RATE_FILE_KEY = 'fx.rates.file'; 
  const DST_AMT_KEY = 'dst.amt'; 
  const DST_CUCY_KEY = 'dst.cucy';
  const SRC_AMT_KEY ='src.amt';
  const SRC_CUCY_KEY = 'src.cucy';
  const DATA_PINDX_KEY      = 'data.pindx'     ;
  const DATA_SINDX_KEY      = 'data.sindx'     ;
  const DATA_DINDX_KEY      = 'data.dindx'     ;
  const DB_PASSWORD_KEY     = 'db.password'    ;
  const DB_USERNAME_KEY     = 'db.username'    ;
  const DSN_KEY             = 'dsn'            ;
  const FX_RATES_PS_KEY   = 'fx.rates.ps'  ;


private $fxCurrencies;//=new array();

private $fxRates ;
//midterm

private $ini_arr;



public function __construct()
{


$this->ini_arr = parse_ini_file(FX_CALC_INI_FILE);
$dbh = new PDO( 
  $this->ini_arr[ self::DSN_KEY ]        ,
  $this->ini_arr[ self::DB_USERNAME_KEY ],
  isset( $this->ini_arr[ self::DB_PASSWORD_KEY ] ) ? $this->ini_arr[ self::DB_PASSWORD_KEY ] : NULL
);

$statement = $dbh->prepare( $this->ini_arr[ self::FX_RATES_PS_KEY ] );

$statement->execute();

while( ( $rec  = $statement->fetch() ) != null )
{        
  $key=$rec[ $this->ini_arr[ self::DATA_SINDX_KEY ] ]."-".$rec[ $this->ini_arr[ self::DATA_DINDX_KEY ] ];
$this->fxRates[$key] = $rec[ $this->ini_arr[ self::DATA_PINDX_KEY ] ];     
$currency=  $rec[ $this->ini_arr[ self::DATA_SINDX_KEY ] ];

$this->fxCurrencies[$currency] = $currency;  
}

$statement->closeCursor();



$dbh = NULL;
}  

public  function getFxCurrencies(){
  return $this->fxCurrencies;

}
public  function getIniArray(){
  return $this->ini_arr;
}

  public  function getFxRate( $source, $des )//return conversion rate--source which row--des --which column
  {
$position1 = 0;
$position2 = 0;
$k=$source."-".$des;

return $this->fxRates[$k];



  }

}  


?>
