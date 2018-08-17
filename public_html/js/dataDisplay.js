// JavaScript Document

window.addEventListener('load', function load(){
	'use strict';
	
	document.querySelector('#dataDisplay').innerHTML = 'Upload?';
	document.querySelector('#date').addEventListener('change', function(){
		console.log(document.querySelector('#date').value);
		document.querySelector('#test').innerHTML = 'fuckin work you piece of shit';
	});
});