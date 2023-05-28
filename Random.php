<?PHP
/* 
*This is a simple pseudo Random number generator in PHP
*by Jehsh Philemon
*/
<?php
/**
  *Random class
  *This class facilitate to work with random stuf and it was inspired by random module from python, you can contribute
  *by Jehsh Philemon Mulwahali
  * Version 0.1.0 : January 1, 2021
**/
namespace Jehsh\Random;
class Random{
    private $seed;
   
   public function __construct(){
       
   }
    public static function seed(int $seed=null){
        $this->seed = $seed;
        return !empty($this->seed) ? srand($this->seed) : '';
    }
    
    protected static function wrand($array,$p){
        //$p is an array of probabilities or weights
        if(!is_array($p)||!is_array($array)){
            throw new \Exception("invalid argument passed for choice, only arrays are accepted. ");
        }
        $weight_sum = array_sum($p);
    
        $rand_num = rand(1,$weight_sum);
        $n = count($array);
        $i = 0;
        while($rand_num > 0 && $i < $n){
            $rand_num -= $p[$i];
            $i++;
        }
        return $array[$i - 1];
    }
    
    public static function choice(array $values, array $p=null, ...$size ){
    /*@param(*) array of $values
      *@param $p probability with the same shape as $values 
      *@param $size shape of return array
      *@return one value or array of value depending on their probabilities
      */
        $size = self::is_null($size) ? null : $size ;      
        if(!$p){
            if( !self::is_null($size) || self::is_numeric_array($size) || is_numeric($size)){
                return self::generate_array($size,'choice',[$values]);
            }
            $dim=count($values)-1;
            return $values[rand(0,$dim)];
        }

        if(!self::is_vector($p)||!self::is_vector($values))
            throw new \Exception("random.choice accept vector only(simple array), arrays or one of them contain(s) sub-array");
        if(count($values)!==count($p))
            throw new \Exception("random.choice arrays  must have same size");
        if($size){
            $ar = self::generate_array($size,'wrand',[$values,$p]);
            return $ar;
        }
        return self::wrand($values,$p);
    }

    public static function normal( float $min=0, float $max=1, ...$size ){
        $size = self::is_null($size) ? null : $size ;     
        $mean = ($max-$min)/2;
        $x = self::rand_01();
        $y = self::rand_01();
        $gaussian = sqrt(-2*log($x)) * cos(2*M_PI*$y);
        //$gaussian2 = sqrt(-2*log($x)) * sin(2*M_PI*$y);
        if($size){
            $rand_number=self::generate_array($size,'normal',[$min,$max]);
            return $rand_number;
        }
        if( $min==0&&$max==1){
            return $gaussian;
        }else{
            $standard_deviation = $std ?? 1.0;
            $rand_number = $gaussian*$standard_deviation + $mean;
            $rand_number = ($rand_number < $min || $rand_number > $max) ? self::normal($min, $max, $std) : $rand_number;
        }
        
        return $rand_number;
    }
    
    protected static function rand_01($negative=false):float{
        //when negative is true return a float between -1 and 1
        if(!$negative)
            return (float) rand()/getrandmax();
        
        return (float) rand( getrandmax() * -1,getrandmax() )/getrandmax();
    }
        
    public static function rand(...$size){
        $size = self::is_null($size) ? null : $size ;
        if(null==$size)
            return (float) self::rand_01();
        if(is_numeric($size)||self::is_numeric_array($size)){
            $temp_ar = self::generate_array($size, 'rand');
            return  $temp_ar;
        }
    }
        
    public static function randn(...$size){
        $size = self::is_null($size) ? null : $size ;
        if(null==$size)
            return (float) self::normal();
            
        if( is_numeric($size) || self::is_numeric_array($size) ){
            $temp_ar=self::generate_array($size,'randn');
              
            return $temp_ar;
          }  
        else{
            throw new \Exception ("invalid arguments for Random::randn()");
        }
    }
    private static function generate_array($size, $method = null, $args = []): array {
    /*
      *generate_array()  Generate an array of xshape ($size)
      *and fill it with 0 if non method is called 
      *or  with result of self::$method($args)
      *@param $size (*): int|array shape of array 
      *@param $method: string method of this class
      *
      *
      *
      */

        //
        $size = (is_array($size)&&!self::is_vector($size) && $size[1]===null) ? $size[0] : $size;
        if(!is_numeric($size)&&!self::is_numeric_array($size))
            throw new \Exception("invalid arguments, size accept only integer or array of integer");
        if($size==0)//when $size = 0
            throw new \Exception("invalid argument, array size must be > to 0 ");
        
        //I use  (int) $size==$size because it's faster than is_int($size)
        $size = ( (int) $size==$size ) ? [$size] : $size;
        $ar = array_fill( 0, $size[0], @count($size) === 1 ? 0 :

            self::generate_array(array_slice($size, 1)) );
        array_walk_recursive($ar,"self::replace",["method"=>$method, "args"=>$args]);
            
        return $ar;
        
    }
    
