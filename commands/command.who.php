<?
class COMMAND_WHO
{
	var $NAME = "COMMAND_WHO";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_WHO(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"WHO", "WHO", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
	function WHO($message)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$this->COMMUNICATION->SEND_TO_Q("+".str_pad("",27,"-=")."Players Online".str_pad("",27,"-=")."+\n");
          foreach($this->PLAYER->GET_ALL_USER_IDS() as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, "USER_ID") == $user_id[0])
                    {
					$this->COMMUNICATION->SEND_TO_Q("| ". $this->PLAYER->GET_USER_FIRSTNAME($user_id[0]) . " " . $this->PLAYER->GET_USER_LASTNAME($user_id[0]));
					$length = strlen($this->PLAYER->GET_USER_FIRSTNAME($user_id[0]) . " " . $this->PLAYER->GET_USER_LASTNAME($user_id[0]));
					$middle_length = strlen(substr($this->ROOMS->GET_ROOM_TITLE($this->PLAYER->GET_USER_ZONE($user_id[0]),$this->PLAYER->GET_USER_ROOM($user_id[0])),0,70-4-$length-8)."...");
					$this->COMMUNICATION->SEND_TO_Q(str_pad("", 70-$middle_length-$length-4, " "));
					$this->COMMUNICATION->SEND_TO_Q(substr($this->ROOMS->GET_ROOM_TITLE($this->PLAYER->GET_USER_ZONE($user_id[0]),$this->PLAYER->GET_USER_ROOM($user_id[0])),0,70-8-$length-4)."...");
					$this->COMMUNICATION->SEND_TO_Q(" |\n");
                    }
               }
          }
		$this->COMMUNICATION->SEND_TO_Q("+".str_pad("",68,"-=")."+\n");
		$this->COMMUNICATION->WRITE_Q();
	}
}
?>