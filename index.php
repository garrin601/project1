<?php


// error control and error reporting with iniset and error_report
// debugging properties as well

ini_set('display_errors', 'On');                            
error_reporting(E_ALL);

// class step1 to allow access to other directories that end with .php
// access to and from stringFunctions.php is done through this line right here
// labeled step 1 for logical reasons as locating and calling other codes from differnt files


class Step1 {                                              
public static function autoload($class) 
	{
		include $class . '.php';
	}	
}


// function register with spl_ autoload

spl_autoload_register(array('Step1', 'autoload'));


// instantitate  main program 

$obj = new main();                                           
class main 
	{
	public function __construct() 

//set default page request when no parameters are in URL
	
	{		$pageRequest = 'uploadForm';

//check if there are parameters

			if(isset($_REQUEST['page'])) 
				{
					$pageRequest = $_REQUEST['page'];
// creating new page request
				}						
			$page = new $pageRequest;
			if($_SERVER['REQUEST_METHOD'] == 'GET') 
				{
					$page->get();
				} 
			else {
					$page->post();
				}			
		}
	}	

// page class  that will carry properties from other classes that they will extend into


abstract class page 
	{
		protected $html;
		public function __construct() 
	{

		$this->html .= '<html>';
		$this->html .= '<link rel="stylesheet" href="styles.css">';
		$this->html .= '<body>';
	
	}	




		public function __destruct() 
			{
				$this->html .= '</body></html>';
			// being called to from stringFunctions in PWD (stringFunctions.php)	
				strngFunct::printThis($this->html);
			}			

		public function get() 

			{


				echo 'default get message';
			}			

		public function post() 
			{

			// print into an array 
				print_r($_POST);
			}			
	}	


class strngFunct
			// creating static public function to be able to print out text in html on page
	{             
		static public function printThis($inputText) 
		{
			return print($inputText);
		}	
	}


// contents will be displayed by the display class
// extends into the page class


class display  extends page 	
	{                         
		public function get() 
			{
			// grabs cvs file which will be the 'filename'
				 $csv = $_GET['filename'];
			// changes the directory via chdir into the upload folder	
				chdir('uploads');                                     
			//open csv file and put into following loop below to get data
				$file = fopen($csv,"r");
				// defines the formatting of that said table 
				tags::tableform();               
				 $row = 1;
				 //loop through the data
 					while (($data=fgetcsv($file))!== FALSE)
						{    
 							foreach($data as $value) {
								 if ($row == 1) {
 								 tags::tableheadings($value);
 								 		}
 								 else
								 	{
										//print the value of the data
										tags::data($value);
  									}
 										 }
 											//increment each row
											$row++;
 										// break table
			
										tags::breaking();
  						}
		// close data stream 
		
				  fclose($file);
          		}
	      }



// extending page class into uploadForm
// index.php?page=uploadForm is url to locate the upload form location

class uploadForm extends page
		{
			public function get() 
		{
   			 $form = '<form action="index.php?page=uploadForm" method="POST" enctype="multipart/form-data">';
   			 $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
   			 $form .= '<input type="submit" value="Upload CSV" name="submit">';
    		       	$form .= '</form>';
    			 $this->html .= tags::head('Upload Form');
    			 $this->html .= $form;
     	
		}

	// locating and posting an uplodated file into the Uploads directory within the project1 directory

     public function post() {                         
     	// set target directory where we can uploaded folder to go into
     $target_dir = "uploads/";
     	// target file will be located in the target directory with the name it is uploaded as
     $target_file = $target_dir . $_FILES["fileToUpload"]["name"];
     	//file name is simply the file name it was given
     $filename = $_FILES["fileToUpload"]["name"];

     		if (file_exists($target_file)) 
		{
   // unlink command enables the target_file (CSV) to be removed and replaced if it is duplicated within AFS 
  		  unlink($target_file);
     		}

		// creates file path of newly uploaded target file 
     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
     		{
   				//newly path created
    			 header("Location: index.php?page=display&filename=$filename");
    		 }			
     			}	
     	
	
	
	}			
    
    
  // html properties 


    class tags 
    	{                
    	
	
	static public function head($text) 
		{
    			 return '<h1>' . $text . '</h1>';
     		}	
     // table format
     // adding space between the cells and boarders
     // border 10 points large
     // border will be solid blue in color
     //text align is set to left

     static public function tableform() 
     	{
     		echo "<table padding='2px' border='10px ' style='border: solid blue' text-align:'left' >";
     	}		

     // text size is set to small

     static public function tableheadings($text) 
     	{	
     		echo '<th style="font-size: small">'.$text.'</th>';
    	}
     // start with data here
     static public function data($text) 
    	 {
     		echo '<td>'.$text.'</td>';
     	}
     // end data here

     static public function breaking() 
     	{	
     		echo '</tr>';
     	}
     }
    
    
    
	           
?>
