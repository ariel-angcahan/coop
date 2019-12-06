<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// all NLP related codes will be placed here for centralized access
class NLP_helper {
	public function __construct() {
		$this->ci =& get_instance();
	}
	function remove_stop_words($words) {
		$stop_words = ['i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 'should', 'now'];
	    foreach ($stop_words as &$word) {
	        $word = '/\b' . preg_quote($word, '/') . '\b/iu';
	    }
	    $words = preg_replace($stop_words, '', $words);
	    
	    return preg_replace('/\s+/', ' ', $words);
	}
	function get_highest_relevance_score($query, $matches) {
		$max_score = 0;
		$id;
		// print_r($query);
		foreach ($matches as $row) {
			
			$search = array_unique(array_filter(explode(' ', $this->remove_stop_words($row->description))));
			$count = 0;
			// echo '<br>';
			// print_r($search);
			// echo '<br>count search ';
			// print_r(count($search));
			foreach ($search as $chunk) {
				// TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO
				// perform stemming on the tokens to increase match percentage
				if (array_search($chunk, $query)) { // if chunk is relevant to the query
					$count++;
				}
			}
			// this determines relavance of current row to the query
			$score = $count / count($query);
			// echo '<br>score ';
			// print_r($score);
			// echo '<br>';
			// if score is 1 then all significant query words matches the current row
			// thus we return the row id to be used as a reason for cancellation
			//
			// otherwise, compare score with max to find out the most relevant row
			if ($score == 1) {
				$id =  $row->id;
				break;
			} else if ($score > $max_score) {
				$max_score = $score;
				$id = $row->id;
			}
		}
		$threshold = $this->score_threshold_helper(count($query));
		// echo '<br>count query ';
		// print_r(count($query));
		// echo '<br>';
		// print_r($threshold);
		// echo '<br>';
		// print_r($max_score);
		// only return ids of scores above the minimum threshold
		if ($max_score >= $threshold) {
			return $id;
		} else {
			return -1;
		}
	}
	private function score_threshold_helper($size) {
		$threshold = 0;
		switch ($size) {
			case 1:
				$threshold = 1; // all tokens must be present
				break;
			case 2:
				$threshold = .5; // one out of two
				break;
			case 3:
				$threshold = .65; // two out of three
				break;
			default:
				$threshold = .75;
		}
		return $threshold;
	}
}