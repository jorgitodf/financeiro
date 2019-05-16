<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Manutencao extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function export()
    {
        $dados["title"] = "Cadastro de Cartão de Crédito";
        $dados["view"] = "manutencao/v_index";

        $db = "financeiro";
        $connection = mysqli_connect('localhost','admin','123456',$db);
        
        $tables = array();
        
        $result = mysqli_query($connection, "SHOW TABLES");
        
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        
        $return = '';
        
        foreach ($tables as $table) {
          $result = mysqli_query($connection,"SELECT * FROM ". $table);
          $num_fields = mysqli_num_fields($result);
          
          $return .= 'DROP TABLE '.$table.';';
          $row2 = mysqli_fetch_row(mysqli_query($connection,"SHOW CREATE TABLE ".$table));
          $return .= "\n\n".$row2[1].";\n\n";
          
          for ($i=0; $i < $num_fields; $i++) {
            while($row = mysqli_fetch_row($result)) {
              $return .= "INSERT INTO ".$table." VALUES(";
        
              for($j=0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                if( isset($row[$j])){ $return .= '"'.$row[$j].'"';}
                else{ $return .= '""';}
                if($j<$num_fields-1){ $return .= ',';}
              }
        
              $return .= ");\n";
            }
          }
          $return .= "\n\n\n";
        }
        
        //save file
        $date = date('dmY');
        $handle = fopen("C:/Users/jorgito.paiva/Dropbox/backup_{$db}_{$date}.sql","w+");
        fwrite($handle,$return);
        fclose($handle);
        mysqli_close($connection);

        $dados["msg_return"] = "Base de Dados Exportada com Sucesso!";
        $this->load->view("v_template", $dados);
    }


}