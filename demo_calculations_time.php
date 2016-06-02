<?php

    
    /* 
     * Just demo'ing the kind of calculations we're trying to make
     */
    
    
    /* A simple function that echoes the results */

    function show_scores($scores){
        echo "\r\n<ul>\r\n";
        forEach($scores as $score => $chance) {
            echo "<li><b>" . $score .":</b> " . $chance ."</li>\r\n";
        }
        echo "\r\n</ul>\r\n";
    }
    
    
/*
 * Imagine a single attack with a 30% chance to score a kill
 * Then the outcomes are:
 */
    
    $single_attack[0] = 0.7;
    $single_attack[1] = 0.3;

    echo "<p>The outcomes for a single attack:</p>";
    show_scores($single_attack);
   
    
    /* **Output**
        The outcomes for a single attack:
     
        0: 0.7;
        1: 0.3;
    
     */

/*
 * Now let's try two attacks, combination theory.
 */

      // 0 kills on first, and 0 kills on second attack
    $two_attacks[0] = $single_attack[0] * $single_attack[0];
    // Scoring 1 kill can be done on either the first OR second attack.
    // The chances of either path are added
    $two_attacks[1] = $single_attack[1] * $single_attack[0];
    $two_attacks[1] += $single_attack[0] * $single_attack[1];
     // 2 kills can only be scores by scoring a kill on the first AND second attack
    $two_attacks[2] = $single_attack[1] * $single_attack[1];
    
    echo "<p>The outcomes for two attacks, done with combination theory:</p>";
    show_scores($two_attacks);
    
    
/*
    This could also be written as:
    (0.7 w^0 + 0.3 w^1) * (0.7 w^0 + 0.3 w^1)
    = 0.49 w^0 + 0.42 w^1 + 0.09 w^2
 
    A quick and dirty function for this algebraic multiplication.
 */
    function multiply_scores_dirty($scores1, $scores2){
        $result = array(0,0,0); //initialize the result;
        forEach($scores1 as $score1 => $chance1) {
            forEach($scores2 as $score2 => $chance2){
                $result[($score1 + $score2)] += $chance1 * $chance2;
            }
            
        }
        return $result;
    
    }
    
    echo "<p>The outcomes for two attacks, using algebraic formula:</p>";
    show_scores(multiply_scores_dirty($single_attack, $single_attack));
    
    
    /*
     This function has a few issues, so let's fix those.
     These are technical matters, really.
     */
    function multiply_scores($scores1, $scores2){
        $result = array(); //initialize the result;
        forEach($scores1 as $score1 => $chance1) {
            //Tiny optimization, avoiding work if the chance is 0 anyway
            if($chance1 != 0){
                forEach($scores2 as $score2 => $chance2){
                    //Tiny optimization, avoiding work if the chance is 0 anyway
                    if($chance2 !=0){
                        // Check if there already is a chance for this outcome
                        if(isset($result[($score1+$score2)])){
                            // If so, add it
                            $result[($score1 + $score2)] += $chance1 * $chance2;
                        } else {
                            // If not, set it as the first one.
                            $result[($score1 + $score2)] = $chance1 * $chance2;
                        }
                    }
                }
            }
        }
        return $result;
        
    }
    
    echo "<p>The outcomes for two attacks, using the improved function:</p>";
    show_scores(multiply_scores($single_attack, $single_attack));
    
    
    /*
     When using this for three attacks we can write this as:
     (0.7 w^0 + 0.3 w^1) * (0.7 w^0 + 0.3 w^1) * (0.7 w^0 + 0.3 w^1)
     
     Or.. for any arbitrary number "n":
     (0.7 w^0 + 0.3 w^1)^n
     */
    
    function scores_to_power($scores, $power){
        $result = array(0=>1);
        while($power>0){
            $result = multiply_scores($result, $scores);
            $power--;
        }
        return $result;
    }
    
    
    echo "<p>The outcomes for two attacks, testing our exponentiation:</p>";
    show_scores(scores_to_power($single_attack, 2));
    
    
    echo "<p>The outcomes for five attacks, testing our exponentiation:</p>";
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$start1 = $mtime;
    show_scores(scores_to_power($single_attack, 500));
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $stop1 = $mtime;
    $time1 = $stop1-$start1;
    echo "<p> Calculated in ".$time1." seconds</p>";
	
    /* 
     Another way to compute this, is using the binomial theorem, which is 
     a formalized model on the algebraic formula (q + p)^n where:
        q = the chance to fail, or 1-p
        p = the chance to succeed, or 1-q
        n = the number of tries
     */
    
    // calculate binomial coefficient
    function binomial_coeff($n, $k) {
        $j = $res = 1;
        if($k < 0 || $k > $n)
            return 0;
        if(($n - $k) < $k)
            $k = $n - $k;
        while($j <= $k) {
            $res *= $n--;
            $res /= $j++;
        }
        return $res;
    }
    
    // Calculate the binomial distribution
    function binomial_distribution($chance, $attempts){
        $result = array();
        for($i=0; $i<= $attempts; $i++){
            $result[$i] = binomial_coeff($attempts, $i) * pow($chance, $i) * pow((1-$chance), $attempts-$i);
        }
        return $result;
    }

    echo "<p>The outcomes for five attacks, using the binomial theory:</p>";
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$start2 = $mtime;
    show_scores(binomial_distribution(0.3, 500));
	$mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $stop2 = $mtime;
    $time2 = ($stop2-$start2);
	echo "<p> Calculated in ".$time2." seconds</p>";
    
    /* More to come */
?>