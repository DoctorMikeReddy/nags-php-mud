<?
/*============================================================================*\
|
| CLASS EDITOR
|
|  DESCRIPTION:
|		TEXT EDITOR MODULE FOR NAGS GAMING SERVER
|
|  REQUIREMENTS:
|		NAGS. This is a core module.
|
|  USAGE:
|
|  AUTHOR:
|		TERRY JAMES VALLADON
|
|  LICENSE:
|		Copyright (C) 2007-2010 by Terry Valladon (get-nags@terryvalladon.com)
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
class EDITOR
{
     var $NAME = "EDITOR";
     var $ENABLED;
	var $BUFFER;
	
     function EDITOR(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE($this->NAME, 'SYSTEM', 'LOAD MODULE');
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, 'SYSTEM', 'INIT MODULE');
          return True;
     }
	function INIT_EDITOR($buffer)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$this->SOCKET->set_socketinfo($socket, 'OLD_MODE', $this->SOCKET->GET_SOCKETINFO($socket, 'MODE'));
		$this->SOCKET->set_socketinfo($socket, 'OLD_SUBMODE', $this->SOCKET->GET_SOCKETINFO($socket, 'SUBMODE'));
		$this->SOCKET->set_socketinfo($socket, 'MODE', 'EDITOR');
		$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
		$this->BUFFER[$socket]['OLD_BUFFER'] = $buffer;
		$this->BUFFER[$socket]['NEW_BUFFER'] = $buffer;
		$this->DISPLAY_MENU();
	}
	function EDITOR_INTERPRETER($socket, $data)
	{
		$this->SYSTEM->CURRENT_SOCKET = $socket;
		$mode = $this->SOCKET->GET_SOCKETINFO($socket, 'MODE');
		$submode = $this->SOCKET->GET_SOCKETINFO($socket, 'SUBMODE');

		if($submode == "MENU")
			switch($data):
				case '\?':
					$this->COMMUNICATION->SEND_TO_CHAR("\SCEditor Commands:\SN\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\? Lists Help\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\L Lists the text in the buffer\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\# Lists the text in the buffer with line numbers.\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\A Append text to the end of a line. (You will be asked which line to edit).\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\D Deletes a line (you will be asked which line to delete).\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\R Replace TextA with TextB, You will be asked for both peices of text.\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\F Format Buffer. This command will remove all new lines and split text at 80 chars.\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\C Clears the buffer and starts anew.\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\X Cancel all changes and exit the editor.\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\Z Save the buffer and exit the editor.\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\FREach command must be on a NEW LINE.\n\n");
					$this->COMMUNICATION->SEND_TO_CHAR("\FYText in buffer:\SN \n\SN");
					$this->COMMUNICATION->SEND_TO_CHAR_RAW($this->BUFFER[$socket]['NEW_BUFFER']);
					$this->COMMUNICATION->SEND_TO_CHAR("\N\SN\FR: \SN");
					break;
				case '\z':
				case '\Z':
					$this->SOCKET->set_socketinfo($socket, 'MODE', $this->SOCKET->GET_SOCKETINFO($socket, 'OLD_MODE'));
					$this->SOCKET->set_socketinfo($socket, 'SUBMODE', $this->SOCKET->GET_SOCKETINFO($socket, 'OLD_SUBMODE'));
					$this->SOCKET->set_socketinfo($socket, 'OLD_MODE', '');
					$this->SOCKET->set_socketinfo($socket, 'OLD_SUBMODE', '');
					$this->SOCKET->set_socketinfo($socket, 'EDITOR_RETURN', 'true');
					$this->SOCKET->set_socketinfo($socket, 'EDITOR_BUFFER', $this->BUFFER[$socket]['NEW_BUFFER']);
					$this->COMMUNICATION->SEND_TO_CHAR("\nEdit buffer saved, press <enter>.");
					break;
				case '\f':
				case '\F':
					$this->BUFFER[$socket]['NEW_BUFFER'] = wordwrap(str_replace("\n", "", $this->BUFFER[$socket]['NEW_BUFFER']), 80, "\n", true);;
					$this->COMMUNICATION->SEND_TO_CHAR("\SC\SN");
					$this->COMMUNICATION->SEND_TO_CHAR_RAW($this->BUFFER[$socket]['NEW_BUFFER']);
					$this->COMMUNICATION->SEND_TO_CHAR("\n\SN\FR: \SN");
					break;
				case '\l':
				case '\L':
					$this->COMMUNICATION->SEND_TO_CHAR("\SC\SN");
					if($this->BUFFER[$socket]['NEW_BUFFER'] != "")
					{
						$this->COMMUNICATION->SEND_TO_CHAR_RAW($this->BUFFER[$socket]['NEW_BUFFER']);
					}else{
						$this->COMMUNICATION->SEND_TO_CHAR("Buffer is empty.\n");
					}
					$this->COMMUNICATION->SEND_TO_CHAR("\n\SN\FR: \SN");
					break;
				case '\#':
					$this->COMMUNICATION->SEND_TO_CHAR("\SC");
					$temp_array = explode("\n", $this->BUFFER[$socket]['NEW_BUFFER']);
					$x = 0;
					foreach($temp_array as $text)
					{
						if($text != "")
						{
							$this->COMMUNICATION->SEND_TO_CHAR("\FR".$x.":\SN");
							$this->COMMUNICATION->SEND_TO_CHAR_RAW($text);
							$this->COMMUNICATION->SEND_TO_CHAR("\n");
							$x++;
						}else{
							$this->COMMUNICATION->SEND_TO_CHAR("\FR".$x.": \SN");
							$this->COMMUNICATION->SEND_TO_CHAR("\n");
							$x++;
						}
					}
					$this->COMMUNICATION->SEND_TO_CHAR("\SN\FR:\SN");
					break;
				case '\d':
				case '\D':
					$this->COMMUNICATION->SEND_TO_CHAR("\SC");
					$temp_array = explode("\n", $this->BUFFER[$socket]['NEW_BUFFER']);
					$x = 0;
					foreach($temp_array as $text)
					{
						if($text != "")
						{
							$this->COMMUNICATION->SEND_TO_CHAR("\FR".$x.":\SN");
							$this->COMMUNICATION->SEND_TO_CHAR_RAW($text);
							$this->COMMUNICATION->SEND_TO_CHAR("\n");
							$x++;
						}else{
							$this->COMMUNICATION->SEND_TO_CHAR("\FR".$x.":\SN");
							$this->COMMUNICATION->SEND_TO_CHAR("\n");
							$x++;
						}
					}
					$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'LINE_DELETE');
					$this->COMMUNICATION->SEND_TO_CHAR("\SN\FRWhich line do you wish to delete?: \FY");
					break;
				case '\c':
				case '\C':
					$this->COMMUNICATION->SEND_TO_CHAR("\SCBuffer Clear.\n");
					$this->BUFFER[$socket]['NEW_BUFFER'] = "";
					$this->COMMUNICATION->SEND_TO_CHAR("\SN\FR: \SN");
					break;
				case '\x':
				case '\X':
					$this->SOCKET->set_socketinfo($socket, 'MODE', $this->SOCKET->GET_SOCKETINFO($socket, 'OLD_MODE'));
					$this->SOCKET->set_socketinfo($socket, 'SUBMODE', $this->SOCKET->GET_SOCKETINFO($socket, 'OLD_SUBMODE'));
					$this->SOCKET->set_socketinfo($socket, 'OLD_MODE', '');
					$this->SOCKET->set_socketinfo($socket, 'OLD_SUBMODE', '');
					$this->SOCKET->set_socketinfo($socket, 'EDITOR_RETURN', 'true');
					$this->SOCKET->set_socketinfo($socket, 'EDITOR_BUFFER', $this->BUFFER[$socket]['OLD_BUFFER']);
					$this->COMMUNICATION->SEND_TO_CHAR("\nEdit canceled, press <enter>.");
					break;
				default:
					$this->COMMUNICATION->SEND_TO_CHAR("\SN\FR: \SN");
					$this->BUFFER[$socket]['NEW_BUFFER'] .= trim($data)."\n";
					break;
			endswitch;

		if($submode == "LINE_DELETE")
			switch(trim($data)):
				case '':
					$this->COMMUNICATION->SEND_TO_CHAR("\SC\SN");
					$this->COMMUNICATION->SEND_TO_CHAR_RAW($this->BUFFER[$socket]['NEW_BUFFER']);
					$this->COMMUNICATION->SEND_TO_CHAR("\SN\FR: \SN");
					$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
					break;
				default:
					$this->COMMUNICATION->SEND_TO_CHAR("\SC\FRLine Deleted, Current Buffer:\n");
					$temp_array = explode("\n", $this->BUFFER[$socket]['NEW_BUFFER']);
					unset($temp_array[trim($data)]);
					$this->BUFFER[$socket]['NEW_BUFFER'] = implode("\n", $temp_array);
					$x = 0;
					foreach($temp_array as $text)
					{
						if($text != "")
						{
							$this->COMMUNICATION->SEND_TO_CHAR("\FR".$x.":\SN");
							$this->COMMUNICATION->SEND_TO_CHAR_RAW($text);
							$this->COMMUNICATION->SEND_TO_CHAR("\n");
							$x++;
						}else{
							$this->COMMUNICATION->SEND_TO_CHAR("\FR".$x.":\SN");
							$this->COMMUNICATION->SEND_TO_CHAR("\n");
							$x++;
						}
					}
					$this->COMMUNICATION->SEND_TO_CHAR("\SN\FR: \SN");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
					break;
			endswitch;
	}
	function DISPLAY_MENU()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$this->COMMUNICATION->SEND_TO_CHAR("\SC\FWWelcome to the \FYOnline Editor\FW, for a list of commands type \FY\?\FW\SN\n");
		$this->COMMUNICATION->SEND_TO_CHAR_RAW($this->BUFFER[$socket]['NEW_BUFFER']."\n");
		$this->COMMUNICATION->SEND_TO_CHAR("\FR: \SN");
	}
}
?>