<?php include 'header.php'; ?>
<div id="container" class="quiz">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <?php
        if (! $session->get('last') ) :
                $question = $quiz->getQuestion($num);
                $answers = $quiz->getAnswers($num);
				$duration = $quiz->getDuration();
				//if($duration != -1)
					//include "timer.php";
            ?>
            <h4>Current tester: <strong><?php echo $user->getName(); ?></strong></h4>
			<?php if($duration != -1) { ?>
			<h4>Time Left: <span id="minleft"></span> mins : <span id="secleft"></span> secs</h4>
			
			<div class="countdown countdown-container container">
    <div class="clock row">
        <div class="clock-item clock-days countdown-time-value col-sm-6 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-days" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-days type-time">DAYS</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->

        <div class="clock-item clock-hours countdown-time-value col-sm-6 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-hours" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-hours type-time">HOURS</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->

        <div class="clock-item clock-minutes countdown-time-value col-sm-6 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-minutes" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-minutes type-time">MINUTES</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->

        <div class="clock-item clock-seconds countdown-time-value col-sm-6 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-seconds" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-seconds type-time">SECONDS</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->
    </div><!-- /.clock -->
</div><!-- /.countdown-wrapper -->



<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo $root; ?>/res/js/kinetic.js"></script>
<script type="text/javascript" src="<?php echo $root; ?>/res/js/jquery.final-countdown.js"></script>
<script type="text/javascript">
	$('.countdown').final_countdown({
        'start': <?php echo $_SESSION['starttime']; ?>,
        'end': <?php echo $_SESSION['starttime'] + $duration*60; ?>,
        'now': <?php echo time(); ?>        
    });
</script>

			
			
			
			
		</div>
			<?php } ?>
            <h2>Question <?php echo $num; ?>:</h2>
            <p><?php echo $question->getText(); ?></p>
			<?php if($question->getType() == 1) { ?>
			<iframe width="560" height="315" src="<?php echo $question->getUrl(); ?>" frameborder="0" allowfullscreen></iframe>
			<?php } else if($question->getType() == 2) { ?>
				<img src="<?php echo $root.'/uploads/'.$quiz->getId().'/'.$question->getUrl(); ?>" width="400" height="300"/>
			<?php } ?>
            <form id="questionBox" method="post" action="<?php echo $root; ?>/quiz/process">
                <ul>
                <?php 
                $shuffledAnswers = SimpleQuiz\Utils\Base\Utils::shuffleAssoc($answers);

                $acount = 0;
                foreach ($shuffledAnswers as $answer) 
                {
                    echo '<li><input type="radio" id="answer' . $acount . '" value="' . $answer . '" name="answers" />' .PHP_EOL;
                    echo '<label for="answer' . $acount . '">' . $answer . '</label></li>' . PHP_EOL;
                    $acount++;
                }
                ?>
                </ul>
            <p>
                <input type="hidden" name="nonce" value="<?php echo $nonce; ?>" />
                <input type="hidden" name="num" value="<?php echo $num; ?>" />
                <input type="hidden" name="quizid" value="<?php echo $quiz->getId(); ?>" />
                <input type="submit" id="submit" class="btn btn-primary" name="submit" value="Submit Answer" />
            </p>
        </form>
        <?php else:
            $percentage = round(((int) $session->get('score') / (int) $quiz->countQuestions()) * 100);
            ?>
            <div id="finalscore">
            <h2 id="score"><?php echo $user->getName(); ?> answered <?php echo $session->get('score'); ?> correct out
                of a possible <?php echo $quiz->countQuestions(); ?></h2>
            <h2 class="userscore"><?php echo $percentage; ?>%</h2>
            <h3 id="time">Time Taken: <?php echo $timetaken['mins'] . $timetaken['secs']; ?></h3>

            <p id="compare"><a href="<?php echo $root; ?>/quiz/<?php echo $quiz->getId(); ?>/results">See how you
            compare!</a></p>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div><!--container-->

<?php include 'footer.php';