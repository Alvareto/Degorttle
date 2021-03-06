<?php
    
    /**
     *	Army class
     * 
     *	@author Ivan Grgurina <ivan.grgurina@gmail.com>
     */
    class Army
    {
        /**
         *	A public variable
         *
         *	@var integer current number of soldiers
         */
        public $_numOfSoldiers;
    
        /**
         *	A public variable
         *
         *	@var array list of all active soldiers in this army
         */
        public $_soldiers = array();
    
        /**
         *	A private variable
         *
         *	@var string the official name of the army, in this case - country
         */
        private $_name;
    
        /**
         *	A private variable
         *
         *	@var array counts the number of soldier types
         */
        private $_stats;
    
        /**
         *	Creates army
         *
         *	Sets name, initializes stats-only variables, creates given number $num of random soldiers.
         *
         * 	@param integer $num a number of soldiers to be added into army
         * 	@return void
         */
        public function __construct($num)
        {
            $this->setName();
    
            $this->_stats = array(
                                "tank" => 0,
                                "hellicopter" => 0,
                                "airplane" => 0,
                                "ship" => 0,
                                "soldier" => 0
                            );
            echo "<br />Creating army with " . $num . " soldiers.. <br />" . PHP_EOL;
            $this->_numOfSoldiers = $num;
            for ($i = 0; $i < $num; $i++)
            {
                $this->addSoldier();
            }
        }
    
        /**
         *	Makes Army class printable
         *
         *	@return string basic info about army
         */
        public function __toString()
        {
            return "<b>" . $this->_name . "</b> => <br />Tanks: " . $this->_stats["tank"] . PHP_EOL .
            "<br />Aircrafts: " . $this->_stats["airplane"] . PHP_EOL .
            "<br />Helicopters: " . $this->_stats["hellicopter"] . PHP_EOL .
            "<br />Ships: " . $this->_stats["ship"] . PHP_EOL .
            "<br />Soldiers: " . $this->_stats["soldier"] . "<br />" . PHP_EOL;
        }    
    
        /* SOLDIER METHODS */
    
        /**
         *	Adds one random soldier to army
         *
         *	Chances for certain type of soldier:
         *	    soldier:    80%
         *	    tank:       20%    
         *	    airforce:    5%
         *	    helicopter: 10%
         *	    navy:        5%
         *
         *	@return void
         */
        private function addSoldier()
        {
            switch(rand(0, 19))
            {
                case 0: { array_push($this->_soldiers, new Navy($this)); $this->_stats["ship"]++; break; }
                case 1: 
                case 2: { array_push($this->_soldiers, new Helicopter($this)); $this->_stats["hellicopter"]++; break; }
                case 3:
                case 4: 
                case 5:
                case 6: { array_push($this->_soldiers, new Tank($this)); $this->_stats["tank"]++; break; }
                case 7: { array_push($this->_soldiers, new Airforce($this)); $this->_stats["airplane"]++; break; }
                default: { array_push($this->_soldiers, new Soldier($this)); $this->_stats["soldier"]++; break; }
            }
        }
    
        /**
         *	Removes soldier from the army when he dies
         *
         *	Using array_splice reorders the index keys of the $_soldiers array nicely, which is required for later features.
         *	The $_numOfSoldiers variable is also decreased by one.
         *
         *	@param Soldier $soldier dead soldier that has to be removed from army (and come back to life 3 days later maybe) :P
         *	@return void
         */
        public function removeSoldier($soldier)
        {
            $this->_numOfSoldiers--;
            if($this->_numOfSoldiers < 5)
            {
                echo "<br /><br /><br /><b>";
                echo implode("  <br/>", $this->_soldiers);
                echo "</b>";
    
            }
            array_splice($this->_soldiers, array_search($soldier, $this->_soldiers), 1);            
        }
    
        /* BATTLE METHODS */
    
        /**
         *	Function for army that is attacking the $defender army
         *
         *	First, the attack() method is being called upon every soldier.
         *	The damage dealt by that soldier is then inflicted to defending army.
         *
         *	@param Battle $battle current ongoing battle
         *	@param Army $defender defending army
         *	@return void
         */
        public function fight($battle, $defender)
        {
            foreach($this->_soldiers as $soldier)
            {
                $soldier->attack($battle);
                $defender->inflictDamage($soldier->_damage, $soldier->_affected);
            }
        }
    
        /**
         *	Function for army that is defending itself
         *
         *	Deals damage appropriately to members of the defending army.
         *	If the number of affected soldiers $num (by attack) is higher than current number of soldiers in defence,
         *	it's being lowered to that number, thus dealing more damage, but to less targets.
         *
         *	@param float $_damage amount of damage dealt by attack
         *	@param int $num number of targets affected by attack
         *	@return void
         */
        public function inflictDamage($_damage, $num)
        {
            $count = count($this->_soldiers);
            if($count < $num)
            {
                $num = $count;
            }
            $single = $_damage / $num; // damage to a single target
            //echo "<i>Total damage to " . $num . " targets will be </i>" . $_damage . " .<br />";
    
            for($i = 0; $i < $num; $i++)
            {
                $this->_soldiers[$i]->defend($single); // this is why it's important ;)
            }
        }
    
        /**
         *	Checks whether army is defeated
         *
         *	Which actually means checking whether the number of soldiers reached zero.
         *
         *	@return boolean has this army lost the battle?
         */
        public function isDefeated()
        {            
            return $this->_numOfSoldiers > 0 ? FALSE : TRUE;
        }
    
        /* NAME METHODS */
    
        /**
         *	Sets official name for the army
         *
         *	@return void
         */
        private function setName()
        {
            $countries = array('Croatia', 'Serbia', 'Slovenia', 'Italia', 'England', 'Scotland', 'Spain', 'Portugal', 'Mexico', 'Brazil', 'Chile', 'Argentina', 'China');
            $this->_name = $countries[rand(0, count($countries) - 1)];
        }
    
        /**
         *	Gets official name of the army
         *
         *	@return string name of the army
         */
        public function getName()
        {
            return $this->_name;
        }

    }
    
    echo "Loading armies... <br />" . PHP_EOL; // just making it fancy
?>
