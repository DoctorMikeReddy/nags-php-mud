<?
class COMMAND_ZLIST
{
	var $NAME = "COMMAND_ZLIST";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_ZLIST";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_ZLIST(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"ZLIST", "ZLIST", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function ZLIST($zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
     	if($zone <> "")
     	{
     		$this->COMMUNICATION->SEND_TO_CHAR("Command Syntax: ZLIST\n");
     	}else{
			$zone_list = $this->DATABASE->GET_RESULTS("select zone_id, zone_name from zones order by zone_id desc;");
          	$this->COMMUNICATION->SEND_TO_CHAR("Zone\tTitle\n");
          	$this->COMMUNICATION->SEND_TO_CHAR("----\t-----\n");
          	if(count($zone_list) != 0)
          	{
		          foreach($zone_list as $zone)
		          {
		          	$this->COMMUNICATION->SEND_TO_CHAR($zone[0]."\t".$zone[1]."\n");
		          }
			}else{
	          	$this->COMMUNICATION->SEND_TO_CHAR("There are no zones in the world!\n");
			}
          	$this->COMMUNICATION->SEND_TO_CHAR("----\t-----\n");
          	$this->COMMUNICATION->SEND_TO_CHAR("\t".count($zone_list)." zones found.\n");
          }
     }  
}
?>