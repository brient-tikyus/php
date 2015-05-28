<?php
	/**
	 * @name MyPassword class
	 * @category Password
	 * @author Brient Ludovic
	 * @version 2.0.0
	 */
	class MyPassword{
		
		protected $password;
		protected $allowed;
		protected $allowedNumber;
		protected $allowedUppercase;
		//protected $allowedLowercase;
		protected $maxLength;
		protected $minLength;
		protected $nbCharAccepted;
		
		/*
		 * $allowed = array();
		 * 
		 * $allowed['symbol'] = array(	'state' => true ,
		 * 								'default' => false , 
		 * 								'regex' => '' , 
		 * 								'var' => array() );
		 * 
		 */
		
		//---------------------------------------------
		//	Constructor
		//---------------------------------------------
		public function __construct(){
			$this->setOptions();
		}
		
		
		//==============================================
		//	METHODS
		//==============================================
		
		
		public function is_valid(){
			return true;
		}
		
		
		public function get_error(){
			return array();
		}
		
		
		
		/**
		 * 
		 * @return string
		 */
		public function generate(){
			$character = array(); 			//All supported charactere
			$char_counter = array();		//char type occurency counter
			$new_pwd = array();				//new password array
			$i = 0;							//while counter
			$merge = 0;
			$this->nbCharAccepted = 0;
			
				if($this->allowedSymbol){
					$character[] =  array('#','$','%','&','@','=','^','+','-','_');
					$char_counter[] = 0;
				}
				if($this->allowedNumber){
					$character[] =  array('2','4','6','8','0','1','3','5','7','9');
					$char_counter[] = 0;
				}
				if($this->allowedUppercase){
					$character[] =  array('Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M');
					$char_counter[] = 0;
				}
				if($this->allowedLowercase){
					$character[] = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m');
					$char_counter[] = 0;
				}

			foreach($character as $type){
				$this->nbCharAccepted += count($type);
			}
				
			$max_type = ceil($this->maxLength / count($character)); 	//max number of type occurency
			$merge = ceil($this->maxLength * 0.2);
				
			while($i<$this->maxLength){
				
				$a = rand(0, (count($character)-1)); 		//pick a rand type
								
				if($char_counter[$a]<($max_type+$merge)){			//if the rand type is smaller than the max occurency number
					$test = false;
					foreach ($char_counter as $type){
						if($char_counter[$a]>0 && $type==0)
							$test = true;
					}
					
					if($test != true){
						$b = rand(0,(count($character[$a])-1));		//pick a rand character 
						$new_pwd[$i] = $character[$a][$b];			//add it to the new password
						$char_counter[$a] ++;						//increment the char_counter
						$i++;										//increment the while counter
				
					}
					else{
						//echo "...Il y a encore des valeurs vides!<br/>";
					}
				}
				
			}		
			$this->password = (string)implode($new_pwd);			// cast the password array to a string
				
			return $this->password;
		}
		
		
		
		/** A revoir : 
		 * 
		 * @return number
		 */
		public function evaluate(){
			
			$type = array();
					$type[] = array('regex' => '/[a-z]/', 'occ' => 0 );
				if($this->getSymbolIsAllowed())
					$type[] = array('regex' => '/[\#\$\%\&\@\=\^\+\-\_]/', 'occ' => 0 );
				if($this->getNumberIsAllowed())
					$type[] = array('regex' => '/[0-9]/', 'occ' => 0 );
				if($this->getUppercaseIsAllowed())
					$type[] = array('regex' => '/[A-Z]/', 'occ' => 0 );
				
			$o_nb_type = 4;
			$length = strlen($this->password);
			$o_nb_occ = ceil($length / $o_nb_type);
			$point = 0;
			
			$exploded = str_split($this->password);
			$nb_type = 0;
			$occ_per_char = array();
			

			foreach($exploded as $char){
				//type evaluation (occurence)
				foreach($type as $key=>$data){
					if(preg_match($type[$key]['regex'], $char)) $type[$key]['occ']++;
				}
				
				//occurence char evaluation
				if(!in_array($char,$occ_per_char))
					$occ_per_char[] = $char;				
			}
			
			foreach ($type as $key=>$data){
				if($type[$key]['occ']>0) $nb_type ++;
			}
			
			$length_point = floor((($length-8)/8)*10);
			$length_point = ($length_point<10)?$length_point:10;
			
			$type_point = floor(($nb_type/$o_nb_type)*10);
			
			$point = (($length_point + $type_point )/20) * 100;
			
			
			return $point;
		}
		
		public function encrypt(){
			return '';
		}

		
		//==============================================
		//	SETTERS
		//==============================================
		
		
		public function setOptions(){
			$this->setAllowedSymbol();
			$this->setAllowedNumber();
			$this->setAllowedUppercase();
			$this->allowedLowercase = true;
			$this->setMaxLength();
			$this->setMinLenght();
			$this->generate();
		}
		
		public function setAllowedSymbol($allowed = false){
			$this->allowedSymbol = $allowed;
		}
		
		public function setAllowedNumber($allowed = true){
			$this->allowedNumber = $allowed;
		}
		
		public function setAllowedUppercase($allowed = true){
			$this->allowedUppercase = $allowed;
		}
		
		public function setMaxLength($length = 16){
			$this->maxLength = $length;
		}

		public function setMinLenght($length = 8){
			$this->minLength = $length;
		}
		
		public function setPassword($password){
			$this->password = $password;
		}
		
		
		
		
		//==============================================
		//	GETTERS
		//==============================================
		
		public function getSymbolIsAllowed(){
			return $this->allowedSymbol;
		}
		
		public function getNumberIsAllowed(){
			return $this->allowedNumber;
		}
		
		public function getUppercaseIsAllowed(){
			return $this->allowedUppercase;
		}
		
		public function getNbCharAccepted(){
			return $this->nbCharAccepted;
		}
		
		public function getMaxLength(){
			return $this->maxLength;
		}
	}
