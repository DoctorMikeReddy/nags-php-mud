<?
/* Most of the code bellow was taken from http://kacke.de/php_samples/source.php?f=socke.inc but modified to work with the NAGS core system
and fixed a few minor bugs, all credit should go to the origional developer because he was the man :D  -- tvalladon*/
/*============================================================================*\
|
| CLASS SOCKE
|
|
|  multi socket handle 
|  OStypes Unix/Linux
|  php 4.3.x with sockets
|
|  usage:
|        $socket = new socke();
|        if(!$io = $socket->listen(host,port)){
|            //error
|        }
|        Creates a listen sock at host:port
|        If host == "" it uses "0.0.0.0"
|        $socket == this class
|       $io is the socket-resource from socket_create();|
|        
|        Samplecode I :
|        Loop and accept new connex. Its like a partyline-server
|
|        while(1){
|            usleep(200);
|           foreach($socket->can_read() as $sock){
|                if($io == $sock){
|                    if($mysock = $socket->accept()){
|                        //New  Connex
|                        login($mysock);
|                   }
|                } else {
|                    //Anyone had something to say
|                    $data = $socket->read($sock);
|                    //Client pinged out
|                    if($data === false){
|                        logout($sock,"Connection reset by Beer");
|                            continue;
|                       }
|                       //Only Enter Hit
|                    if(!$data || $data == "\n" || $data == "\r\n"){
|                        continue;
|                      }
|                    interact($sock, $data);
|                }
|            }
|        }
|
|        It always does a socket_select() on each descriptor -> $socket->can_read();
|        and retruns an array of changed sockets.
|        If a new connex is comming in, class socke() adds the descriptor the the array of
|        clients.
|        On timeout or disconnect the sockdescriptor will be removed from array of clients
|        (socket->read() returns === FALSE instead of NULL
|
|
|        Samplecode II :
|        Create a socket by using a socketfile (UNIX AF_UNIX)
|
|        $socket = new sock("TCP", "AF_UNIX");
|        $io     = $socket->listen("/your/path/to/socket.file",0);
|        Creates a listen Unix sock by using a socketfile for local communications
|
|
|         Also you can use UDP instead of TCP
|
|          For more informations read the declarations of the vars below.
|          Take a notice of set_socketinfo(), get_scoketinfo
|
|
|      by Toppi
|
|   forgett my poor english, if you can !
|   and please ....
|    if you like my work, respect me and dont remove my Notes :-)
|
|      2004
|
|
|
|  Copyright (C) 2001-2004 by Toppi (toppi@kacke.de)
|
|  This program is free software
|  you can redistribute it and/or modify
|  it under the terms of the GNU General Public License as published by
|  the Free Software Foundation; either version 2, or (at your option)
|  any later version.
|
|  This program is distributed in the hope that it will be useful,
|  but WITHOUT ANY WARRANTY; without even the implied warranty of
|  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|  GNU General Public License for more details.
|
|  You should have received a copy of the GNU General Public License
|  along with this program; if not, write to the Free Software
|  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
|
|  Njoy you alte Socke :)
|
\*============================================================================*/


class SOCKET {
    
        var $opener;
        /* Opener = bool
            Makes the lineberak in debug
              If we have getenv[SCRIPT_NAME] we are www and will use <br> else \n
              Set in constructor
        */
        
        var $Socket;
        /*Created Socket 
            TCP, UDP
            AF_INET, AF_UNIX
        */
        
        var $clients;
        /* Clients
            Array of Socketdeskriptors
            [++][ResourceID]
        */
        
        var $socketinfo;
        /*Socketinfo
            Array of additional informations
            Always set by default are:
                [ResourceID][IP]         == IP of remote host. IF AF_UNIX the path
                [ResourceID][Host]         == Hostname of remote host. IF AF_UNIX the path
                [ResourceID][started]     == Timestamp first connect
                [ResourceID][last]         == Timestamp last action
                [ResourceID][anyuservalues] can set by method set_socketinfo($deskriptor,$field,$value)
        */
        
