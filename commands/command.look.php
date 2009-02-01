<?
class COMMAND_LOOK
{
	var $NAME = "COMMAND_LOOK";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_LOOK(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"LOOK", "LOOK", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          $this->INTERPRETER->ADD_ALIAS("L", "LOOK");
          return True;
     }
     function LOOK($target = "")
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          if($target == "")
          {
               $user_id = $this->PLAYER->GET_UID_BY_SOCKET($socket);         
               $this->INFORMATIVE->LOOK_AT_ROOM($this->PLAYER->GET_USER_ZONE($user_id),$this->PLAYER->GET_USER_ROOM($user_id));
          }
     }
}
?>