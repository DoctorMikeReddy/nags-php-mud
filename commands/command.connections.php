<?
class COMMAND_CONNECTIONS
{
	var $NAME = "COMMAND_CONNECTIONS";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_CONNECTIONS";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_CONNECTIONS(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"CONNECTIONS", "CONNECTIONS", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function CONNECTIONS()
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          foreach($this->SOCKET->clients as $client)
          {
               if(isset($this->DATABASE->DB[$client]))
               {
                    $this->COMMUNICATION->SEND_TO_CHAR("\FY".str_pad(str_replace("Resource id #", "", $client), 7, " ")."".str_pad($this->SOCKET->get_socketinfo($client, 'ip'), 18, " ")."".str_pad($this->SOCKET->get_socketinfo($client, 'USERNAME'), 28, " ")."".$this->SOCKET->get_socketinfo($client, 'USER_ID')."\SN\n");
			}else{
                    $this->COMMUNICATION->SEND_TO_CHAR("\FY".str_pad(str_replace("Resource id #", "", $client), 7, " ")."\SN\n");
			}
          }
          return;
     }  
}
?>