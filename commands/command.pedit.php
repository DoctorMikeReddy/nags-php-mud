<?
/*============================================================================*\
|
| CLASS PEDIT
|
|  DESCRIPTION:
|		PEDIT COMMAND FOR NAGS GAMING SERVER
|
|  REQUIREMENTS:
|		NAGS. This is a core command.
|
|  USAGE:
|		PEDIT
|			This command enters the editor system and allows you to
|			edit information regarding a player who is online or in
|			the database
|
|  AUTHOR:
|		TERRY JAMES VALLADON
|
|  LICENSE:
|		Copyright (C) 2004-2009 by Terry Valladon (get-nags@terryvalladon.com)
|
|		This program is free software
|		you can redistribute it and/or modify
|		it under the terms of the GNU General Public License as published by
|		the Free Software Foundation; either version 2, or (at your option)
|		any later version.
|
|		This program is distributed in the hope that it will be useful,
|		but WITHOUT ANY WARRANTY; without even the implied warranty of
|		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|		GNU General Public License for more details.
|
|		You should have received a copy of the GNU General Public License
|		along with this program; if not, write to the Free Software
|		Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
\*============================================================================*/
class COMMAND_PEDIT
{
	var $NAME = "COMMAND_PEDIT";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_PEDIT";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
	var $PLAYER_ARRAY;
	var $user_id;
    
