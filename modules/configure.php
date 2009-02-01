<?
/*============================================================================*\
|
| CLASS CONFIGURE
|
|  DESCRIPTION:
|		CONFIGURATION SYSTEM MODULE FOR NAGS GAMING SERVER
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
class CONFIGURE
{
     var $ENABLED;
          /*   ENABELED = bool
               Denotes the class is active and available
          */
     var $CONFIG = array();
     var $CONFIG_FILE;
          
     function CONFIGURE(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE("CONFIGURE", "SYSTEM", "LOAD MODULE");
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "INIT MODULE");
          return True;
     }
     function CONFIG_LOAD($filename)
     {
          $this->CONFIG_FILE = $filename;
          if (file_exists($this->CONFIG_FILE))
          {
               include_once($this->CONFIG_FILE);
               
               $this->CONFIG['DB_HOST'] = $configarray['DB_HOST'];
               $this->CONFIG['DB_USERNAME'] = $configarray['DB_USERNAME'];
               $this->CONFIG['DB_PASSWORD'] = $configarray['DB_PASSWORD'];
               $this->CONFIG['DB_DATABASE'] = $configarray['DB_DATABASE'];
               $this->CONFIG['IP_ADDRESS'] = $configarray['IP_ADDRESS'];
               $this->CONFIG['SERVER_PORT'] = $configarray['SERVER_PORT'];
               $this->CONFIG['SERVER_NAME'] = $configarray['SERVER_NAME'];

               if(!isset($this->CONFIG['DB_HOST']))
               {
                    $this->CONFIG_CREATE();
               }
          }else{
               $this->CONFIG_CREATE();
          }
          if($this->CONFIG['DB_PASSWORD']=="DEFAULT")
          {
               $this->MESSAGE->SYSMESSAGE("DEFAULT CONFIG VALUES NOT CHANGED!", "SYSTEM", "FATAL ERROR");
               exit;
          }
     }

     function CONFIG_SAVE()
     {
          if(!$this->file_put_contents($this->CONFIG_FILE, $this->dump_array($this->CONFIG)))
          {
               $this->MESSAGE->SYSMESSAGE("COULD NOT SAVE CONFIG FILE", "SYSTEM", "FATAL ERROR");
               return False;
          }
          return True;
     }

     function CONFIG_CREATE()
     {
//          echo($this->ANSI_FG['GREEN']."SYSTEM:\t".$this->ANSI_FG['WHITE']."(".$this->ANSI_FG['YELLOW']."WARNING    ".$this->ANSI_FG['WHITE'].")".$this->ANSI_FG['YELLOW']."\tCONFIG FILE NOT FOUND, CREATING\n".$this->ANSI_SPECIAL['NORMAL']);
          $this->MESSAGE->SYSMESSAGE("CONFIG FILE NOT FOUND, CREATING", "SYSTEM", "WARNING");

          $this->CONFIG['DB_USERNAME'] = "DEFAULT";
          $this->CONFIG['DB_PASSWORD'] = "DEFAULT";
          $this->CONFIG['DB_DATABASE'] = "DEFAULT";
          $this->CONFIG['DB_HOST'] = "DEFAULT";
          $this->CONFIG['IP_ADDRESS'] = "127.0.0.1";
          $this->CONFIG['SERVER_PORT'] = 4000;
          $this->CONFIG['SERVER_NAME'] = "My Mud";
          
          $this->CONFIG_SAVE();
     }

     function CONFIG_CLEAR()
     {
          unset($this->CONFIG);
          $this->CONFIG = array();
     }

     function file_put_contents($filename, $content)
     {
          $RETURNCODE = True;
          if($RETURNCODE = True)
          {
               if (is_writable($filename))
               {
                    $RETURNCODE = True;
               }else{
                    $RETURNCODE = False;
               }
          }
          if($RETURNCODE== True)
          {
               if (!$handle = fopen($filename, 'w'))
               {
                    $RETURNCODE = False;
               }
          }
          if($RETURNCODE== True)
          {
               if (fwrite($handle, $content) === FALSE)
               {
                    $RETURNCODE = False;
               }
          }
          if($RETURNCODE== True)
          {
               fclose($handle);
          }
          return $RETURNCODE;
     }

     //This one gives you output you can copy and paste back into your PHP code to recreate the array.
     function dump_array($array, $tabs=1)
     {
          $code = "<? \$configarray = array(\n";
          foreach($array as $key => $val)
          {
               $code .= str_repeat("\t", $tabs )."'$key' => ";
               if( is_array( $val))
               {
                    $code .= dump_array($array,$tabs+1);
               }else{
                    $code .= "'$val',\n";
               }
          }
          $code .= ');?>';
          return $code;
     }
}
?>