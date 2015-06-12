<?php
	if  (! ctype_digit($id)) {
        $app->redirect($app->request->getRootUri().'/admin/');
    }
    
    $quiz = $app->quiz;
    
    $question = trim($app->request->post('questiontext'));
	$url = trim($app->request->post('questionurl'));
    $correct = (int) trim($app->request()->post('correct'));
    $answerarray = $app->request()->post('answer');
    
    if ($quiz->setId($id)) {
        $quiz->populateQuestions();
        $categories = $app->simple->getCategories(false);
        $i = 0;
        foreach ($answerarray as $answer) {
            if (trim($answer) == '') {
                $app->flashnow('error', "Answers can't be empty");
                $app->render('admin/quiz.php', array('quiz' => $quiz, 'categories' => $categories));
                $app->stop();
            }
            if ($i == $correct) {
                $correctAnswer = 1;
            } else {
               $correctAnswer = 0;
            }
            $answers[] = array($answer, $correctAnswer);

            $i++;
        }
        try {
			$embed = trim($app->request->post('embed'));
			if(strcmp($embed, "video") == 0)
				$quiz->addQuestion($question, 'radio', $answers, $url);
			if(strcmp($embed, "image") == 0) {
				$target_dir = "uploads/";
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
					$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
					if($check !== false) {
						echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						echo "File is not an image.";
						$uploadOk = 0;
					}
				}
			}
			$quiz->addQuestion($question, 'radio', $answers, null);
            $app->flashnow('success', 'New Question saved successfully');
        } catch (Exception $e ) {
            $app->flashnow('error', 'An error occurred creating a new question');
            $app->flashnow('error', $e->getMessage());
        }
        $quiz->populateUsers();
   
        $app->render('admin/quiz.php', array('quiz' => $quiz, 'categories' => $categories));
    } else {
        echo 'oops';
    }
?>