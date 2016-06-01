//Drop Down Tabs Menu- Author: Dynamic Drive (http://www.dynamicdrive.com)
//Created: May 16th, 07'

// June 20th, 13': Menus made responsive (by default, to devices with browser width 480px or less).

var tabdropdown={
	disappeardelay: 200, //set delay in miliseconds before menu disappears onmouseout
	disablemenuclick: false, //when user clicks on a menu item with a drop down menu, disable menu item's link?
	enableiframeshim: 1, //1 or 0, for true or false
	mediabreakpoint: 480, // maximum width of browser window (in pixels) before menu becomes responsive (in this case, onlick toggle)

	//No need to edit beyond here////////////////////////
	dropmenuobj: null, ie: document.all, firefox: document.getElementById&&!document.all, previousmenuitem:null,
	currentpageurl: window.location.href.replace("http://"+window.location.hostname, "").replace(/^\//, ""), //get current page url (minus hostname, ie: http://www.dynamicdrive.com/)
	ismobile: navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i) != null, //boolean check for popular mobile browsers

	getposOffset:function(what, offsettype){
		var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
		var parentEl=what.offsetParent;
			while (parentEl!=null){
				totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
				parentEl=parentEl.offsetParent;
			}
		return totaloffset;
	},

	showhide:function(obj, e, obj2){ //obj refers to drop down menu, obj2 refers to tab menu item mouse is currently over
		if (this.ie || this.firefox)
			this.dropmenuobj.style.left=this.dropmenuobj.style.top="-500px"
		if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover"){
			if (obj2.parentNode.className.indexOf("default")==-1) //if tab isn't a default selected one
				obj2.parentNode.className="selected"
			this.showhidemenu('show')
			}
		else if (e.type=="click"){
			this.showhidemenu('hide')
		}
	},

	iecompattest:function(){
		return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	},

	clearbrowseredge:function(obj, whichedge){
		var edgeoffset=0
		if (whichedge=="rightedge"){
			var windowedge=this.ie && !window.opera? this.standardbody.scrollLeft+this.standardbody.clientWidth-15 : window.pageXOffset+window.innerWidth-15
			this.dropmenuobj.contentmeasure=this.dropmenuobj.offsetWidth
		if (windowedge-this.dropmenuobj.x < this.dropmenuobj.contentmeasure)  //move menu to the left?
			edgeoffset=this.dropmenuobj.contentmeasure-obj.offsetWidth
		}
		else{
			var topedge=this.ie && !window.opera? this.standardbody.scrollTop : window.pageYOffset
			var windowedge=this.ie && !window.opera? this.standardbody.scrollTop+this.standardbody.clientHeight-15 : window.pageYOffset+window.innerHeight-18
			this.dropmenuobj.contentmeasure=this.dropmenuobj.offsetHeight
			if (windowedge-this.dropmenuobj.y < this.dropmenuobj.contentmeasure){ //move up?
				edgeoffset=this.dropmenuobj.contentmeasure+obj.offsetHeight
				if ((this.dropmenuobj.y-topedge)<this.dropmenuobj.contentmeasure) //up no good either?
					edgeoffset=this.dropmenuobj.y+obj.offsetHeight-topedge
			}
			this.dropmenuobj.firstlink.style.borderTopWidth=(edgeoffset==0)? 0 : "1px" //Add 1px top border to menu if dropping up
		}
		return edgeoffset
	},

	dropit:function(obj, e, dropmenuID){
		if (this.dropmenuobj!=null){ //hide previous menu
			this.showhidemenu('hide')
			if (this.previousmenuitem!=null && this.previousmenuitem!=obj){
				if (this.previousmenuitem.parentNode.className.indexOf("default")==-1) //If the tab isn't a default selected one
					this.previousmenuitem.parentNode.className=""
			}
		}
		this.clearhidemenu()
		if (this.ie||this.firefox){
			var winwidth = this.ie && !window.opera? this.standardbody.clientWidth : window.innerWidth
			if (winwidth <= this.mediabreakpoint){
				obj.onmouseout = null
				obj.onclick=function(e){e.stopPropagation(); return false}
			}
			else{
				obj.onmouseout=function(){tabdropdown.delayhidemenu(obj)}
				obj.onclick=function(){e.stopPropagation(); return !tabdropdown.disablemenuclick} //disable main menu item link onclick?
			}
			this.dropmenuobj=document.getElementById(dropmenuID)
			this.dropmenuobj.onmouseover=function(){tabdropdown.clearhidemenu()}
			this.dropmenuobj.onmouseout=function(e){tabdropdown.dynamichide(e, obj)}
			this.dropmenuobj.onclick=function(){tabdropdown.delayhidemenu(obj)}
			this.showhide(this.dropmenuobj.style, e, obj)
			this.dropmenuobj.x=this.getposOffset(obj, "left")
			this.dropmenuobj.y=this.getposOffset(obj, "top")
			this.dropmenuobj.style.left=this.dropmenuobj.x-this.clearbrowseredge(obj, "rightedge")+"px"
			this.dropmenuobj.style.top=this.dropmenuobj.y-this.clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+1+"px"
			this.previousmenuitem=obj //remember main menu item mouse moved out from (and into current menu item)
			this.positionshim() //call iframe shim function
		}
	},

	contains_firefox:function(a, b) {
		while (b.parentNode)
		if ((b = b.parentNode) == a)
			return true;
		return false;
	},

	showhidemenu:function(action){
		var visibilityprop = (action == 'show')? 'visible' : 'hidden'
		var displayprop = (action == 'show')? 'block' : 'none'
		tabdropdown.dropmenuobj.style.visibility = visibilityprop
		tabdropdown.dropmenuobj.style.display = displayprop
	},

	dynamichide:function(e, obj2){ //obj2 refers to tab menu item mouse is currently over
		var evtobj=window.event? window.event : e
		if (this.ie&&!this.dropmenuobj.contains(evtobj.toElement))
			this.delayhidemenu(obj2)
		else if (this.firefox&&e.currentTarget!= evtobj.relatedTarget&& !this.contains_firefox(evtobj.currentTarget, evtobj.relatedTarget))
			this.delayhidemenu(obj2)
	},

	delayhidemenu:function(obj2){
		this.delayhide=setTimeout(function(){tabdropdown.showhidemenu('hide'); if (obj2.parentNode.className.indexOf('default')==-1) obj2.parentNode.className=''},this.disappeardelay) //hide menu
	},

	clearhidemenu:function(){
		if (this.delayhide!="undefined")
			clearTimeout(this.delayhide)
	},

	positionshim:function(){ //display iframe shim function
		if (this.enableiframeshim && typeof this.shimobject!="undefined"){
			if (this.dropmenuobj.style.visibility=="visible"){
				this.shimobject.style.width=this.dropmenuobj.offsetWidth+"px"
				this.shimobject.style.height=this.dropmenuobj.offsetHeight+"px"
				this.shimobject.style.left=this.dropmenuobj.style.left
				this.shimobject.style.top=this.dropmenuobj.style.top
			}
		this.shimobject.style.display=(this.dropmenuobj.style.visibility=="visible")? "block" : "none"
		}
	},

	hideshim:function(){
		if (this.enableiframeshim && typeof this.shimobject!="undefined")
			this.shimobject.style.display='none'
	},

