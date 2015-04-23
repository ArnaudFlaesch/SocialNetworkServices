<?php
 
class QueryPDO
{
  /**
   * Instance de la classe PDO
   */ 
  private $PDOInstance = null;
 
   /**
   * Instance de la classe QueryPDO
   */ 
  private static $instance = null;
 
  /**
   * Constante: nom d'utilisateur de la bdd
   */
  const DEFAULT_SQL_USER = 'root';
 
  /**
   * Constante: hôte de la bdd
   */
  const DEFAULT_SQL_HOST = 'localhost';
 
  /**
   * Constante: hôte de la bdd
   */
  const DEFAULT_SQL_PASS = '';
 
  /**
   * Constante: nom de la bdd
   */
  const DEFAULT_SQL_DTB = 'socialnetwork';
 
  /**
   * Constructeur
   */
  private function __construct()
  {
    $this->PDOInstance = new PDO('mysql:dbname='.self::DEFAULT_SQL_DTB.';host='.self::DEFAULT_SQL_HOST,self::DEFAULT_SQL_USER ,self::DEFAULT_SQL_PASS);
    $this->PDOInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $this->PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
 
   /**
    * Crée et retourne l'objet QueryPDO
    */
  public static function getInstance()
  {  
    if(is_null(self::$instance))
    {
      self::$instance = new QueryPDO();
    }
    return self::$instance;
  }
 
  /**
   * Exécute une requête SQL avec PDO
   */

  public function query($query)
  {
    $requete = $this->PDOInstance->prepare($query);
    try{
        if($requete && $requete->execute()){
          if($requete->rowCount()==0)
            return null;
         return $requete;
        }
        else{
          return null;
        }
      }
   catch(Exception $e){
    return null;  
      }
  }

  public function getIdByToken($token){
      $requete= $this->PDOInstance->query("SELECT `iduser` FROM user where user_token='".$token."'");
      $data = $requete->fetch();
      return $data["iduser"];
  }

  public function ServiceReturnJson($code,$msg){
    $Error[] =["code"=>$code,"msg"=>$msg];
    print_r(json_encode($Error));
    return json_encode($Error);;
  }
}