<?php defined('BASE') or die("Access denied");

class AppView extends BaseView {

	function __construct($data){
		$this->data=$data;
		$this->render(BASE.'/sys/tpl_app.html');
	}

	function renderLogin(){
		$this->beginForm(ROOT.'/login', $this->data['title']);
		$this->input('User name', 'userName', '', ' autocomplete="username" required autofocus');
		$this->password();
		$this->endForm();
	}

	function renderAdminLink(){
		if(isset($_SESSION['userName'])) echo '<a href="'.ROOT.'/admin" class="ux-admin-link"><svg><use href="#cogs"></use></svg></a>';
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
