<?php

$authenticate = function ($app, $admin = false) {

    return function () use ($app, $admin) {
        $errors = array();
        if ($admin)
        {
            if (! $app->session->get('user') instanceof \SimpleQuiz\Utils\User\AdminUser)
            {
                $errors['loginerror'] = 'You do not have Administrator access.';
                $app->session->set('urlRedirect', $app->request()->getPathInfo());
                $app->flash('errors', $errors);
                $app->redirect($app->request->getRootUri() . '/login/');
            }
        }
        else
        {
            //stops non-registered users and admin user from taking quizzes too
            if (! $app->session->get('user') instanceof \SimpleQuiz\Utils\User\EndUser)
            {
                $errors['loginerror'] = 'You need to login to take a quiz';
                $app->session->set('urlRedirect', $app->request()->getPathInfo());
                $app->flash('errors', $errors);
                $app->redirect($app->request->getRootUri() . '/login/');
            }
        }
    };
};

$app->get("/logout/", function () use ($app) {
    $session = $app->session;
    $session->end();
    $app->redirect($app->request->getRootUri().'/');
});

$app->get('/admin/', $authenticate($app, true), function () use ($app) {
    
    $simple = $app->simple;
    $quizzes = $simple->getQuizzes(false);
    $categories = $simple->getCategories(false);

    $app->render('admin/index.php', array('quizzes' => $quizzes, 'categories' => $categories));
});

$app->post("/admin/quiz/", $authenticate($app, true), function() use ($app) {
    
    $quizmeta = array();
    
    $quizname = trim($app->request->post('quizname'));
    $quizdescription = trim($app->request->post('description'));
    $quizcategory = trim($app->request->post('category'));
	$quizduration = (int) trim($app->request->post('duration'));
    $active = (int) trim($app->request()->post('active'));
    
    if ( ($quizname !== '') && ($quizdescription !== '') ) {
        $quizmeta['name'] = ucwords($quizname);
        $quizmeta['description'] = $quizdescription;
		$quizmeta['duration'] = $quizduration;
        $quizmeta['category'] = $quizcategory;
        $quizmeta['active'] = $active;
        
        $simple = $app->simple;
    
        if ($simple->addQuiz($quizmeta)) {
            $app->flash('success', 'Quiz has been created successfully');

            $app->redirect($app->request->getRootUri().'/admin/');
        } else {
            //problem adding quiz
            $app->flash('error', 'Problem creating the quiz');
            $app->redirect($app->request->getRootUri().'/admin/');
        }
    } else {
        //problem with post inputs
        $app->flash('error', 'Problem creating the quiz. Something wrong wth inputs');
        $app->redirect($app->request->getRootUri().'/admin/');
    }
        
});

$app->put("/admin/quiz/", $authenticate($app, true), function() use ($app) {
    
    $quizmeta = array();
    
    $quizid = trim($app->request->put('quizid'));
    $quizname = trim($app->request->put('quizname'));
    $quizdescription = trim($app->request->put('description'));
	$quizduration = (int) trim($app->request->post('duration'));
    $quizcategory = trim($app->request->post('category'));
    $active = (int) trim($app->request()->put('active'));
    
    if ( ($quizname !== '') && ($quizdescription !== '') && (ctype_digit($quizid)) ) {
        
        $quizmeta['id'] = $quizid;
        $quizmeta['name'] = ucwords($quizname);
        $quizmeta['description'] = $quizdescription;
		$quizmeta['duration'] = $quizduration;
        $quizmeta['category'] = $quizcategory;
        $quizmeta['active'] = $active;
        
        $simple = $app->simple;
    
        if ($simple->updateQuiz($quizmeta)) {
            $app->flash('success', 'Quiz has been updated');

            $app->redirect($app->request->getRootUri().'/admin/');
        } else {
            //problem adding quiz
            $app->flash('error', 'Problem updating the quiz');
            $app->redirect($app->request->getRootUri().'/admin/');
        }
    } else {
        //problem with post inputs
        $app->flash('error', 'Problem updating the quiz. Something wrong wth inputs');
        $app->redirect($app->request->getRootUri().'/admin/');
    }
        
});

$app->delete("/admin/quiz/", $authenticate($app, true), function() use ($app) {
    
    $quizid = trim($app->request->post('quizid'));
    
    if (ctype_digit($quizid) ) {
        
        $simple = $app->simple;
        try {
			deleteDir("C:/xampp2/htdocs/simple-quiz/public/uploads/".$quizid);
            $simple->deleteQuiz($quizid);
        } catch (Exception $e ) {
            echo json_encode(array('error' => $e->getMessage()));
        }
        echo json_encode(array('success' => 'Quiz has been deleted successfully'));
        $app->stop();
        
    } else {
        echo json_encode(array('error' => 'non-int quiz'));
    }
        
});

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

$app->get("/admin/quiz/:id/", $authenticate($app, true), function($id) use ($app) {

    $quiz = $app->quiz;
    
    if ($quiz->setId($id)) {
        $quiz->populateQuestions();
        $quiz->populateUsers();
        $categories = $app->simple->getCategories(false);
        
        $app->render('admin/quiz.php', array('quiz' => $quiz, 'categories' => $categories));
    }
        
})->conditions(array('id' => '\d+'));


