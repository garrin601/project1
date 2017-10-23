<?php


$destDir = "uploads/";
$baseName = basename($FILES["fileToUpload"]["name"]);
$targetFile = $destDir . $baseName;
$fileType = pathinfo($targetFile,PATHINFO_EXTENSION);
$check = 1;



	if (file_exists($targetFile))

	{

		echo "ERROR:" .$baseName." Exists";
		$check = 0;

	}

	if ($check ==0)
	{
		echo "sorry, file could not have been uploaded.";
	}

	else

	{
		 if (move_uploaded_file($_FILES["fileToUpload"]["tmpName"], $target_file))

		{
			echo $baseName . "Has been uploaded.";

		}

		else 

		{
			echo "There was an error, try again";
		}
	}


