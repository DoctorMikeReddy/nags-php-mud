<?
class COMMAND_PSTAT
{
	var $NAME = "COMMAND_PSTAT";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_PSTAT";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_PSTAT(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "LOAD COMMAND");
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "INIT COMMAND");
          return True;
     }
     function LOAD_COMMANDS()
     {
          $this->INTERPRETER->ADD_ACTION($this->NAME,"PSTAT", "PLAYER_STAT", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function PLAYER_STAT($target)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $target = strtoupper($target);
          $cmd = explode(" ",$target);
          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
          if($target == "")
          {
               $user_id = $this->PLAYER->GET_UID_BY_SOCKET($socket);         
          }elseif($cmd[1] == ""){ //No last name, must be asking by username... 
               $user_id = $this->PLAYER->GET_UID_BY_USERNAME($cmd[0]);
          }else{ //not self, not username.. must be first and last name... 
               $user_id = $this->PLAYER->GET_UID_BY_FIRSTNAME_LASTNAME($cmd[0], $cmd[1]);
          }
		if($user_id <>"")
		{        
			$this->COMMUNICATION->SEND_TO_Q("User ID:\t".$user_id."\n");
			$this->COMMUNICATION->SEND_TO_Q("Username:\t".$this->PLAYER->GET_USER_USERNAME($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Password:\t**HIDDEN HASH**\n");
			$this->COMMUNICATION->SEND_TO_Q("First Name:\t".$this->PLAYER->GET_USER_FIRSTNAME($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Last Name:\t".$this->PLAYER->GET_USER_LASTNAME($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Email Address:\t".$this->PLAYER->GET_USER_EMAIL($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Sex:\t\t".$this->PLAYER->GET_USER_SEX($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Level:\t\t".$this->PLAYER->GET_USER_LEVEL($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Zone:\t\t".$this->PLAYER->GET_USER_ZONE($user_id)."\n");
			$this->COMMUNICATION->SEND_TO_Q("Room:\t\t".$this->PLAYER->GET_USER_ROOM($user_id)."\n");
	          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
	          $this->COMMUNICATION->SEND_TO_Q("Flags:\n");
	          $this->COMMUNICATION->SEND_TO_Q($this->PLAYER->GET_USER_FLAGS($user_id));
		}else{
			$this->COMMUNICATION->SEND_TO_Q("No user found matching this name\n");
		}
          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
          $this->COMMUNICATION->WRITE_Q();
     }
}
?>