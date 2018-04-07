<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skill_functions extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(array('session', 'function_lib', 'skill_lib'));
		$this->load->helper(array('url'));
		$this->data = array();

		$this->data['message'] = ($v = $this->session->flashdata('message'))?$v:array('content'=>'','color'=>'');

		// $this->data['csrf_token_name'] = $this->security->get_csrf_token_name();
	}

	 public function addSkills(){
	 	$skillID = '';
	 	if($x = $this->input->post('skill'))
	 		$skillID = $x;
	 	if($skillID == ''){
	 		$this->session->set_flashdata('message', array('content'=>'Please select a skill to proceed further.','color'=>'red'));
			redirect(base_url('skills'));
	 	}
	 	$_SESSION['userData']['currentSkill'] = $skillID;
	 	redirect(base_url('skill-test-guidelines'));
	 }

	 public function beginTest(){
			$skill_id = $this->input->get('skillID');
			$_SESSION['userData']['intest'] = false;
			$_SESSION['userData']['currentSkill'] = $skill_id;
			$_SESSION['userData']['currentSkillName'] = $this->skill_lib->getSkillData($skill_id)[0]['skill_name'];
			$_SESSION['userData'][$skill_id]['totalScore'] = 0;
			$_SESSION['userData'][$skill_id]['skips'] = 1;
			$_SESSION['userData'][$skill_id]['skipStatus'] = 0;
			$testTime = $this->skill_lib->getTestSettings($skill_id)[0]['testTime'];
			$_SESSION['userData'][$skill_id]['totalTime'] = $testTime;
			$_SESSION['userData'][$skill_id]['responses'] = array();
			$_SESSION['questionData'] = $this->getQuestionDetails($skill_id);
			redirect(base_url('skill-test'));
		}

		public function nextQuestion(){
			if(!$_SESSION['userData']['intest']){
				$this->session->set_flashdata('message', array('content'=>'You Need to Start or Resume a test to Answer.','color'=>'red'));
				redirect(base_url('skill-tests'));
			}
			$answer = $this->input->post('answer');
			$timeConsumed = $this->input->post('timeConsumed');
			$correct = $this->skill_lib->checkAnswer($_SESSION['questionData'][0]['question_id'], $answer);
			$skill_id = $_SESSION['userData']['currentSkill'];
			$_SESSION['userData'][$skill_id]['totalTime'] = $this->input->post('totalTime');
			$score = $this->calculateScore(1, $_SESSION['questionData'][0]['expert_time'], $timeConsumed, $correct);
			if($correct == 1){
				$correct = '1';
			}else{
				$correct = '0';
			}
			if($answer == 0){
				$timeConsumed++;
			}
			$data = array(
				'userID' => $_SESSION['user_data']['userID'],
				'questionID' => $_SESSION['questionData'][0]['question_id'],
				'answer' => $answer,
				'score' => $score,
				'timeConsumed' => $timeConsumed,
				'correct' => $correct
				);
			if($this->skill_lib->updateResponse($data, $score)){
				$_SESSION['userData'][$skill_id]['totalScore'] += $score;
				$totalScore = $_SESSION['userData'][$skill_id]['totalScore'];
				array_push($_SESSION['userData'][$skill_id]['responses'], $_SESSION['questionData'][0]['question_id']);
				$_SESSION['questionData'] = $this->getQuestionDetails($skill_id);
				$testData['questionData'] = $_SESSION['questionData'][0];
					if($_SESSION['userData'][$skill_id]['skips'] > 0){
						$testData['skipsLeft'] = $_SESSION['userData'][$skill_id]['skips'];
						$testData['skips'] = true;
					}
					else{
						$testData['skipsLeft'] = 0;
						$testData['skips'] = false;
					}
					$testData['totalScore'] = $totalScore;
					$testData['totalTime'] = $_SESSION['userData'][$skill_id]['totalTime'];
					if($totalScore >= 10 || $totalScore <= -10){
						$testData['questionData'] = null;
					}
					echo json_encode($testData);
			}else{
				echo "string"; die;
				$this->logout();
			}
		}

	public function skipQuestion(){
		if(!$_SESSION['userData']['intest'] || !isset($_SESSION['userData']['intest'])){
			$this->session->set_flashdata('message', array('content'=>'Sorry, Some Error Occured. You May resume the Test to Continue.','color'=>'red'));
			redirect(base_url('skill-tests'));
		}
		if(!$timeConsumed = $this->input->post('timeConsumed')){
			$timeConsumed = 0;
		}
		$answer = $this->input->post('answer');
		$skill_id = $_SESSION['userData']['currentSkill'];
		$_SESSION['userData'][$skill_id]['totalTime'] = $this->input->post('totalTime');
		if($_SESSION['userData'][$skill_id]['skips'] > 0){
			$_SESSION['userData'][$skill_id]['skips']--;
			$data = array(
				'userID' => $_SESSION['user_data']['userID'],
				'questionID' => $_SESSION['questionData'][0]['question_id'],
				'answer' => $answer,
				'score' => 0,
				'timeConsumed' => $timeConsumed,
				'correct' => '-1'
				);
			if($this->skill_lib->updateResponse($data)){	
				$totalScore = $_SESSION['userData'][$skill_id]['totalScore'];
				array_push($_SESSION['userData'][$skill_id]['responses'], $_SESSION['questionData'][0]['question_id']);
				$_SESSION['questionData'] = $this->getQuestionDetails($skill_id);
				$testData['questionData'] = $_SESSION['questionData'][0];
				if($_SESSION['userData'][$skill_id]['skips'] > 0){
					$testData['skipsLeft'] = $_SESSION['userData'][$skill_id]['skips'];
					$testData['skips'] = true;
				}
				else{
					$testData['skipsLeft'] = 0;
					$testData['skips'] = false;
				}
				$testData['totalTime'] = $_SESSION['userData'][$skill_id]['totalTime'];
				echo json_encode($testData);
			}else{
				$this->logout();
			}
		}else{
			$this->session->set_flashdata('message', array('content'=>'Some Error Occured Resume Test to Continue.','color'=>'red'));
			echo 'false';
		}
	}

	public function endTest(){
		$userID = $_SESSION['user_data']['userID'];
		$skill_id = $_SESSION['userData']['currentSkill'];
		$score = $_SESSION['userData'][$skill_id]['totalScore'];
		if($score >= 10){
			$result = $this->skill_lib->addSkill($score, $userID, $skill_id);
			if(!$result){
				$this->session->set_flashdata('message', array('content'=>'Some Error Occured. Please Try Again.1','color'=>'red'));
				$this->updateInfo();
				redirect('skills');
			}
			$this->session->set_flashdata('message', array('content'=>'Congratulations your score was '.$score.' and skill was Successfully added to your Profile.','color'=>'green'));
		}else{
			$this->session->set_flashdata('message', array('content'=>'Your score was '.$score.' and were unable to Clear the Skill Test. Better Luck Next Time.','color'=>'red'));
		} 
		$this->updateInfo();
		redirect('skills');
	}

	public function updateInfo(){
		$skill_id = $_SESSION['userData']['currentSkill'];
		$totalScore = $_SESSION['userData'][$skill_id]['totalScore'];
		$_SESSION['questionData'] = NULL;
		$_SESSION['userData']['currentSkill'] = NULL;
		$_SESSION['userData']['currentSkillName'] = NULL;
		$_SESSION['userData'][$skill_id]['totalScore'] = NULL;
		$_SESSION['userData'][$skill_id]['skips'] = NULL;
		$_SESSION['userData'][$skill_id]['skipStatus'] = NULL;
		$_SESSION['userData'][$skill_id]['totalTime'] = NULL;
		$_SESSION['userData'][$skill_id]['responses'] = NULL;
		$_SESSION['userData']['intest'] = false;
	}

	private function getQuestionDetails($skillID){
		$questionDetails = $this->skill_lib->getQuestionDetails($skillID);
		return $questionDetails;
	}

	private function calculateScore($difficulty_level = 1, $expert_time, $timeConsumed, $correct){
		$score = 0;
		if($correct == 0){
			$correct = -1;
		}
		$score = pow(((pow(3, ($difficulty_level/2)) * ((2*$expert_time)-$timeConsumed))/(2*$expert_time)), (2/$difficulty_level));
		$score = $score * $correct;
		if($correct == -1){
			$score = $score/2;
		}
		return $score;
	}



}
