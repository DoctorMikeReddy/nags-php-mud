<?
class COMMAND_REBOOT
{
	var $NAME = "COMMAND_REBOOT";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_REBOOT";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_REBOOT(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"REBOOT", "REBOOT", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function REBOOT($message="Server rebooting for maintanance...." )
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          if(trim($message) == "")
          {
               $message="Server rebooting for maintanance....";
          }else{
               $message = trim($message);
          }
		exec("touch .reboot");
          $this->MESSAGE->SYSMESSAGE("***System Reboot: ".$message." by user ".$this->PLAYER->GET_USER_USERNAME($this->PLAYER->GET_UID_BY_SOCKET($socket)), "SYSTEM", "SHUTDOWN");
          $this->COMMUNICATION->SEND_TO_ALL("\FR***System Reboot: $message\SN\n");
          $this->SOCKET->close();
          exit;
     }
}
?>