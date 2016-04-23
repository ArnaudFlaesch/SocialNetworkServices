<?php
 
class QueryPDO
{
  /**
   * Instance de la classe PDO
   */ 
  private static $PDOInstance = null;
 
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
  const DEFAULT_SQL_PASS = 'root';
 
  /**
   * Constante: nom de la bdd
   */
  const DEFAULT_SQL_DTB = 'socialnetwork';
 
  /**
   * Constructeur
   */
  private function __construct()
  {
    self::$PDOInstance = new PDO('mysql:dbname='.self::DEFAULT_SQL_DTB.';host='.self::DEFAULT_SQL_HOST,self::DEFAULT_SQL_USER ,self::DEFAULT_SQL_PASS);
    self::$PDOInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    self::$PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
   * Retourne la connexion à la base de données
   * @return type
   */
  public static function getPDOInstance()
  {  
    return self::$PDOInstance;
  }
 
  /**
   * Exécute une requête SQL avec PDO
   */

    public function query($query, $tabParams) {
    
        $requete = self::$PDOInstance->prepare($query);
        try {
            if($requete && $requete->execute($tabParams)) {
                if ($requete->rowCount()==0) {
                    return null;
                }
                return $requete;
            }
            else {
                return null;
            }
        }
        catch(Exception $e){
            return $e;  
        }
    }

    public function getIdByToken($token){
        $requete= self::$PDOInstance->query("SELECT `iduser` FROM user where user_token='".$token."'");
        $data = $requete->fetch();
        return $data["iduser"];
    }

    public function ServiceReturnJson($code,$msg){
        $Error[] =["code"=>$code,"msg"=>$msg];
        print_r(json_encode($Error));
        return json_encode($Error);
    }
}