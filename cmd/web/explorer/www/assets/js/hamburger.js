(function() {
	'use strict';
	var Menu = (function() {
	  	var burger = document.querySelector('.hamburger-menu-link');
	  	var menu = document.querySelector('.hamburger-menu-content');
	  	var menuList = document.querySelector('.menu-list');
	  	var brand = document.querySelector('.menu-brand');
	  	var menuItems = document.querySelectorAll('.menu-item');
	  	var humbergerHeader = document.querySelectorAll('.hamburger-header');
	  	
	  	var active = false;
	  	
	  	var toggleMenu = function() {
	  		if (!active) {
	  		  	menu.classList.add('menu--active');
	  		  	menuList.classList.add('menu-list--active');
	  		  	brand.classList.add('menu-brand--active');
	  		  	burger.classList.add('burger--close');
	  		  	humbergerHeader[0].classList.add('hamburger-enable');
	  		  	for (var i = 0, ii = menuItems.length; i < ii; i++) {
	  		  	  	menuItems[i].classList.add('menu-item--active');
	  		  	}
	  		  	active = true;
	  		} else {
	  		  	menu.classList.remove('menu--active');
	  		  	menuList.classList.remove('menu-list--active');
	  		  	brand.classList.remove('menu-brand--active');
	  		  	burger.classList.remove('burger--close');
	  		  	humbergerHeader[0].classList.remove('hamburger-enable');
	  		  	for (var i = 0, ii = menuItems.length; i < ii; i++) {
	  		  	  	menuItems[i].classList.remove('menu-item--active');
	  		  	}
	  		  	active = false;
	  		}
	  	};
	  	
	  	var bindActions = function() {
	  	  	burger.addEventListener('click', disableLinks, false);
	  	  	burger.addEventListener('click', toggleMenu, false);
	  	};
	
	  	function disableLinks(e) {
	  	    e.returnValue = false;
	  	}
	  	
	  	var init = function() {
	  	  	bindActions();
	  	};
	  	
	  	return {
	  	  	init: init
	  	};
	}());
	
  	Menu.init();  
}());