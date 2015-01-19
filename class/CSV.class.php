<?php 

  class CSV{
       private $filename;
	   public $data;
	   public $numericData;
	   public $header;
       public $numericHeader;
	   private $row;
	   
	   function __construct($filename){
	     $this->filename = $filename;
		 $this->data = array();
		 $this->data = array();
		 $this->numericData = array();
		 $this->row = true;
		 $this->header = array();
		 $this->numericHeader = array();
	   }
	   
	   public function read(){
	        if(!empty($this->filename)){
		      try{
			 if(file_exists($this->filename)){
                  $open = fopen($this->filename,"r");
                		while(!feof($open)){

                            if($this->row ==  true){
						 $this->header[] = fgetcsv($open);
                                $this->numericHeader = $this->header;
						   $this->row =  false;
						 }
						 else{
						  $this->numericData[] = fgetcsv($open);
						  $this->data = $this->numericData;
                        }

					} $this->arrayManipulate();
						
					return true;
			 }else{
			   throw new Exception("File not exists!");
			 }
			 }
			 catch(IOException $e){
			     throw new Exception($e->getMessage());
			 } 
			 catch(Exception $e){
			     throw new Exception($e->getMessage());
			 } 
				}else{
		    throw new Exception("FileName mustn't be empty!");
		  }
	   return false;
	   }
	   
	   private function arrayManipulate(){
	   $new = array();
           $c = 0;

           for($i = 0; $i < sizeof($this->data);$i++){
                for($x = 0; $x < sizeof($this->data[0]);$x++){
                   $new[$i][$this->header[0][$x]] = $this->data[$i][$x];
                }
            }

            $this->data = $new;
		 $e = array();
		 foreach($this->header as $x ){
		   foreach($x as $a){
		     $e[$a] = $a;
		   }
		 }
		 $this->header = $e;
		 //echo $this->data[0][1];

	   }

	  public function getData($i){
	     return $this->data[$i];
	  }



      public static function join($csv1,$csv2,$compare){
          //PolyMorphism
          $csv1->read();
          $csv2->read();

          $tableOneHeader = $csv1->header;
          $tableOneData = $csv1->data;

          $tableTwoHeader = $csv2->header;
          $tableTwoData = $csv2->data;

          $newTableHeader = array_merge($tableOneHeader,$tableTwoHeader);
          $newTableData = $tableOneData;
          $rowCounter = 0;

           foreach($newTableData as $x){
              for($int = 0; $int  <= sizeof($csv2->numericData); $int++){
                  if( $csv2->numericHeader[0][$int] != $compare){
                      $newTableData[$rowCounter][$csv2->numericHeader[0][$int]] = "null";
                  }
              }
              $rowCounter++;
          }
//print_r($newTableData);
//As our data header are static
          $rowCounter = 0;
          foreach($tableOneData as $x){

              foreach($tableTwoData as $b){
                  if($x[$compare] == $b[$compare]){
                      foreach($tableTwoHeader as $tth){
                          if($tth != $compare){
                              $newTableData[$rowCounter][$tth] = $b[$tth];
                          }

                      }
                  }
              }
                $rowCounter++;
          }
          $allData = array();

          $allData["tableOneHeader"] = $tableOneHeader;
          $allData["tableOneData"]  = $tableOneData;
          $allData["tableTwoHeader"] = $tableTwoHeader;
          $allData["tableTwoData"]  = $tableTwoData;
          $allData["newTableHeader"] = $newTableHeader;
          $allData["newTableData"]  = $newTableData;
          return $allData;
      }

      public static function Write($header,$data,$filename){
          try{
             if(@$fopen = fopen($filename,"w")){
             fputcsv($fopen,$header,",");
                foreach($data as $a){
                    $temp = array();
                    foreach($a as $b){
                        $temp[] = $b;
                    }
                    fputcsv($fopen,$temp,",");
                    unset($temp);
                }

          fclose($fopen);}else{
               echo "There is Error opening file <br>If you are Opening this file somewhere then please close it and open this file again";
             }
          }
          catch(Exception $e){
              throw new Exception($e->getMessage());
          }
       }

      public static function getAmount($data,$status,$amount){
          $sum = 0;
          foreach($data as $x){
               if($x["status"] == $status){
                   if($x[$amount] != "null" && $x[$amount] != null && !empty($x[$amount])){
                       $sum = $sum + $x[$amount];
                   }
               }
           }
          return $sum;
      }
  }
?>