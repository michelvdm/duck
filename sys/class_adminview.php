<?php defined('BASE') or die("Access denied");

class AdminView extends BaseView {

	function __construct($data){
		$this->data=$data;
		$this->render(BASE.'/sys/tpl_admin.html');
	}

	function renderUserInfo(){
		if(isset($_SESSION['userName'])) echo $_SESSION['userName'], ' - <a href="'.ROOT.'/logout">Log out</a>';
		else echo 'Anonymous - <a href="'.ROOT.'/login">Log in</a>';
	}

	function renderNav(){
		$items=$this->data['navItems'];
		$here=$this->data['navActive'];
		foreach ($items as $item) {
			extract($item);
			$class=($key==$here)?' class="ux-active"':'';
			tag('a href="'.ROOT.$link.'"'.$class, $label);
		}
	}

	function renderView(){
		extract($this->data);
		$noView=['unid', 'password'];
		tag('h1', $title);
		if(sizeof($items)==0){
			tag('p', 'No items found.');
		} else {
			out('<table class="list">');
			out('<tr>');
			foreach ($items[0] as $key=>$value) {
				if(!in_array($key, $noView)) tag('th', ucfirst($key));
			}
			out('</tr>');
			foreach ($items as $item) {
				$link=$linkBase.$item['unid'];
				out('<tr>');
				foreach ($item as $key=>$value) {
					if(!in_array($key, $noView)) tag('td', "<a href=\"$link\">$value</a>");
				}
				out('</tr>');
			}
			out('</table>');
		}
		
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
				case 'sideNav': $this->renderNav(); break;
				case 'renderTime': echo round(microtime( true )-START_TIME, 3); break;
				default: echo isset($$key)?$$key:('Error - no rule for key: '.$key); 
			}
			echo $tmp2[1];
		}
	}

}
