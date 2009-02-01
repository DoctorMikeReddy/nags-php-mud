<?
class COMMAND_USERS
{
	var $NAME = "COMMAND_USERS";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_USERS";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_USERS(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE($this->NAME, 'SYSTEM', 'LOAD COMMAND');
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, 'SYSTEM', 'INIT COMMAND');
          return True;
     }
     function LOAD_COMMANDS()
     {
          $this->INTERPRETER->ADD_ACTION($this->NAME,'USERS', 'USERS', '', $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
	function USERS($message)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          foreach($this->PLAYER->GET_ALL_USER_IDS() as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0]):
					$this->COMMUNICATION->SEND_TO_Q($this->PLAYER->GET_USER_FIRSTNAME($user_id[0]) . ' ' . $this->PLAYER->GET_USER_LASTNAME($user_id[0]).'\n');
                    endif;
               }
          }
		$this->COMMUNICATION->WRITE_Q();
	}
}
?>