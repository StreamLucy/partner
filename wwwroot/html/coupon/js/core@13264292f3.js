/*! Copyright©2008-2016 ykxia.com All Rights Reserved.2017-05-09 */
function dcsMultiTrackCore(){function dcsSyncGetWtid(){document.write("<SCRIPT TYPE='text/javascript' SRC='http"+(0==window.location.protocol.indexOf("https:")?"s":"")+"://"+gDomain+"/"+gDcsId+"/wtid.js'></SCRIPT>")}function dcsAsyncGetWtid(){gJsWtid=document.createElement("script");var jsWtidUrl="http"+(0==window.location.protocol.indexOf("https:")?"s":"")+"://"+gDomain+"/"+gDcsId+"/wtid.js";window.setTimeout('gJsWtid.src="'+jsWtidUrl+'"',0);var headElem=document.getElementsByTagName("head")[0];headElem.appendChild(gJsWtid)}function dcsCookie(){"function"==typeof dcsOther?dcsOther():"function"==typeof dcsPlugin?dcsPlugin():"function"==typeof dcsFPC&&dcsFPC(gTimeZone)}function dcsGetCookie(name){var pos=document.cookie.indexOf(name+"=");if(pos!=-1){var start=pos+name.length+1,end=document.cookie.indexOf(";",start);return end==-1&&(end=document.cookie.length),unescape(document.cookie.substring(start,end))}return null}function dcsGetCrumb(name,crumb){for(var aCookie=dcsGetCookie(name).split(":"),i=0;i<aCookie.length;i++){var aCrumb=aCookie[i].split("=");if(crumb==aCrumb[0])return aCrumb[1]}return null}function dcsGetIdCrumb(name,crumb){for(var cookie=dcsGetCookie(name),id=cookie.substring(0,cookie.indexOf(":lv=")),aCrumb=id.split("="),i=0;i<aCrumb.length;i++)if(crumb==aCrumb[0])return aCrumb[1];return null}function dcsFPC(offset){if("undefined"!=typeof offset&&document.cookie.indexOf("WTLOPTOUT=")==-1){var name=gFpc,dCur=new Date,adj=6e4*dCur.getTimezoneOffset()+36e5*offset;dCur.setTime(dCur.getTime()+adj);var dExp=new Date(dCur.getTime()+31536e7),dSes=new Date(dCur.getTime());if(WT.co_f=WT.vt_sid=WT.vt_f=WT.vt_f_a=WT.vt_f_s=WT.vt_f_d=WT.vt_f_tlh=WT.vt_f_tlv="",document.cookie.indexOf(name+"=")==-1){if("undefined"!=typeof gWtId&&""!=gWtId)WT.co_f=gWtId;else if("undefined"!=typeof gTempWtId&&""!=gTempWtId)WT.co_f=gTempWtId,WT.vt_f="1";else{WT.co_f="2";for(var cur=dCur.getTime().toString(),i=2;i<=32-cur.length;i++)WT.co_f+=Math.floor(16*Math.random()).toString(16);WT.co_f+=cur,WT.vt_f="1"}"undefined"==typeof gWtAccountRollup&&(WT.vt_f_a="1"),WT.vt_f_s=WT.vt_f_d="1",WT.vt_f_tlh=WT.vt_f_tlv="0"}else{var id=dcsGetIdCrumb(name,"id"),lv=parseInt(dcsGetCrumb(name,"lv")),ss=parseInt(dcsGetCrumb(name,"ss"));if(null==id||"null"==id||isNaN(lv)||isNaN(ss))return;WT.co_f=id;var dLst=new Date(lv);WT.vt_f_tlh=Math.floor((dLst.getTime()-adj)/1e3),dSes.setTime(ss),(dCur.getTime()>dLst.getTime()+18e5||dCur.getTime()>dSes.getTime()+288e5)&&(WT.vt_f_tlv=Math.floor((dSes.getTime()-adj)/1e3),dSes.setTime(dCur.getTime()),WT.vt_f_s="1"),dCur.getDay()==dLst.getDay()&&dCur.getMonth()==dLst.getMonth()&&dCur.getYear()==dLst.getYear()||(WT.vt_f_d="1")}WT.co_f=escape(WT.co_f),WT.vt_sid=WT.co_f+"."+(dSes.getTime()-adj);var expiry="; expires="+dExp.toGMTString();document.cookie=name+"=id="+WT.co_f+":lv="+dCur.getTime().toString()+":ss="+dSes.getTime().toString()+expiry+"; path=/"+("undefined"!=typeof gFpcDom&&""!=gFpcDom?"; domain="+gFpcDom:""),document.cookie.indexOf(name+"=")==-1&&(WT.co_f=WT.vt_sid=WT.vt_f_s=WT.vt_f_d=WT.vt_f_tlh=WT.vt_f_tlv="",WT.vt_f=WT.vt_f_a="2")}}function dcsOther(){"undefined"!=typeof WT.dcsvid&&delete WT.dcsvid;var gVisitorId="wt_visitor_id";if("undefined"!=typeof DCSext[gVisitorId]){var vid=DCSext[gVisitorId].replace(/(^\s*)|(\s*$)/g,"").toLowerCase();""!=vid&&"null"!=vid&&(WT.dcsvid=escape(vid))}if("undefined"!=typeof WT.dcsvid){var dCur=new Date,dExp=new Date(dCur.getTime()+31536e7),expiry="; expires="+dExp.toGMTString();document.cookie=gVisitorId+"="+DCSext[gVisitorId]+expiry+"; path=/"+("undefined"!=typeof gFpcDom&&""!=gFpcDom?"; domain="+gFpcDom:"")}else{var vid=dcsGetCookie(gVisitorId);null!=vid&&(vid=vid.replace(/(^\s*)|(\s*$)/g,"").toLowerCase(),""!=vid&&"null"!=vid&&(WT.dcsvid=escape(vid)))}"undefined"!=typeof gFpc&&dcsFPC(gTimeZone)}function dcsParseSvl(sv){sv=sv.split(" ").join(""),sv=sv.split("\t").join(""),sv=sv.split("\n").join("");var pos=sv.toUpperCase().indexOf("WT.SVL=");if(pos!=-1){var start=pos+8,end=sv.indexOf('"',start);return end==-1&&(end=sv.indexOf("'",start),end==-1&&(end=sv.length)),sv.substring(start,end)}return""}function dcsIsOnsite(host){for(var doms="@@ONSITEDOMAINS@@",aDoms=doms.split(","),i=0;i<aDoms.length;i++)if(host.indexOf(aDoms[i])!=-1)return 1;return 0}function dcsIsHttp(e){return!(!e.href||!e.protocol||e.protocol.indexOf("http")==-1)}function dcsTypeMatch(path,typelist){for(var type=path.substring(path.lastIndexOf(".")+1,path.length),types=typelist.split(","),i=0;i<types.length;i++)if(type==types[i])return!0;return!1}function dcsEvt(evt,tag){for(var e=evt.target||evt.srcElement;e.tagName&&e.tagName!=tag;)e=e.parentElement||e.parentNode;return e}function dcsBind(event,func){"function"==typeof window[func]&&document.body&&(document.body.addEventListener?document.body.addEventListener(event,window[func],!0):document.body.attachEvent&&document.body.attachEvent("on"+event,window[func]))}function dcsET(){var e=navigator.appVersion.indexOf("MSIE")!=-1?"click":"mousedown";dcsBind(e,"dcsDownload"),dcsBind(e,"dcsDynamic"),dcsBind(e,"dcsFormButton"),dcsBind(e,"dcsOffsite"),dcsBind(e,"dcsAnchor"),dcsBind("mousedown","dcsRightClick")}function dcsMultiTrack(){if(console.log("dcsMultiTrack start"),arguments.length%2==0){for(var i=0;i<arguments.length;i+=2)0==arguments[i].indexOf("WT.")?WT[arguments[i].substring(3)]=arguments[i+1]:0==arguments[i].indexOf("DCS.")?DCS[arguments[i].substring(4)]=arguments[i+1]:0==arguments[i].indexOf("DCSext.")&&(DCSext[arguments[i].substring(7)]=arguments[i+1]);var dCurrent=new Date;DCS.dcsdat=dCurrent.getTime(),dcsFunc("dcsCookie"),WT.ti=gI18n?dcsEscape(dcsEncode(WT.ti),I18NRE):WT.ti,dcsTag()}}function dcsAdv(){dcsFunc("dcsET"),dcsFunc("dcsCookie"),dcsFunc("dcsAdSearch"),dcsFunc("dcsTP")}function dcsVar(){var dCurrent=new Date;if(WT.tz=dCurrent.getTimezoneOffset()/60*-1,0==WT.tz&&(WT.tz="0"),WT.bh=dCurrent.getHours(),WT.ul="Netscape"==navigator.appName?navigator.language:navigator.userLanguage,"object"==typeof screen&&(WT.cd="Netscape"==navigator.appName?screen.pixelDepth:screen.colorDepth,WT.sr=screen.width+"x"+screen.height),"boolean"==typeof navigator.javaEnabled()&&(WT.jo=navigator.javaEnabled()?"Yes":"No"),document.title&&(WT.ti=gI18n?dcsEscape(dcsEncode(document.title),I18NRE):document.title),WT.js="Yes",WT.jv=dcsJV(),document.body&&document.body.addBehavior?(document.body.addBehavior("#default#clientCaps"),WT.ct=document.body.connectionType||"unknown",document.body.addBehavior("#default#homePage"),WT.hp=document.body.isHomePage(location.href)?"1":"0"):WT.ct="unknown",parseInt(navigator.appVersion)>3&&("Microsoft Internet Explorer"==navigator.appName&&document.body?WT.bs=document.body.offsetWidth+"x"+document.body.offsetHeight:"Netscape"==navigator.appName&&(WT.bs=window.innerWidth+"x"+window.innerHeight)),WT.fi="No",window.ActiveXObject)for(var i=10;i>0;i--)try{new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+i);WT.fi="Yes",WT.fv=i+".0";break}catch(e){}else if(navigator.plugins&&navigator.plugins.length)for(var i=0;i<navigator.plugins.length;i++)if(navigator.plugins[i].name.indexOf("Shockwave Flash")!=-1){WT.fi="Yes",WT.fv=navigator.plugins[i].description.split(" ")[2];break}if(gI18n&&(WT.em="function"==typeof encodeURIComponent?"uri":"esc","string"==typeof document.defaultCharset?WT.le=document.defaultCharset:"string"==typeof document.characterSet&&(WT.le=document.characterSet)),WT.tv="8.0.2",DCS.dcsdat=dCurrent.getTime(),DCS.dcssip=window.location.hostname,DCS.dcsuri=window.location.pathname,window.location.search&&(DCS.dcsqry=window.location.search,gQP.length>0))for(var i=0;i<gQP.length;i++){var pos=DCS.dcsqry.indexOf(gQP[i]);if(pos!=-1){var front=DCS.dcsqry.substring(0,pos),end=DCS.dcsqry.substring(pos+gQP[i].length,DCS.dcsqry.length);DCS.dcsqry=front+end}}""!=window.document.referrer&&"-"!=window.document.referrer&&("Microsoft Internet Explorer"==navigator.appName&&parseInt(navigator.appVersion)<4||(DCS.dcsref=gI18n?dcsEscape(window.document.referrer,I18NRE):window.document.referrer))}function dcsA(N,V){return"&"+N+"="+dcsEscape(V,RE)}function dcsEscape(S,REL){if("undefined"!=typeof REL){var retStr=new String(S);for(var R in REL)retStr=retStr.replace(REL[R],R);return retStr}return escape(S)}function dcsEncode(S){return"function"==typeof encodeURIComponent?encodeURIComponent(S):escape(S)}function dcsCreateImage(dcsSrc){document.images?(gImages[gIndex]=new Image,gImages[gIndex].src=dcsSrc,gIndex++):document.write('<IMG ALT="" BORDER="0" NAME="DCSIMG" WIDTH="1" HEIGHT="1" SRC="'+dcsSrc+'">')}function dcsMeta(){var elems;if(document.all?elems=document.all.tags("meta"):document.documentElement&&(elems=document.getElementsByTagName("meta")),"undefined"!=typeof elems)for(var length=elems.length,i=0;i<length;i++){var name=elems.item(i).name,content=elems.item(i).content,equiv=elems.item(i).httpEquiv;if(name.length>0)if(0==name.indexOf("WT.")){var encode=!1;if(gI18n)for(var params=["mc_id","oss","ti"],j=0;j<params.length;j++)if(0==name.indexOf("WT."+params[j])){encode=!0;break}WT[name.substring(3)]=encode?dcsEscape(dcsEncode(content),I18NRE):content}else if(0==name.indexOf("DCSext.")){var encode=!1;if(gI18n)for(var params=["wt_visitor_id"],j=0;j<params.length;j++)if(0==name.indexOf("DCSext."+params[j])){encode=!0;break}DCSext[name.substring(7)]=encode?dcsEscape(dcsEncode(content),I18NRE):content}else 0==name.indexOf("DCS.")&&(DCS[name.substring(4)]=gI18n&&0==name.indexOf("DCS.dcsref")?dcsEscape(content,I18NRE):content);else if(gI18n&&"Content-Type"==equiv){var pos=content.toLowerCase().indexOf("charset=");pos!=-1&&(WT.mle=content.substring(pos+8))}}}function dcsTag(){if(document.cookie.indexOf("WTLOPTOUT=")==-1){DCS.dcsref&&DCS.dcsref.length>1024&&(DCS.dcsref=DCS.dcsref.substring(0,900));var P="http"+(0==window.location.protocol.indexOf("https:")?"s":"")+"://"+gDomain+(""==gDcsId?"":"/"+gDcsId)+"/dcs.gif?";for(var N in DCS)DCS[N]&&(P+=dcsA(N,DCS[N]));for(var keys=["co_f","vt_sid","vt_f_tlv"],i=0;i<keys.length;i++){var key=keys[i];WT[key]&&(P+=dcsA("WT."+key,WT[key]),delete WT[key])}for(N in WT)WT[N]&&(P+=dcsA("WT."+N,WT[N]));for(N in DCSext)DCSext[N]&&(P+=dcsA(N,DCSext[N]));P.length>2048&&navigator.userAgent.indexOf("MSIE")>=0&&(P=P.substring(0,2040)+"&WT.tu=1"),dcsCreateImage(P)}}function dcsPrintVariables(){var tagVariables="\nDomain = "+gDomain;tagVariables+="\nDCSId = "+gDcsId;for(N in DCS)tagVariables+="\nDCS."+N+" = "+DCS[N];for(N in WT)tagVariables+="\nWT."+N+" = "+WT[N];for(N in DCSext)tagVariables+="\nDCSext."+N+" = "+DCSext[N];window.alert(tagVariables)}function dcsJV(){var agt=navigator.userAgent.toLowerCase(),major=parseInt(navigator.appVersion),mac=agt.indexOf("mac")!=-1,ff=agt.indexOf("firefox")!=-1,ff0=agt.indexOf("firefox/0.")!=-1,ff10=agt.indexOf("firefox/1.0")!=-1,ff15=agt.indexOf("firefox/1.5")!=-1,ff2up=ff&&!ff0&&!ff10&!ff15,nn=!ff&&agt.indexOf("mozilla")!=-1&&agt.indexOf("compatible")==-1,nn4=nn&&4==major,nn6up=nn&&major>=5,ie=agt.indexOf("msie")!=-1&&agt.indexOf("opera")==-1,ie4=ie&&4==major&&agt.indexOf("msie 4")!=-1,ie5up=ie&&!ie4,op=agt.indexOf("opera")!=-1,op5=agt.indexOf("opera 5")!=-1||agt.indexOf("opera/5")!=-1,op6=agt.indexOf("opera 6")!=-1||agt.indexOf("opera/6")!=-1,op7up=op&&!op5&&!op6,jv="1.1";return ff2up?jv="1.7":ff15?jv="1.6":ff0||ff10||nn6up||op7up?jv="1.5":mac&&ie5up||op6?jv="1.4":ie5up||nn4||op5?jv="1.3":ie4&&(jv="1.2"),jv}function dcsFunc(func){"function"==typeof window[func]&&window[func]()}var gDomain="sdc.zuche.com",gDcsId="dcswwefp210000oej7isqjplt_1q7t",gFpc="WT_FPC",gConvert=!0,gJsWtid;"undefined"!=typeof gConvert&&gConvert&&document.cookie.indexOf(gFpc+"=")==-1&&document.cookie.indexOf("WTLOPTOUT=")==-1;var gService=!1,gTimeZone=8,gImages=new Array,gIndex=0,DCS=new Object,WT=new Object,DCSext=new Object,gQP=new Array,gI18n=!0;if(window.RegExp)var RE={"%09":/\t/g,"%20":/ /g,"%23":/\#/g,"%26":/\&/g,"%2B":/\+/g,"%3F":/\?/g,"%5C":/\\/g,"%22":/\"/g,"%7F":/\x7F/g,"%A0":/\xA0/g},I18NRE={"%25":/\%/g};dcsVar(),dcsMeta(),dcsFunc("dcsAdv"),dcsTag(),"function"==typeof eval("dcsMultiTrack")&&dcsMultiTrack.apply(this,arguments)}var zuche={version:"1.0",author:"m.zuche.com",website:"/"};zuche.uitls={getUrlParam:function(key){var url=window.location.search;if(url=url.split("?")[1],!url)return null;var value=null,params=url.split("&");return $.each(params,function(i,param){var kv=param.split("=");if(kv[0]==key)return value=decodeURIComponent(kv[1]),!1}),value},show:function(msg,callback){if(!$("#dialog-mask").length){var dialog=document.createElement("div");dialog.id="dialog-mask",dialog.className="mask",$(dialog).html("<div class='dialog'><div class='dialog-content-full'><p>"+msg+"</p><span class='btn-dialog-close'></span></div></div>"),document.body.appendChild(dialog),$("#dialog-close").click(function(){$("#dialog-mask").remove(),callback&&callback()}),$("#dialog-mask").click(function(){$("#dialog-mask").remove(),callback&&callback()}),$("#dialog-mask").fadeIn("normal")}},loading:{show:function(){var html="<div id='loading-mask' class='mask'><div class='loading-full'><img src='http://img01.10101111cdn.com/mkt/bak/2015/wap/download/app/loading_new.gif' /><div></div></div></div>";$("#loading-mask").length?$("#loading-mask").show():$(html).appendTo(document.body)},hide:function(){$("#loading-mask").hide()}},isMobile:function(val){var myreg=/(^0?(13[0-9]|14[57]|15[012356789]|17[012356789]|18[0-9])[0-9]{8}$)|(^886[0-9]{9}$)/;return myreg.test(val)},canStorage:function(){return!!window.localStorage},setStorage:function(key,value){try{zuche.uitls.canStorage()&&(localStorage.removeItem(key),"string"!=typeof value&&(value=JSON.stringify(value)),localStorage.setItem(key,value))}catch(e){}},getStorage:function(key){if(zuche.uitls.canStorage()){var value=localStorage.getItem(key);value&&"string"==typeof value&&"undefined"===value&&(value=null);try{return value?JSON.parse(value):null}catch(err){return value}}},removeStorage:function(key){zuche.uitls.canStorage()&&localStorage.removeItem(key)},setSession:function(key,value){if(window.sessionStorage)try{sessionStorage.removeItem(key),"string"!=typeof value&&(value=JSON.stringify(value)),sessionStorage.setItem(key,value)}catch(e){}},getSession:function(key){if(window.sessionStorage)try{var value=sessionStorage.getItem(key);value&&"string"==typeof value&&"undefined"===value&&(value=null);try{return value?JSON.parse(value):null}catch(err){return value}}catch(e){}},removeSession:function(key){sessionStorage.removeItem(key)}},$(document).on("ajaxStart",function(){zuche.uitls.loading.show()}),$(document).on("ajaxStop",function(){zuche.uitls.loading.hide()}),+function($){$(function(){var href=location.href;if(href.indexOf("activityCode")!=-1){var code=zuche.uitls.getUrlParam("activityCode");$(".btn-trace").click(function(){var data,mobile=$(".mobile-trace").val(),traceType=$(this).attr("data-traceType");switch(traceType){case"download":data={type:"203",activityCode:code};break;case"rent":data={type:"202",activityCode:code};break;case"coupon":data={type:"204",mobile:mobile,activityCode:code}}data&&window.LCTJ&&window.LCTJ.putBe(data)})}})}(jQuery);var lingquanSuccess=function(){var code=zuche.uitls.getUrlParam("activityCode"),mobile=$(".mobile-trace").val(),data={type:"201",mobile:mobile,activityCode:code};code&&mobile&&window.LCTJ&&window.LCTJ.putBe(data)};