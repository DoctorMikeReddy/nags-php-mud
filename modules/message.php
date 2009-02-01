<?
/*============================================================================*\
|
| CLASS MESSAGE
|
|  DESCRIPTION:
|		MAIN CONSOLE AND SYSLOG LOGGING MODULE FOR NAGS GAMING SERVER
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
class MESSAGE
{
     var $NAME = "MESSAGE";
     var $ENABLED;
          /*   ENABELED = bool
               Denotes the class is active and available
          */


     function MESSAGE()
     {
          $this->SYSMESSAGE("MESSAGE", "SYSTEM", "LOAD MODULE");
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->SYSMESSAGE($this->NAME, "SYSTEM", "INIT MODULE");
          return True;
     }
     function SYSMESSAGE($message, $type="SYSTEM", $level="MESSAGE")
     {
          switch($level)
          {
               case "LOAD MODULE":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "LOAD PLUGIN":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "LOAD COMMAND":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "INIT MODULE":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "INIT PLUGIN":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "INIT COMMAND":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "NOTICE":
               	$text = "\FG$type:\t\FW(\FW$level\FW)\FW\t$message\n\SN";
                    systemlog($text);
                    break;
               case "CONNECT":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "DISCONNECT":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "SHUTDOWN":
               	$text = "\FR$type:\t\FW(\FR$level\FW)\FR\t$message\n\SN";
                    systemlog($text);
                    break;
               case "WARNING":
                    break;
               case "ERROR":
                    break;
               case "FATAL ERROR":
               	$text = "\FR$type:\t\FW(\FR$level\FW)\FR\t$message\n\SN";
                    systemlog($text);
                    break;
               case "MESSAGE":
               	$text = "\FG$type:\t\FW(\FG$level\FW)\FG\t$message\n\SN";
                    systemlog($text);
                    break;
               case "BLANK":
                    systemlog("\n");
                    break;
               default:
                    break;
          }
     }
}
?>