$app->put("/admin/quiz/:id/", $authenticate($app, true), function($id) use ($app) {
    
    $questionid = $app->request->put('questionid');
    $text = $app->request->put('questiontext');
	$url = $app->request->put('questionurl');
    
    if (! ctype_digit($id)) {
        $app->redirect($app->request->getRootUri().'/admin/');
    }
    
    $quiz = $app->quiz;
    
    if ($quiz->setId($id)) {

        $quiz->populateQuestions();

        $categories = $app->simple->getCategories(false);
        
        if ( (! ctype_digit($questionid)) || (trim($text) == '') ) {
            $app->redirect($app->request->getRootUri().'/admin/');
        }

        try {
            $quiz->updateQuestion($questionid, $text, $url);
            $app->flashnow('success', 'Question saved successfully');
        } catch (Exception $e ) {
            $app->flashnow('error', $e->getMessage());
        }
        $quiz->populateUsers();
        $app->render('admin/quiz.php', array('quiz' => $quiz, 'categories' => $categories));
        
    }
        
});

$app->post("/admin/quiz/:id/", $authenticate($app, true), function($id) use ($app) {
    
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
			if(strcmp($embed, "video") == 0) {
				$quiz->addQuestion($question, 'radio', $answers, $url, 1);
				$app->flashnow('success', 'New Question saved successfully');
			}
			else if(strcmp($embed, "image") == 0) {
				$target_dir = 'uploads/'.$quiz->getId().'/';
				if(!file_exists($target_dir)) {
					mkdir($target_dir, 0755, true);
				}
				$temp = explode(".",$_FILES["fileToUpload"]["name"]);
				$newfilename = rand(1,99999) . '.' .end($temp);
				$target_file = $target_dir . basename($newfilename);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
					$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
					if($check !== false) {
						echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						$error_message = "File is not an image.";
						$uploadOk = 0;
					}
				}
				// Check if file already exists
				if (file_exists($target_file)) {
					$error_message = "Sorry, file already exists.";
					$uploadOk = 0;
				}
				// Check file size
				if ($_FILES["fileToUpload"]["size"] > 500000) {
					$error_message = "Sorry, your file is too large.";
					$uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					$error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					$app->flashnow('error', $error_message);
					//echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
				} else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
						//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
						$quiz->addQuestion($question, 'radio', $answers, $newfilename, 2);
						$app->flashnow('success', 'New Question saved successfully');
					} else {
						$error_message = "Sorry, there was an error uploading your file.";
						$app->flashnow('error', $error_message);
					}
				}
			}
			else {
				$quiz->addQuestion($question, 'radio', $answers, null, 0);
				$app->flashnow('success', 'New Question saved successfully');
			}
        } catch (Exception $e ) {
            $app->flashnow('error', 'An error occurred creating a new question');
            $app->flashnow('error', $e->getMessage());
        }
        $quiz->populateUsers();
   
        $app->render('admin/quiz.php', array('quiz' => $quiz, 'categories' => $categories));
    } else {
        echo 'oops';
    }
        
});

$app->delete("/admin/quiz/:id/", $authenticate($app, true), function($id) use ($app) {
    
    $questionid = $app->request->post('questionid');
            
    if (! ctype_digit($id)) {
        $app->redirect($app->request->getRootUri().'/admin/');
    }
    
    $quiz = $app->quiz;
    
    if ($quiz->setId($id)) {
        
        try {
            $quiz->deleteQuestion($questionid);
        } catch (Exception $e ) {
            echo json_encode(array('error' => $e->getMessage()));
        }
        echo json_encode(array('success' => 'Question successfully deleted'));
        $app->stop();
    }
        
});

$app->get("/admin/quiz/:quizid/question/:questionid/edit/", $authenticate($app, true), function($quizid,
                                                                                           $questionid) use ($app) {
   
    $quiz = $app->quiz;
    
    if ($quiz->setId($quizid)) {
        $quiz->populateQuestions();
        $question = $quiz->getQuestion($questionid);
        $answers = $quiz->getAnswers($questionid);
        $app->render('admin/editanswers.php', array('quizid' => $quizid,'questionid' => $questionid, 'question' => $question, 'answers' => $answers));
    } else {
        echo 'oops';
    }
        
})->conditions(array('quizid' => '\d+', 'questionid' => '\d+'));

$app->put("/admin/quiz/:quizid/question/:questionid/edit/", $authenticate($app, true), function($quizid,
                                                                                           $questionid) use ($app) {
   
    if ( (! ctype_digit($quizid)) || (! ctype_digit($questionid))) {
        $app->redirect($app->request->getRootUri().'/admin/');
    }
    
    $quiz = $app->quiz;
    
    $correct = (int) trim($app->request()->put('correct'));
    $answerarray = $app->request()->put('answer');
    
    if ($quiz->setId($quizid)) {
        $quiz->populateQuestions();
        $question = $quiz->getQuestion($questionid);
        $i = 0;
        foreach ($answerarray as $answer) {
            if (trim($answer) == '') {
                $app->flashnow('error', 'Answers can\'t be empty');
                $answers = $quiz->getAnswers($questionid);
                $app->render('admin/editanswers.php', array('quizid' => $quizid,'questionid' => $questionid, 'question' => $question, 'answers' => $answers));
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
            $quiz->updateAnswers($answers, $questionid);
            $app->flashnow('success', 'Answers saved successfully');
        } catch (Exception $e ) {
            $app->flashnow('error', 'An error occurred');
        }
        $answers = $quiz->getAnswers($questionid);
        $app->render('admin/editanswers.php', array('quizid' => $quizid,'questionid' => $questionid, 'question' => $question, 'answers' => $answers));
    } else {
        echo 'oops';
    }
});