     function COMMAND_PEDIT(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"PEDIT", "PEDIT", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function PEDIT($target)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $target = strtoupper($target);
          $cmd = explode(" ",$target);
          if($target == "")
          {
               $user_id = $this->PLAYER->GET_UID_BY_SOCKET($socket);         
          }elseif($cmd[1] == ""){ //No last name, must be asking by username... 
               $user_id = $this->PLAYER->GET_UID_BY_USERNAME($cmd[0]);
          }else{ //not self, not username.. must be first and last name... 
               $user_id = $this->PLAYER->GET_UID_BY_FIRSTNAME_LASTNAME($cmd[0], $cmd[1]);
          }
		if($user_id != "")
		{        
	          $player_db = $this->PLAYER->GET_PLAYER_BY_UID($user_id);
	          $this->user_id = $user_id;
			$this->PLAYER_ARRAY[$socket]['user_id'] = $user_id;
			$this->PLAYER_ARRAY[$socket]['username'] = $player_db->username;
			$this->PLAYER_ARRAY[$socket]['password'] = "";
			$this->PLAYER_ARRAY[$socket]['orig_password'] = $player_db->password; //Used to see if password was changed for save
			$this->PLAYER_ARRAY[$socket]['first_name'] = $player_db->first_name;
			$this->PLAYER_ARRAY[$socket]['last_name'] = $player_db->last_name;
			$this->PLAYER_ARRAY[$socket]['email'] = $player_db->email;
			$this->PLAYER_ARRAY[$socket]['sex'] = strtoupper($player_db->sex);
			$this->PLAYER_ARRAY[$socket]['level'] = $player_db->level;
			$this->PLAYER_ARRAY[$socket]['zone'] = $player_db->zone;
			$this->PLAYER_ARRAY[$socket]['room'] = $player_db->room;
	          $this->SOCKET->set_socketinfo($socket, 'MODE', 'PLAYER_EDITOR');
	          $this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
			$this->DISPLAY_MENU();
		}else{
			$this->COMMUNICATION->SEND_TO_CHAR("No user found matching this name\n");
		}

     }
	function PEDIT_INTERPRETER($socket, $data)
	{
		$this->SYSTEM->CURRENT_SOCKET = $socket;
		$mode = $this->SOCKET->GET_SOCKETINFO($socket, 'MODE');
		$submode = $this->SOCKET->GET_SOCKETINFO($socket, 'SUBMODE');

		if($submode == "MENU"):
			switch(strtoupper(trim($data))):
				case 'A':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWNew Username: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_USERNAME');
					break;
				case 'B':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWNew Password: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_PASSWORD');
					break;
				case 'C':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWFirst Name: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_FIRSTNAME');
					break;
				case 'D':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWLast Name: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_LASTNAME');
					break;
				case 'E':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWEmail Address: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_EMAIL');
					break;
				case 'F':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWSex (M/F): \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_SEX');
					break;
				case 'G':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWLevel: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_LEVEL');
					break;
				case 'H':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWPlayer Zone: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_ZONE');
					break;
				case 'I':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWPlayer Room: \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_ROOM');
					break;
				case 'Y':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWThis will cause the GUI player flag editor to come up\n\FRNot working yet.\n\FWPress <enter> \FY");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
					break;
				case 'X':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SCPlayer Not Saved.\n");
			          $this->SOCKET->set_socketinfo($socket, 'MODE', 'PLAYING');
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', '');
          			unset($this->PLAYER_ARRAY[$socket]);
					break;
				case 'Z':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SCPlayer Saved.\n");
			          $this->SOCKET->set_socketinfo($socket, 'MODE', 'PLAYING');
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', '');
					$this->PLAYER->SET_PLAYER_USERNAME($this->user_id, $this->PLAYER_ARRAY[$socket]['username']);
					if($this->PLAYER_ARRAY[$socket]['password'] != "")
					{
						$this->PLAYER->SET_PLAYER_PASSWORD($this->user_id, $this->PLAYER_ARRAY[$socket]['password']);
					}
					$this->PLAYER->SET_PLAYER_FIRSTNAME($this->user_id, $this->PLAYER_ARRAY[$socket]['first_name']);
					$this->PLAYER->SET_PLAYER_LASTNAME($this->user_id, $this->PLAYER_ARRAY[$socket]['last_name']);
					$this->PLAYER->SET_PLAYER_SEX($this->user_id, $this->PLAYER_ARRAY[$socket]['sex']);
					$this->PLAYER->SET_PLAYER_EMAIL($this->user_id, $this->PLAYER_ARRAY[$socket]['email']);
					$this->PLAYER->SET_PLAYER_LEVEL($this->user_id, $this->PLAYER_ARRAY[$socket]['level']);
					$this->PLAYER->SET_PLAYER_ROOM($this->user_id, $this->PLAYER_ARRAY[$socket]['room']);
					$this->PLAYER->SET_PLAYER_ZONE($this->user_id, $this->PLAYER_ARRAY[$socket]['zone']);
          			unset($this->PLAYER_ARRAY[$socket]);
					break;
				default:
					$this->DISPLAY_MENU();
					break;
			endswitch;
		endif;


		// This block will be deleted after all options are working, for now it "just works"
		// (keeps mud from crashing if someone issues a bad option)
		if($submode == "SET_PLAYER_"):
			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
			$this->DISPLAY_MENU();
		endif;
		// Block above here will be deleted after all options are working, for now it "just works"
		
		
		if($submode == "SET_PLAYER_ZONE"):
			if($data >= 0 AND $data <= 9999):
				$this->PLAYER_ARRAY[$socket]['zone'] = str_pad($data, 4, '0', STR_PAD_LEFT);
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRNot a valid zone number, please enter a numeric value from 0 to 9999.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWPlayer Zone: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_ROOM"):
			if($data >= 0 AND $data <= 9999):
				$this->PLAYER_ARRAY[$socket]['room'] = str_pad($data, 4, '0', STR_PAD_LEFT);
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRNot a valid room number, please enter a numeric value from 0 to 9999.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWPlayer Room: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_USERNAME"):
			if(eregi('^[a-zA-Z0-9]{5,24}$', $data)):
				$this->PLAYER_ARRAY[$socket]['username'] = $data;
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
                    $this->COMMUNICATION->SEND_TO_CHAR('\FRInvalid username, may only contain upper and lower case characters and numbers.\n');
				$this->COMMUNICATION->SEND_TO_CHAR('\FRUsername must be between 5 and 24 characters long.\n');
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWNew Username: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_PASSWORD"):
			if(eregi('^[a-zA-Z0-9]{5,24}$', $data)):
				$this->PLAYER_ARRAY[$socket]['password1'] = $data;
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_REPASSWORD');
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWRetype Password: \FY");
			else:
                    $this->COMMUNICATION->SEND_TO_CHAR('\FRInvalid password, may only contain upper and lower case characters and numbers.\n');
				$this->COMMUNICATION->SEND_TO_CHAR('\FRPassword must be between 5 and 24 characters long.\n');
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWNew Password: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_REPASSWORD"):
			if($this->PLAYER_ARRAY[$socket]['password1'] == $data):
				$this->PLAYER_ARRAY[$socket]['password'] = $data;
				unset($this->PLAYER_ARRAY[$socket]['password1']);
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'SET_PLAYER_PASSWORD');
                    $this->COMMUNICATION->SEND_TO_CHAR('\FRPasswords do not match, please try again\n');
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWNew Password: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_FIRSTNAME"):
			if(eregi('^[a-zA-Z]{1,24}$', $data)):
				$this->PLAYER_ARRAY[$socket]['first_name'] = $data;
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRNot a valid first name, please enter a use only upper and lower case letters.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWFirst Name: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_LASTNAME"):
			if(eregi('^[a-zA-Z]{1,24}$', $data)):
				$this->PLAYER_ARRAY[$socket]['last_name'] = $data;
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRNot a valid last name, please enter a use only upper and lower case letters.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWLast Name: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_SEX"):
			if(eregi('^[MFmf]{1}$', $data)):
				$this->PLAYER_ARRAY[$socket]['sex'] = strtoupper($data);
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRUnknown sex, please only use M or F.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWSex (M/F): \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_EMAIL"):
			if($this->validate_email($data)):
				$this->PLAYER_ARRAY[$socket]['email'] = $data;
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRNot a valid email address, please enter a valid email address.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWEmail Address: \FY");
			endif;
		endif;
		if($submode == "SET_PLAYER_LEVEL"):
			if($data >= 0 AND $data <= 9999):
				$this->PLAYER_ARRAY[$socket]['level'] = $data;
     			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
				$this->DISPLAY_MENU();
			else:
				$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRNot a valid level, please enter a numeric value from 0 to 9999.\n");
		          $this->COMMUNICATION->SEND_TO_CHAR("\SN\FWLevel: \FY");
			endif;
		endif;


	}
	function DISPLAY_MENU()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $this->COMMUNICATION->SEND_TO_CHAR("\SC\FW================================================================================\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FWUser ID: \FR".$this->PLAYER_ARRAY[$socket]['user_id']."\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRA\FW) Username: \FY".str_pad($this->PLAYER_ARRAY[$socket]['username'], 25, " ")."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRB\FW) Password: \FY".str_pad("**HIDDEN HASH**", 25, " ")."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRC\FW) First Name: \FY".str_pad($this->PLAYER_ARRAY[$socket]['first_name'], 25, " ")."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRD\FW) Last Name: \FY".str_pad($this->PLAYER_ARRAY[$socket]['last_name'], 25, " ")."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRE\FW) Email: \FY".str_pad($this->PLAYER_ARRAY[$socket]['email'], 50, " ")."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRF\FW) Sex: \FY".$this->PLAYER_ARRAY[$socket]['sex']."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRG\FW) Level: \FY".$this->PLAYER_ARRAY[$socket]['level']."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRH\FW) Zone: \FY".$this->PLAYER_ARRAY[$socket]['zone']."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRI\FW) Room: \FY".$this->PLAYER_ARRAY[$socket]['room']."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FW================================================================================\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRY\FW)\FY Edit Player Flags (\FRChanges will be lost\FY)\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRX\FW)\FY Cancel\t\t\FRZ\FW)\FY Save\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FW================================================================================\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRPEDIT\FY > \FW");
	}
//=========================
// Helper Functions
//=========================
	function validate_email($email)
	{
		$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		$valid = 0;
		if (eregi($regexp, $email))
		{
			$valid = 1;
		} else {
			$valid = 0;
		}
		return $valid;
	}
}
?>