<?php
namespace SAE_401\Services\ViaPdo;

include_once __DIR__ . '/config.php';

class Bdd {
    public  $connect = null;
    public function __construct(){
        try{
            $this->connect=new \PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8',
                                            BDD_HOST, BDD_BDNAME), 			   
                                    BDD_USER, BDD_PASS,
                                    array(
                                        \PDO::ATTR_PERSISTENT => true,
                                    )
            );
        }catch (\PDOException $e) {
            $msg="Error PDO!: " . $e->getMessage() ;
            if (ini_get('display_error')){
                print($msg. "<br/>");
            }
            error_log($msg);
            die();    
        }
    }
    protected function log($msg){
        error_log($msg);

    }
    public function estConnectee():bool{
        return $this->connect!==null;
    }
    
    public function executer(\PDOStatement $stmt, int $line=0, string $file=__FILE__):bool{
        $ex=$stmt->execute();
        if ($ex===FALSE){

            ob_start();
            $stmt->debugDumpParams();
            $stmtdgstr=ob_get_contents();
            ob_end_clean();

            $errorInfos=$stmt->errorInfo();
            $this->log(sprintf("execute failed %s -  %s - %s - %s : %s (%s:%d)", 
                            $stmt->errorCode(), $errorInfos[0], $errorInfos[1], $errorInfos[2],
                            $stmtdgstr,
                            $file, $line));
        }
        return ($ex===FALSE?FALSE:TRUE); //PHP bool sucks !
    }
}
?>