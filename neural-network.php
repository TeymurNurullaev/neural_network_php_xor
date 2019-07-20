<?php


class N { 

var $enters = [0,1]; // 2 входа
var $hidden_layer = [0,1]; // два скрытых нейрона
var $synapses_hidden = [[0.3, 1.3], [0.5,0.1]]; // от входов к скрытым нейронам
var $synapses_output = [0.5, 0.1]; // от скрытых нейронов к выходу
var $output = 0; // выход


var $learn = [[0,0],[0,1],[1,0],[1,1]];
var $learn_answers = [0,1,1,0];    

var $gError = 0; // глобальная ошибка
var $errors = [0,1]; // слой ошибок


    function getSum() {

        for ($i = 0; $i < count($this->hidden_layer); $i++ ){
            $this->hidden_layer[$i] = 0;
            for ($j = 0; $j < count($this->enters); $j++ ) {
             $this->hidden_layer[$i] += $this->synapses_hidden[$j][$i] * $this->enters[$j];
            }
            if ($this->hidden_layer[$i] > 0.5 ) {
                $this->hidden_layer[$i] = 1; 
            }else{ 
                $this->hidden_layer[$i] = 0;
            }
        }

        $this->output = 0;
        for ( $i = 0; $i < count($this->hidden_layer); $i++ ) {
           $this->output += $this->synapses_output[$i] * $this->hidden_layer[$i];
        }

        if ( $this->output > 0.5 ) { 
            $this->output = 1; 
        }else{ 
            $this->output = 0;
        }

    }


    function getLlearn() {

        do {
            $this->gError = 0; // обнуляем

            for ($p = 0; $p < count($this->learn); $p++ ){

                for ($i = 0; $i < count($this->enters); $i++ ) {
                    $this->enters[$i] = $this->learn[$p][$i]; // подаём об.входы на входы сети
                }

                $this->getSum(); // запускаем распространение сигнала

                $this->error = $this->learn_answers[$p] - $this->output; // получаем ошибку
                $this->gError += abs($this->error); // записываем в глобальную

                for ( $i = 0; $i < count($this->errors); $i++ ) {
                    $this->errors[$i] = $this->error * $this->synapses_output[$i]; // передаём ошибку на слой ошибок
                }

                // по связям к выходу
                for ($i = 0; $i < count($this->enters); $i++ ){
                    for ( $j = 0; $j < count($this->hidden_layer); $j++ ) {
                        $this->synapses_hidden[$i][$j] += 0.1 * $this->errors[$i] * $this->enters[$j]; // меняем веса
                    }
                }

                for ($i = 0; $i < count($this->synapses_output); $i++ ) {
                    $this->synapses_output[$i] += 0.1 * $this->error * $this->hidden_layer[$i]; // меняем веса
                }
            } 
        } while($this->gError != 0);

    }

    public function start() {

        $this->getLlearn();

        for ($p = 0; $p < count($this->learn); $p++ ){
            
            for ( $i = 0; $i < count($this->enters); $i++ ) {
            $this->enters[$i] = $this->learn[$p][$i]; // записываем входы
            }
            $this->getSum(); // распространяем сигнал
            echo $this->output . "\n"; // выводим ответы
        }

    }

}

 //(new N)->start();

?>
