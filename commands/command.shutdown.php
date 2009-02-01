<?
class COMMAND_SHUTDOWN
{
	var $NAME = "COMMAND_SHUTDOWN";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_SHUTDOWN";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_SHUTDOWN(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"SHUTDOWN", "SHUTDOWN", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function SHUTDOWN($message="Server shutting down for maintanance...." )
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          if(trim($message) == "")
          {
               $message="Server shutting down for maintanance....";
          }else{
               $message = trim($message);
          }
		exec("touch .shutdown");
          $this->MESSAGE->SYSMESSAGE("***System Shutdown: ".$message." by user ".$this->PLAYER->GET_USER_USERNAME($this->PLAYER->GET_UID_BY_SOCKET($socket)), "SYSTEM", "SHUTDOWN");
          $this->COMMUNICATION->SEND_TO_ALL($this->MESSAGE->ANSI_FG['RED']."***System Shutdown: " . $message.$this->MESSAGE->ANSI_SPECIAL['NORMAL']."\n");
          $this->SOCKET->close();
          exit;
     }
}
?>