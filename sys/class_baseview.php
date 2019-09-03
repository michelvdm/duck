<?php defined('BASE') or die("Access denied");

class BaseView {

	function __construct($data){
		$this->data=$data;
		$this->render(BASE.'/sys/tpl_app.html');
	}

	function renderMessage(){
		if(isset($_SESSION['message'])){
			extract($_SESSION['message']);
			tag("div class=\"ux-message $type\"", $text);
			unset($_SESSION['message']);
		};
	}

	function renderStatic() {
		extract($this->data);
		if(isset($errCode)) http_response_code($errCode);
		tag('h1', $title);
		echo $body;
	}

	function beginForm($action, $title=null){
		out("<form method=\"post\" action=\"$action\">");
		if(isset($title)) tag('h1', $title);
		out('<ul>');
	}

	function endForm(){
		out('</ul>');
		out('<ux-actions><button>Submit</button></ux-actions>');
		out('</form>');
	}

	function input($label, $key, $val='', $opt=''){
		out('<li><label><i>'.$label.': </i><input name="'.$key.'" value="'.$val.'"'.$opt.'></label></li>');
	}

	function password(){
		out('<li ux-password-area>');
		out('<label><i>Password: </i>');
		out('<input name="password" type="password" autocomplete="current-password" required></label>');
		out('<button><svg><use href="#eye"></use></svg><svg><use href="#eye-off"></use></svg></button>');
		out('</li>');
	}

	function renderUserInfo(){
		if(isset($_SESSION['userName'])) echo $_SESSION['userName'], ' - <a href="'.ROOT.'/logout">Log out</a>';
		else echo 'Anonymous - <a href="'.ROOT.'/login">Log in</a>';
	}

	function render($tpl) {
		extract($this->data);
		$tmp=explode('{{', file_get_contents($tpl));
		echo $tmp[0];
		for($i=1; $i<sizeof($tmp); $i++){
			$tmp2=explode('}}', $tmp[$i]);
			$key=$tmp2[0];
			switch($key){
				case 'root': echo ROOT; break;
				case 'content':
					$this->renderMessage();
					$fn='render'.ucfirst($type);
					if(method_exists( __CLASS__, $fn)) call_user_func(array($this, $fn));
					else {
						tag('h1', "Error in ".__CLASS__);
						tag('p', "Method does not exist: ".$fn);
					}
					break;
				case 'userInfo': $this->renderUserInfo(); break;
				case 'adminLink': $this->renderAdminLink(); break;
				case 'renderTime': echo round(microtime( true )-START_TIME, 3); break;
				default: echo isset($$key)?$$key:('Error - no rule for key: '.$key); 
			}
			echo $tmp2[1];
		}
	}

}
