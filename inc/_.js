"use strict";
(e=>{
const Fn={
  get(key, parent=document){return parent.querySelector(key)},
  all(key, parent=document){return parent.querySelectorAll(key)},
  on(obj, key, fn){if(obj)obj.addEventListener(key, fn)},
  isSet(val){return !!val||["", 0].includes(val)}
};


function togglePassword(ev){
	ev.preventDefault();
	const obj=Fn.get("[ux-password-area]");
	if(obj.classList.contains('ux-show-pw')){
		obj.classList.remove('ux-show-pw');
		Fn.get('input', obj).type="password";
	} else {
		obj.classList.add('ux-show-pw');
		Fn.get('input', obj).type="text";
	}
}

function init(){ 
	Fn.on(Fn.get('[ux-password-area] button'), 'click', togglePassword);
}

Fn.on(document, "DOMContentLoaded", init);
})();
