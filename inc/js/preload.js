"use strict";function init(){for(var t=document.getElementsByTagName("img"),e=0;e<t.length;e++)t[e].getAttribute("data-lazy-src")&&t[e].setAttribute("src",t[e].getAttribute("data-lazy-src"))}window.onload=init;var animateHTML=function(){var e,n,t=function(){e=document.getElementsByClassName("lazydefer"),n=window.innerHeight,i()},i=function(){window.addEventListener("scroll",a),window.addEventListener("resize",t)},a=function(){for(var t=0;t<e.length;t++){e[t].getBoundingClientRect().top-n<=0&&(e[t].className=e[t].className.replace("lazydefer","defered"))}};return{init:t}};animateHTML().init();