<?
class COMMAND_RLIST
{
	var $NAME = "COMMAND_RLIST";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_RLIST";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_RLIST(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"RLIST", "RLIST", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function RLIST($zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
     	if($zone == "")
     	{
     		$this->COMMUNICATION->SEND_TO_CHAR("Command Syntax: rlist <zone><ALL>\n");
     	}elseif(strtoupper($zone) == "ALL"){
			$room_list = $this->DATABASE->GET_RESULTS("select zone_id, room_id, room_name from rooms order by zone_id desc, room_id desc;");
          	$this->COMMUNICATION->SEND_TO_CHAR("Zone\tRoom\tTitle\n");
          	$this->COMMUNICATION->SEND_TO_CHAR("----\t----\t-----\n");
          	if(count($room_list) != 0)
          	{
		          foreach($room_list as $room)
		          {
		          	$this->COMMUNICATION->SEND_TO_CHAR($room[0]."\t".$room[1]."\t".$room[2]."\n");
		          }
			}else{
	          	$this->COMMUNICATION->SEND_TO_CHAR("There are no rooms in the world!\n");
			}
          	$this->COMMUNICATION->SEND_TO_CHAR("----\t----\t-----\n");
          	$this->COMMUNICATION->SEND_TO_CHAR("\t".count($room_list)." rooms found.\n");
          }else{
			$room_list = $this->DATABASE->GET_RESULTS("select zone_id, room_id, room_name from rooms where zone_id='".$zone."' order by room_id desc;");
          	$this->COMMUNICATION->SEND_TO_CHAR("Zone\tRoom\tTitle\n");
          	$this->COMMUNICATION->SEND_TO_CHAR("----\t----\t-----\n");
          	if(count($room_list) != 0)
          	{
		          foreach($room_list as $room)
		          {
		          	$this->COMMUNICATION->SEND_TO_CHAR($room[0]."\t".$room[1]."\t".$room[2]."\n");
		          }
			}else{
	          	$this->COMMUNICATION->SEND_TO_CHAR("There are no rooms in this zone.\n");
			}
          	$this->COMMUNICATION->SEND_TO_CHAR("----\t----\t-----\n");
          	$this->COMMUNICATION->SEND_TO_CHAR("\t".count($room_list)." rooms found.\n");
          }
     }  
}
?>