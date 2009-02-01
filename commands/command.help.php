<?
class COMMAND_HELP
{
	var $NAME = "COMMAND_HELP";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_HELP(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"HELP", "HELP", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          $this->INTERPRETER->ADD_ALIAS("?", "HELP");
          return True;
     }
     function HELP($target)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		if($target == "")
		{
			$target = "help";
		}
		$result = $this->DATABASE->GET_ROW("select topic, descr, see_also from help where topic='".$target."'");
		if($this->DATABASE->NUM_ROWS() == 1)
		{
			$this->COMMUNICATION->SEND_TO_CHAR("\FYHelp Topic: \FW".strtoupper($result->topic)."\SN\n");
			$this->COMMUNICATION->SEND_TO_CHAR("\FW".$result->descr."\SN\n");
			$this->COMMUNICATION->SEND_TO_CHAR("\FRSee Also: \FY".strtoupper($result->see_also)."\SN\n");
		}else{
			$this->COMMUNICATION->SEND_TO_CHAR("\FWNo help was found on that subject, please try another subject.\n");
		}
     }
}
?>