        var $protocol;
        /* protocol array
            Set by set_protocol(); Default is TCP
            [tag]     = TCP / UDP
            [type]  = SOCK_DGRAM / SOCK_STREAM
            [proto] = SOL_TCP / SOL_TCP
          */
        
        var $family;
        /* Family string
            Set by set_family(); Default is "AF_INET"
            AF_INET / AF_UNIX
        */
        
        var $myhost;
        /* myhost = string
            Current adress IP/HOST we listen at AF_INET sockets
            else Location/Name of the socketfile for AF_UNIX sockets (relative unixpath)
        */
        
        var $myport;
        /* myport = integer
            Current port we listen at AF_INET sockets
            else 0 at AF_UNIX
        */
        
        
        var $method;
        /* Method string
            pointer incl. last called method_name
        */

     var $ENABLED;
          /*   ENABELED = bool
               Denotes the class is active and available
          */        
     var $SYS_MESSAGE;

        function SOCKET (&$sys_message, $proto = "TCP", $family = "AF_INET") {
          $this->SYS_MESSAGE =& $sys_message;
          $this->ENABLED = True;
          $this->SYS_MESSAGE->SYSMESSAGE("SOCKET", "SYSTEM", "LOAD MODULE");
          $this->method = "socke";
            
            //Ini Vars
            $this->opener         = FALSE;
            $this->clients         = ARRAY();
            $this->socketinfo     = ARRAY();
            $this->protocol         = ARRAY();
            
            if (getenv("SCRIPT_NAME")){
                $this->opener = TRUE;
            }
            
            //Set defaults
            $this->set_protocol($proto);
            $this->set_family($family);

            $this->create_normal_sock();
            
            return true;
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
        function create_normal_sock(){

            $this->method = "create_normal_sock";

            switch($this->family){
                case AF_UNIX:
                       if (($this->Socket = socket_create(AF_UNIX, SOCK_STREAM, 0)) < 0 ){
                        $this->error($this->Socket,"socket_create() failed !\n",1);
                        //exitpoint
                    }
                    break;

                default:
                    if (($this->Socket = socket_create($this->family, $this->protocol['type'], $this->protocol['proto'])) < 0 ){
                        $this->error($this->Socket,"socket_create() failed !\n",1);
                        //exitpoint
                    }
            }
            socket_clear_error($this->Socket);
            return True;
        }
        
        function set_protocol($proto = "TCP"){

            $this->method = "set_protocol";
            
            $proto = strtoupper($proto);
            
            switch($proto){
                case "TCP":
                    $this->protocol['tag']   = "TCP";
                    $this->protocol['type']  = SOCK_STREAM;
                    $this->protocol['proto'] = SOL_TCP;
                    break;
                case "UDP":
                    $this->protocol['tag']   = "UDP";
                    $this->protocol['type']  = SOCK_DGRAM;
                    $this->protocol['proto'] = SOL_UDP;
                    break;
                default:
                    $this->error("","Protocol \"$proto\" is not a valid Socket-protocol-type!\nUse TCP/UPD Quit..\n",1);
                    //exitpoint
            }
            return True;
        }
        
        function set_family($family = "AF_INET"){

            $this->method = "set_family";

            $family = strtoupper($family);
            
            switch($family){
                case "AF_INET":
                    $this->family = AF_INET;
                    break;
                case "AF_UNIX":
                     $this->family = AF_UNIX;
                    break;
                default:
                    $this->error("","Type \"$family\" is not a valid Socket-family!\nUse AF_INET/AF_UNIX Quit..\n",1);
                    //exitpoint
            }
            return True;
        }
        
        function accept() {
            
            $this->method = "accept";
            
            if (!($conn_id = socket_accept($this->Socket))){
                $this->Error($this->Socket,"failed !\n");
            }else{
                $this->add_socket($conn_id);
                $this->MESSAGE->SYSMESSAGE($conn_id." from IP: ".$this->GET_SOCKETINFO($conn_id, "ip"), "SYSTEM", "CONNECT");

                return $conn_id;
            }
        }

        function set_option($level,$option,$value){
            
            $this->method = "set_option";
            
            if (!socket_set_option($this->Socket, $level, $option, $value)) {
                $this->Error($this->Socket,"Values: ($level,$option,$value) failed !\n");
            }
        }
        
        function bind ($adress_or_path = "0.0.0.0" , $port){
            
            $this->method         = "bind";

            if(!$adress_or_path){$adress_or_path = "0.0.0.0";}

            if($this->family == "AF_UNIX"){
                
                $port        = 0;
                $sockfile    = "/tmp/D2Dsock_".getmypid().".sock";
                
                if($adress_or_path == "0.0.0.0"){
                    $adress_or_path = $sockfile;
                    if(!$this->isdir("/tmp")){
                        $this->error("","Cant not use default path \"/tmp/\" to create socketfile !\nDirectory does not exists\nUse bind() with existing path",1);
                        //exitpoint
                    }
                }
                
                $pathparts = pathinfo($adress_or_path);

                if(!$pathparts['extension'] || !$pathparts['basename']){
                    $this->error("","\"$adress_or_path\" is no valid filename! Useage /path/name.extension \n",1);
                    //exitpoint
                }
                if(!$this->isdir($pathparts[dirname])){
                    $this->error("","Cant not use path \"$pathparts[dirname]\" to create socketfile ! Directory does not exists\n",1);
                    //exitpoint
                }
                if(file_exists($adress_or_path)){
                    $this->error("","Socketfile already exists: \"$adress_or_path\"! Delete, if the socket is not in use anymore\n",1);
                    //exitpoint
                }
                if(!$fp = @fopen($adress_or_path,"w+")){
                    $this->error("","Cant create socketfile in: \"$adress_or_path\"! Make sure we have permissions to do this\n",1);
                    //exitpoint;
                }else{
                    fclose($fp);
                    unlink($adress_or_path);
                }
            }
            if (!socket_bind($this->Socket, $adress_or_path, $port)) {
                $this->error($this->Socket,"Values: ($adress_or_path, $port) failed !\n",1);
                //exitpoint
            }
            
            $this->myhost = $adress_or_path;
            $this->myport = $port;
            
            return True;
        }

        function listen ($adress_or_path = "0.0.0.0", $port){
            
            $this->method = "listen";
            
            $this->set_option(SOL_SOCKET, SO_REUSEADDR, 1);
            $this->bind($adress_or_path, $port);
            if (($ret = socket_listen($this->Socket,15)) < 0 ) {
                $this->error($this->Socket,"Values: ($adress_or_path,$port) failed !\n",1);
                //exitpoint
            }
            $this->add_socket($this->Socket);
  
            return $this->Socket;
        }

        function block($deskriptor=null, $block = true) {
            
            $this->method = "block";
            
            if(!$deskriptor){
                $deskriptor = $this->Socket;
            }
            if(!$block){
                if(!socket_set_nonblock($deskriptor)){
                    $this->Error($deskriptor,"NoneBlock failed !\n");
                    return False;
                }
            }else {
                if(!socket_set_block($deskriptor)){
                    $this->Error($deskriptor,"Block failed !\n");
                    return False;
                }
            }
            return True;
        }
        
        function can_read() {

            $this->method = "can_read";
            
            $read = $this->clients;
            if(false === socket_select($read, $write = NULL, $set_e = NULL, 0, 0)){
                $this->Error("","Socket_select() failed !\n");
                $read = Array();
            }
            return $read;
        }

        function can_write() {
            
            $this->method = "can_write";
            
            $write = $this->clients;
            if( false === socket_select($read, $write = NULL, $set_e = NULL, 0, 0)){
                $this->Error("","Socket_select() failed !\n");
                $write = Array();
            }
            return $write;
        }

        function write($deskriptor, $string = "\x0", $incl_mypipe = FALSE) {
            
            $this->method = "write";
            
            if($deskriptor == $this->Socket && !$incl_mypipe){
                //dont wirte into my own pipe -> gives error broken pipe
                //Dunno if this will be needed anytime ? then set to true
                return;
            }
            if(!$deskriptor){
                $this->Error("","No valid socketdescriptor(id) given !\n");
                return false;
            }
            if($res = socket_write($deskriptor, $string) ){
                $this->socketinfo[$deskriptor]['last'] = time();
                return $res;
            }
            $this->error($deskriptor);
            return false;
        }

        function send($deskriptor,$string, $host, $port = null) {
            
            $this->method = "send";
            
            if(!$deskriptor){
                $this->Error("","No valid socketdescriptor(id) given !\n");
                return false;
            }
            
            // Send a packet when using UDP.
            if(socket_sendto($deskriptor, $string, strlen($string), 0, gethostbyname($host), $port)){
                $this->socketinfo[$deskriptor]['last'] = time();
                return true;
            }
            $this->error($deskriptor,"Host: \"$host\", Port: \"$port\" failed !\n");
            return false;
        }

        function recv($deskriptor, $len) {
            
            $this->method = "recv";
            
            // Recieve from a socket when using UDP.
            $buf  = null;
            $host = null;
            $port = null;

            if(!$deskriptor){
                $this->Error("","No valid socketdescriptor(id) given !\n");
                return false;
            }
            
            if(socket_recvfrom($deskriptor, $buf, $len, 0, $host, $port)){
                $this->socketinfo[$deskriptor]['last'] = time();
                return array($buf, $host, $port);
            }
            $this->error($deskriptor);
            return false;
        }

        function binary_read($deskriptor, $length = 1024) {
            
            $this->method = "binary_read";
            
            // Reads $length number of bytes without stopping at \n or \r.
            if(!$deskriptor){
                $this->Error("","No valid socketdescriptor(id) given !\n");
                return false;
            }
            if (FALSE === ($buf = socket_read ($deskriptor, $length, PHP_BINARY_READ))) {
               $this->error($deskriptor);
            }
            $this->socketinfo[$deskriptor]['last'] = time();
            return $buf;
        }

	function read($deskriptor, $length = 1024)
	{
		$this->method = "read";
		if(!$deskriptor)
		{
			$this->Error("","No valid socketdescriptor(id) given !\n");
			return false;
		}
		$read = "";
		if (($read = @socket_read($deskriptor, $length, PHP_NORMAL_READ)) === false)
		{
			if ($read != '')
			{
				$this->error($deskriptor);
			} else {
				$this->close($deskriptor);
				return FALSE;
			}
		} else {
			$this->socketinfo[$deskriptor]['last'] = time();
			$read =  str_replace("\r", '', $read);
			$read =  str_replace("\n", '', $read);
			socket_read($deskriptor, $length, PHP_NORMAL_READ);
			if($read != ""){return $read;}
			return NULL;
		}
	}

        function shutdown($deskriptor=null, $how = "ALL") {
            
            $this->method = "shutdown";

            switch(strtoupper($how)){
                case "ALL":
                    $how = 2;
                    break;
                case "READ":
                    $how = 0;
                    break;
                case "WRITE":
                    $how = 1;
                    break;
                default:
                    $this->error("","Socket Shutdown \"$how\" is not valid !\nUse ALL/READ/WRITE");
                    return False;
            }
            if(!$deskriptor){
                $deskriptor = $this->Socket;
            }
            if (socket_shutdown($deskriptor, $how)<0){
                $this->error($deskriptor);
            }
            return true;
        }

        function close($deskriptor=null) {
            
            $this->method = "close";
            
            if(!$deskriptor){
                //Shutdown ALL incl. Me
                while (list ($id, $sock) = each ($this->clients)) {
                    if($sock != $this->Socket){
                        socket_close($sock);
                    }
                }
                if($this->family == AF_UNIX && file_exists($this->myhost)){
                    unlink($this->myhost);
                }
                socket_close($this->Socket);
                $this->clients         = "";
                $this->socketinfo     = "";
                $this->Socket         = "";
                return True;
            }
            $this->remove_socket($deskriptor);
            @socket_close($deskriptor);
            return True;
        }
        
        function add_socket ($socket){
            
            $this->method = "add_socket";
            
            $this->clients[] = $socket;
            if($socket != $this->Socket){
                $ip = $this->get_ip($socket);
                $this->socketinfo[$socket]['ip']         = $ip;
                $this->socketinfo[$socket]['host']         = $this->get_host($ip);
            } else {
                //its me ...
                $this->socketinfo[$socket]['ip']         = $this->myhost;
                $this->socketinfo[$socket]['host']         = "not supported";
            }
            $this->socketinfo[$socket]['started']     = time();
            $this->socketinfo[$socket]['last']         = time();
        }

        function remove_socket($deskriptor){
            
            $this->method = "remove_socket";
            
            $sockets = ARRAY();
            reset($this->clients);
            foreach($this->clients as $sock) {
                if($deskriptor != $sock){
                    $sockets[] = $sock;
                }
            }
            $this->remove_socketinfo($deskriptor);
            $this->clients = $sockets;
        }
        
        function get_socketinfo($deskriptor="", $field = ""){

            $this->method = "get_socketinfo";

            if(!$deskriptor){
                return $this->socketinfo;
            }
            if(!$this->socketinfo[$deskriptor]){
                $this->Error("","No valid socketdescriptor(id) given !\n");
                return false;
            }
            if($field){
                return     $this->socketinfo[$deskriptor][$field];
            }
            return $this->socketinfo[$deskriptor];
        }

        function set_socketinfo($deskriptor, $field, $value){

            $this->method = "set_socketinfo";

            if(in_array($field,array("ip","host","started","last"))){
                $this->Error("","Field \"$field\" is already in use by system\n");
                return False;
            }
            $this->socketinfo[$deskriptor][$field] = $value;
            return True;
        }

        function remove_socketinfo($deskriptor){

            $this->method = "remove_socketinfo";

             $infos = ARRAY();
             $this->socketinfo[$deskriptor] = "";
             reset($this->socketinfo);
            while (list ($id, $sock) = each ($this->socketinfo)) {
                if($sock){
                    $infos[$id] = $sock;
                }
            }
            $this->socketinfo = $infos;
        }

        function get_host($ip){
            
            $this->method = "get_host";
            
            if($this->family == AF_INET ){
                if(!$ip){
                    $this->Error("","Not a valid IP \"$ip\" given");
                    return false;
                }
                return gethostbyaddr($ip);
            }
            return "localhost";
        }
        
        function get_ip ($deskriptor){
            
            $this->method = "get_ip";
            
            if($this->family == AF_INET ){
                if(!$deskriptor){
                    $this->Error("","No valid socketdescriptor(id) given !\n");
                    return false;
                }
                socket_getpeername($deskriptor, &$addr);
                if(!$addr){
                    return false;
                }
                return $addr;
            }
            return $this->mypath;
        }

        function debug_print($message){

            if($this->opener){
                echo nl2br($message);
                return;
            }
            echo "$message";
            return;
        }
        
        function isdir($directory){
            return ((@fileperms("$directory") & 0x4000) == 0x4000);
        }
        
        function Error($deskriptor="", $message = "", $critical = FALSE) {
            
            $headline = "Error in :".__CLASS__."::".$this->method."()";

            if(!$critical){
                if($message){$headline = "$headline\n->$message";}
                if($deskriptor){$headline.= "\n->".@socket_strerror(socket_last_error($deskriptor));}
                $this->debug_print("$headline\n");
                return;
            }
            
               $msg = "Socketstatus below:\n";
               $msg.= 'Last deskriptor status: '.@socket_strerror(socket_last_error($deskriptor))."\n";
               $msg.= 'Last socket status: '.@socket_strerror(socket_last_error($this->Socket))."\n";
               $msg.=  "AT: ".__LINE__ ."\nIn:". __FILE__;
                   
            $this->debug_print("CRITICAL ERROR !\n$headline\n$message\n$msg\nSocke dying....");
            
            exit();
        }
    }
?>