isSelected:function(menuurl){
	var menuurl=menuurl.replace("http://"+menuurl.hostname, "").replace(/^\//, "")
	return (tabdropdown.currentpageurl==menuurl)
},

	init:function(menuid, dselected){
		this.standardbody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body //create reference to common "body" across doctypes
		var menuitems=document.getElementById(menuid).getElementsByTagName("a")
		this.enableiframeshim = (this.ismobile)? 0 : this.enableiframeshim
		for (var i=0; i<menuitems.length; i++){
			if (menuitems[i].getAttribute("rel")){
				var relvalue=menuitems[i].getAttribute("rel")
				document.body.appendChild( document.getElementById(relvalue) )
				document.getElementById(relvalue).firstlink=document.getElementById(relvalue).getElementsByTagName("a")[0]
				menuitems[i]["onmouseover"]=function(e){
					var event=typeof e!="undefined"? e : window.event
					tabdropdown.dropit(this, event, this.getAttribute("rel"))
					e.stopPropagation()
				}
			}
			if (dselected=="auto" && typeof setalready=="undefined" && this.isSelected(menuitems[i].href)){
				menuitems[i].parentNode.className+=" selected default"
				var setalready=true
			}
			else if (parseInt(dselected)==i)
				menuitems[i].parentNode.className+=" selected default"
		}
		this.standardbody.onclick = function(e){
			if (tabdropdown.previousmenuitem!=null){
				tabdropdown.delayhidemenu(tabdropdown.previousmenuitem)
			}
		}
	}

}