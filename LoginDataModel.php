<?php

define( 'FX_USERS_INI_FILE', 'fxUsers.ini' );
define( 'LOGIN_INI_FILE'     , 'login.ini'   );

class LoginDataModel 
{
  const PASSWORD_KEY = 'password';
  const USERNAME_KEY = 'username';

  const DB_PASSWORD_KEY = 'db.password';
  const DB_USERNAME_KEY = 'db.username' ;
  const DSN_KEY         = 'dsn' ;
  const LOGIN_PS_KEY    = 'login.ps';

  private $dbh;
  private $statement; 
  
  private $iniArray;  
  private $loginArray;  
    
  public function __construct() 
  {
    $this->iniArray   = parse_ini_file( LOGIN_INI_FILE )   ;
    // $this->loginArray = parse_ini_file(FX_USERS_INI_FILE );

    $this->dbh = new PDO( 
      $this->iniArray[ self::DSN_KEY ]        ,
      $this->iniArray[ self::DB_USERNAME_KEY ],
      isset( $this->iniArray[ self::DB_PASSWORD_KEY ] ) ? $this->iniArray[ self::DB_PASSWORD_KEY ] : NULL
    );

    $this->statement = $this->dbh->prepare( $this->iniArray[ self::LOGIN_PS_KEY ] );

  }

  public function __destruct() 
  {
    $this->dbh = NULL;
  }
  

  public function getIniArray()
  {
    return $this->iniArray;
  }
  
  public function validateUser( $username, $password ) 
  {
   	// return array_key_exists( $username, $this->loginArray ) && ( $this->loginArray[ $username ] == $password );

     $this->statement->execute( array( $username, $password ) );
    
     if( $this->statement->fetchColumn() == 1 )
     {
       $status = TRUE;
     }
     else
     {
       $status = FALSE;
     }      
     
     $this->statement->closeCursor();
     
     return $status;


  }
}

?>