    protected static function replace(&$value,$key,  $params=[]){
        $method = $params['method'];
        $args = $params['args'];
        if(!empty($method)){//replace  zeros (0) by $self::method($args) values
        //$ar =  str_replace($def, call_user_func_array( [__NAMESPACE__.'\'.$method], is_array($args) ? $args[0] : $args ) , $ar);
        //$self = new self();
        $value = self::$method(
             $args[0] ??null, $args[1] ??null, $args[2] ??null 
             );
        return $value;    
        }  
    }
    
    private static function is_numeric_array($array) : bool{
        if(!is_array($array))
            return false;
        foreach($array as $value){
            if($value != (float) $value)
                return false;
            
        }
        return true;
    }
    
    private static function is_vector($array) : bool{
        if(!is_array($array))
            return false;
        rsort($array);
       
        if(is_array($array[0]))
            return false;
        return true;

    }
    
    public static function is_null($value):bool{
        if(is_array($value)){
            $isNull=true;
            array_walk_recursive($value, function($value) use(&$isNull){
                if($value!=null)
                    $isNull = false;
            });
            return $isNull;
        }
        if(empty($value))
            return true;
        return false;
    }
    public static function randint(int $start, int $end=null, ...$size){  
        $size = self::is_null($size) ? null : $size ;
        if(null===$end&&!$size){
            return rand(0,$start-1);
        }
        if($end&&!$size){
            return $number = rand($start,$end-1);
            }
        if($size&&$end==null){
            $number=self::generate_array($size,'randint',[$start]);
            return $number;
        }
        if($size&&$end){
            $ar = self::generate_array($size,'randint',[$start,$end]);
            return $ar;
        }
    }  
    public static function randrange(int $start, int $end=null, int $step=null, ...$size){      /**
      * @param $a (if a is only param, return rand(0,$a))
      * @param $b 
      * @param $c
      * return int or Array
      */
    
        $size = self::is_null($size) ? null : $size ;
        //when $step is high than $end minus $start
        $step = ($step==null || !is_numeric($step)) ? 1 : $step;
        $check =( !$end&&$step&&(0+$step<=$start) ) ? true : ( ($end&&$step&&($start+$step<=$end) )? true:false );
        if(!$check)
            throw new \Exception("step is higher, it must be a number lower than (end-start) ");
        if(null===$end&&$step===1&&null==$size)
            return $number = rand(0,( $start ? $start-1 :0) );
        elseif($end&&null==$size&&$step===1)
            return $number = rand($start,$end-1);
        elseif($step&&null===$end&&null==$size){
            //$number = rand(0,$start,$step-1);
            $temp_ar=range(0,$start,$step);
            $end=count($temp_ar)-1;
            return $number = $temp_ar[rand(0,$end)];
            //$temp_ar = null;
        }
        elseif($size){
            $ar=self::generate_array($size,'randrange',[$start,$end,$step]);
            return $ar;
        }
        else{
            $temp_ar=range($start,$end-1,$step);
            $end=count($temp_ar)-1;
            $number = $temp_ar[rand(0,$end)];
            $temp_ar='';
        }
        return $number; 
    }
    
    public static function sample(array $array, ...$size){
        $size = self::is_null($size) ? null : $size ;
        if(!is_array($array))
            throw new \Exception("The first parameter must be an array or NdArray object. ".gettype($array)." passed");
        if(!$size){
            $dim=count($array)-1;
            return $array[rand(0,$dim)];
        }
        else{
            $sample = self::generate_array($size,'sample',[$array]);
            /*for($i = 0; $i < $b; $i++){
                $sample[] = self::sample($a);
                                
            }*/
            return $sample;
        }
    }
    
    final public static function random(...$size){
        $size = self::is_null($size) ? null : $size ;
        if(!$size)
            $random = rand()/(getrandmax() - 1e-16);
        else
            $random = self::generate_array($size,"random");
        return $random;
    }

    public static function shuffle($array):array{
        shuffle($array);
        return $array;
    }
    
    /*public static function uniform(float $start, float $end, ...$size){
           //to do   

    }*/      
}
?>
