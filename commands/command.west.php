<?
class COMMAND_WEST
{
	var $NAME = "COMMAND_WEST";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_WEST(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"WEST", "WEST", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          $this->INTERPRETER->ADD_ALIAS("W", "WEST");
          return True;
     }
     function WEST()
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$this->MOVEMENT->GO_THROUGH_EXIT("WE");
     }  
}
?>