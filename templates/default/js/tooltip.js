var config = new Object(); var tt_Debug = false
var tt_Enabled = true
var TagsToTip = true
config. Above = false
config. BgColor = '#1f1f1f'
config. BgImg = ''
config. BorderColor = '#303030'
config. BorderStyle = 'dotted'
config. BorderWidth = 0
config. CenterMouse = false
config. ClickClose = false
config. ClickSticky = false
config. CloseBtn = false
config. CloseBtnColors = ['#990000', '#FFFFFF', '#DD3333', '#FFFFFF']
config. CloseBtnText = '&nbsp;X&nbsp;'
config. CopyContent = true
config. Delay = 20
config. Duration = 0
config. FadeIn = 0
config. FadeOut = 0
config. FadeInterval = 0
config. Fix = null
config. FollowMouse = true
config. FontColor = '#f0f044'
config. FontFace = 'Verdana,Geneva,sans-serif'
config. FontSize = '8pt'
config. FontWeight = 'normal'
config. Height = 0
config. JumpHorz = false
config. JumpVert = true
config. Left = false
config. OffsetX = 14
config. OffsetY = 8
config. Opacity = 90
config. Padding = 5
config. Shadow = true
config. ShadowColor = '#151515'
config. ShadowWidth = 5
config. Sticky = false
config. TextAlign = 'center'
config. Title = ''
config. TitleAlign = 'center'
config. TitleBgColor = '#1E3052'
config. TitleFontColor = '#cccccc'
config. TitleFontFace = ''
config. TitleFontSize = ''
config. TitlePadding = 1
config. Width = 0
function Tip()
{ tt_Tip(arguments, null);}
function TagToTip()
{ var t2t = tt_GetElt(arguments[0]); if(t2t)
tt_Tip(arguments, t2t);}
function UnTip()
{ tt_OpReHref(); if(tt_aV[DURATION] < 0 && (tt_iState & 0x2))
tt_tDurt.Timer("tt_HideInit()", -tt_aV[DURATION], true); else if(!(tt_aV[STICKY] && (tt_iState & 0x2)))
tt_HideInit();}
var tt_aElt = new Array(10), tt_aV = new Array(), tt_sContent, tt_scrlX = 0, tt_scrlY = 0, tt_musX, tt_musY, tt_over, tt_x, tt_y, tt_w, tt_h; function tt_Extension()
{ tt_ExtCmdEnum(); tt_aExt[tt_aExt.length] = this; return this;}
function tt_SetTipPos(x, y)
{ var css = tt_aElt[0].style; tt_x = x; tt_y = y; css.left = x + "px"; css.top = y + "px"; if(tt_ie56)
{ var ifrm = tt_aElt[tt_aElt.length - 1]; if(ifrm)
{ ifrm.style.left = css.left; ifrm.style.top = css.top;}
}
}
function tt_HideInit()
{ if(tt_iState)
{ tt_ExtCallFncs(0, "HideInit"); tt_iState &= ~0x4; if(tt_flagOpa && tt_aV[FADEOUT])
{ tt_tFade.EndTimer(); if(tt_opa)
{ var n = Math.round(tt_aV[FADEOUT] / (tt_aV[FADEINTERVAL] * (tt_aV[OPACITY] / tt_opa))); tt_Fade(tt_opa, tt_opa, 0, n); return;}
}
tt_tHide.Timer("tt_Hide();", 1, false);}
}
function tt_Hide()
{ if(tt_db && tt_iState)
{ tt_OpReHref(); if(tt_iState & 0x2)
{ tt_aElt[0].style.visibility = "hidden"; tt_ExtCallFncs(0, "Hide");}
tt_tShow.EndTimer(); tt_tHide.EndTimer(); tt_tDurt.EndTimer(); tt_tFade.EndTimer(); if(!tt_op && !tt_ie)
{ tt_tWaitMov.EndTimer(); tt_bWait = false;}
if(tt_aV[CLICKCLOSE] || tt_aV[CLICKSTICKY])
tt_RemEvtFnc(document, "mouseup", tt_OnLClick); tt_ExtCallFncs(0, "Kill"); if(tt_t2t && !tt_aV[COPYCONTENT])
{ tt_t2t.style.display = "none"; tt_MovDomNode(tt_t2t, tt_aElt[6], tt_t2tDad);}
tt_iState = 0; tt_over = null; tt_ResetMainDiv(); if(tt_aElt[tt_aElt.length - 1])
tt_aElt[tt_aElt.length - 1].style.display = "none";}
}
function tt_GetElt(id)
{ return(document.getElementById ? document.getElementById(id)
: document.all ? document.all[id]
: null);}
function tt_GetDivW(el)
{ return(el ? (el.offsetWidth || el.style.pixelWidth || 0) : 0);}
function tt_GetDivH(el)
{ return(el ? (el.offsetHeight || el.style.pixelHeight || 0) : 0);}
function tt_GetScrollX()
{ return(window.pageXOffset || (tt_db ? (tt_db.scrollLeft || 0) : 0));}
function tt_GetScrollY()
{ return(window.pageYOffset || (tt_db ? (tt_db.scrollTop || 0) : 0));}
function tt_GetClientW()
{ return(document.body && (typeof(document.body.clientWidth) != tt_u) ? document.body.clientWidth
: (typeof(window.innerWidth) != tt_u) ? window.innerWidth
: tt_db ? (tt_db.clientWidth || 0)
: 0);}
function tt_GetClientH()
{ return(document.body && (typeof(document.body.clientHeight) != tt_u) ? document.body.clientHeight
: (typeof(window.innerHeight) != tt_u) ? window.innerHeight
: tt_db ? (tt_db.clientHeight || 0)
: 0);}
function tt_GetEvtX(e)
{ return (e ? ((typeof(e.pageX) != tt_u) ? e.pageX : (e.clientX + tt_scrlX)) : 0);}
function tt_GetEvtY(e)
{ return (e ? ((typeof(e.pageY) != tt_u) ? e.pageY : (e.clientY + tt_scrlY)) : 0);}
function tt_AddEvtFnc(el, sEvt, PFnc)
{ if(el)
{ if(el.addEventListener)
el.addEventListener(sEvt, PFnc, false); else
el.attachEvent("on" + sEvt, PFnc);}
}
function tt_RemEvtFnc(el, sEvt, PFnc)
{ if(el)
{ if(el.removeEventListener)
el.removeEventListener(sEvt, PFnc, false); else
el.detachEvent("on" + sEvt, PFnc);}
}
var tt_aExt = new Array(), tt_db, tt_op, tt_ie, tt_ie56, tt_bBoxOld, tt_body, tt_ovr_, tt_flagOpa, tt_maxPosX, tt_maxPosY, tt_iState = 0, tt_opa, tt_bJmpVert, tt_bJmpHorz, tt_t2t, tt_t2tDad, tt_elDeHref, tt_tShow = new Number(0), tt_tHide = new Number(0), tt_tDurt = new Number(0), tt_tFade = new Number(0), tt_tWaitMov = new Number(0), tt_bWait = false, tt_u = "undefined"; function tt_Init()
{ tt_MkCmdEnum(); if(!tt_Browser() || !tt_MkMainDiv())
return; tt_OnScrl(); tt_IsW3cBox(); tt_OpaSupport(); tt_AddEvtFnc(window, "scroll", tt_OnScrl); tt_AddEvtFnc(window, "resize", tt_OnScrl); tt_AddEvtFnc(document, "mousemove", tt_Move); if(TagsToTip || tt_Debug)
tt_SetOnloadFnc(); tt_AddEvtFnc(window, "unload", tt_Hide);}
function tt_MkCmdEnum()
{ var n = 0; for(var i in config)
eval("window." + i.toString().toUpperCase() + " = " + n++); tt_aV.length = n;}
function tt_Browser()
{ var n, nv, n6, w3c; n = navigator.userAgent.toLowerCase(), nv = navigator.appVersion; tt_op = (document.defaultView && typeof(eval("w" + "indow" + "." + "o" + "p" + "er" + "a")) != tt_u); tt_ie = n.indexOf("msie") != -1 && document.all && !tt_op; if(tt_ie)
{ var ieOld = (!document.compatMode || document.compatMode == "BackCompat"); tt_db = !ieOld ? document.documentElement : (document.body || null); if(tt_db)
tt_ie56 = parseFloat(nv.substring(nv.indexOf("MSIE") + 5)) >= 5.5
&& typeof document.body.style.maxHeight == tt_u;}
else
{ tt_db = document.documentElement || document.body || (document.getElementsByTagName ? document.getElementsByTagName("body")[0]
: null); if(!tt_op)
{ n6 = document.defaultView && typeof document.defaultView.getComputedStyle != tt_u; w3c = !n6 && document.getElementById;}
}
tt_body = (document.getElementsByTagName ? document.getElementsByTagName("body")[0]
: (document.body || null)); if(tt_ie || n6 || tt_op || w3c)
{ if(tt_body && tt_db)
{ if(document.attachEvent || document.addEventListener)
return true;}
else
tt_Err("wz_tooltip.js must be included INSIDE the body section," + " immediately after the opening <body> tag.", false);}
tt_db = null; return false;}
function tt_MkMainDiv()
{ if(tt_body.insertAdjacentHTML)
tt_body.insertAdjacentHTML("afterBegin", tt_MkMainDivHtm()); else if(typeof tt_body.innerHTML != tt_u && document.createElement && tt_body.appendChild)
tt_body.appendChild(tt_MkMainDivDom()); if(window.tt_GetMainDivRefs && tt_GetMainDivRefs())
return true; tt_db = null; return false;}
function tt_MkMainDivHtm()
{ return('<div id="WzTtDiV"></div>' + (tt_ie56 ? ('<iframe id="WzTtIfRm" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>')
: ''));}
function tt_MkMainDivDom()
{ var el = document.createElement("div"); if(el)
el.id = "WzTtDiV"; return el;}
function tt_GetMainDivRefs()
{ tt_aElt[0] = tt_GetElt("WzTtDiV"); if(tt_ie56 && tt_aElt[0])
{ tt_aElt[tt_aElt.length - 1] = tt_GetElt("WzTtIfRm"); if(!tt_aElt[tt_aElt.length - 1])
tt_aElt[0] = null;}
if(tt_aElt[0])
{ var css = tt_aElt[0].style; css.visibility = "hidden"; css.position = "absolute"; css.overflow = "hidden"; return true;}
return false;}
function tt_ResetMainDiv()
{ tt_SetTipPos(0, 0); tt_aElt[0].innerHTML = ""; tt_aElt[0].style.width = "auto"; tt_h = 0;}
function tt_IsW3cBox()
{ var css = tt_aElt[0].style; css.padding = "10px"; css.width = "40px"; tt_bBoxOld = (tt_GetDivW(tt_aElt[0]) == 40); css.padding = "0px"; tt_ResetMainDiv();}
function tt_OpaSupport()
{ var css = tt_body.style; tt_flagOpa = (typeof(css.KhtmlOpacity) != tt_u) ? 2
: (typeof(css.KHTMLOpacity) != tt_u) ? 3
: (typeof(css.MozOpacity) != tt_u) ? 4
: (typeof(css.opacity) != tt_u) ? 5
: (typeof(css.filter) != tt_u) ? 1
: 0;}
function tt_SetOnloadFnc()
{ tt_AddEvtFnc(document, "DOMContentLoaded", tt_HideSrcTags); tt_AddEvtFnc(window, "load", tt_HideSrcTags); if(tt_body.attachEvent)
tt_body.attachEvent("onreadystatechange", function() { if(tt_body.readyState == "complete")
tt_HideSrcTags();} ); if(/WebKit|KHTML/i.test(navigator.userAgent))
{ var t = setInterval(function() { if(/loaded|complete/.test(document.readyState))
{ clearInterval(t); tt_HideSrcTags();}
}, 10);}
}
function tt_HideSrcTags()
{ if(!window.tt_HideSrcTags || window.tt_HideSrcTags.done)
return; window.tt_HideSrcTags.done = true; if(!tt_HideSrcTagsRecurs(tt_body))
tt_Err("There are HTML elements to be converted to tooltips.\nIf you" + " want these HTML elements to be automatically hidden, you" + " must edit wz_tooltip.js, and set TagsToTip in the global" + " tooltip configuration to true.", true);}
function tt_HideSrcTagsRecurs(dad)
{ var ovr, asT2t; var a = dad.childNodes || dad.children || null; for(var i = a ? a.length : 0; i;)
{--i; if(!tt_HideSrcTagsRecurs(a[i]))
return false; ovr = a[i].getAttribute ? (a[i].getAttribute("onmouseover") || a[i].getAttribute("onclick"))
: (typeof a[i].onmouseover == "function") ? (a[i].onmouseover || a[i].onclick)
: null; if(ovr)
{ asT2t = ovr.toString().match(/TagToTip\s*\(\s*'[^'.]+'\s*[\),]/);
			if(asT2t && asT2t.length)
			{
				if(!tt_HideSrcTag(asT2t[0]))
					return false;
			}
		}
	}
	return true;
}
function tt_HideSrcTag(sT2t)
{
	var id, el;

	// The ID passed to the found TagToTip() call identifies an HTML element
	// to be converted to a tooltip, so hide that element
	id = sT2t.replace(/.+'([^'.]+)'.+/, "$1"); el = tt_GetElt(id); if(el)
{ if(tt_Debug && !TagsToTip)
return false; else
el.style.display = "none";}
else
tt_Err("Invalid ID\n'" + id + "'\npassed to TagToTip()." + " There exists no HTML element with that ID.", true); return true;}
function tt_Tip(arg, t2t)
{ if(!tt_db)
return; if(tt_iState)
tt_Hide(); if(!tt_Enabled)
return; tt_t2t = t2t; if(!tt_ReadCmds(arg))
return; tt_iState = 0x1 | 0x4; tt_AdaptConfig1(); tt_MkTipContent(arg); tt_MkTipSubDivs(); tt_FormatTip(); tt_bJmpVert = false; tt_bJmpHorz = false; tt_maxPosX = tt_GetClientW() + tt_scrlX - tt_w - 1; tt_maxPosY = tt_GetClientH() + tt_scrlY - tt_h - 1; tt_AdaptConfig2(); tt_OverInit(); tt_ShowInit(); tt_Move();}
function tt_ReadCmds(a)
{ var i; i = 0; for(var j in config)
tt_aV[i++] = config[j]; if(a.length & 1)
{ for(i = a.length - 1; i > 0; i -= 2)
tt_aV[a[i - 1]] = a[i]; return true;}
tt_Err("Incorrect call of Tip() or TagToTip().\n" + "Each command must be followed by a value.", true); return false;}
function tt_AdaptConfig1()
{ tt_ExtCallFncs(0, "LoadConfig"); if(!tt_aV[TITLEBGCOLOR].length)
tt_aV[TITLEBGCOLOR] = tt_aV[BORDERCOLOR]; if(!tt_aV[TITLEFONTCOLOR].length)
tt_aV[TITLEFONTCOLOR] = tt_aV[BGCOLOR]; if(!tt_aV[TITLEFONTFACE].length)
tt_aV[TITLEFONTFACE] = tt_aV[FONTFACE]; if(!tt_aV[TITLEFONTSIZE].length)
tt_aV[TITLEFONTSIZE] = tt_aV[FONTSIZE]; if(tt_aV[CLOSEBTN])
{ if(!tt_aV[CLOSEBTNCOLORS])
tt_aV[CLOSEBTNCOLORS] = new Array("", "", "", ""); for(var i = 4; i;)
{--i; if(!tt_aV[CLOSEBTNCOLORS][i].length)
tt_aV[CLOSEBTNCOLORS][i] = (i & 1) ? tt_aV[TITLEFONTCOLOR] : tt_aV[TITLEBGCOLOR];}
if(!tt_aV[TITLE].length)
tt_aV[TITLE] = " ";}
if(tt_aV[OPACITY] == 100 && typeof tt_aElt[0].style.MozOpacity != tt_u && !Array.every)
tt_aV[OPACITY] = 99; if(tt_aV[FADEIN] && tt_flagOpa && tt_aV[DELAY] > 100)
tt_aV[DELAY] = Math.max(tt_aV[DELAY] - tt_aV[FADEIN], 100);}
function tt_AdaptConfig2()
{ if(tt_aV[CENTERMOUSE])
{ tt_aV[OFFSETX] -= ((tt_w - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0)) >> 1); tt_aV[JUMPHORZ] = false;}
}
function tt_MkTipContent(a)
{ if(tt_t2t)
{ if(tt_aV[COPYCONTENT])
tt_sContent = tt_t2t.innerHTML; else
tt_sContent = "";}
else
tt_sContent = a[0]; tt_ExtCallFncs(0, "CreateContentString");}
function tt_MkTipSubDivs()
{ var sCss = 'position:relative;margin:0px;padding:0px;border-width:0px;left:0px;top:0px;line-height:normal;width:auto;', sTbTrTd = ' cellspacing="0" cellpadding="0" border="0" style="' + sCss + '"><tbody style="' + sCss + '"><tr><td '; tt_aElt[0].innerHTML = ('' + (tt_aV[TITLE].length ?
('<div id="WzTiTl" style="position:relative;z-index:1;">' + '<table id="WzTiTlTb"' + sTbTrTd + 'id="WzTiTlI" style="' + sCss + '">' + tt_aV[TITLE] + '</td>' + (tt_aV[CLOSEBTN] ?
('<td align="right" style="' + sCss + 'text-align:right;">' + '<span id="WzClOsE" style="position:relative;left:2px;padding-left:2px;padding-right:2px;' + 'cursor:' + (tt_ie ? 'hand' : 'pointer') + ';" onmouseover="tt_OnCloseBtnOver(1)" onmouseout="tt_OnCloseBtnOver(0)" onclick="tt_HideInit()">' + tt_aV[CLOSEBTNTEXT] + '</span></td>')
: '') + '</tr></tbody></table></div>')
: '') + '<div id="WzBoDy" style="position:relative;z-index:0;">' + '<table' + sTbTrTd + 'id="WzBoDyI" style="' + sCss + '">' + tt_sContent + '</td></tr></tbody></table></div>' + (tt_aV[SHADOW]
? ('<div id="WzTtShDwR" style="position:absolute;overflow:hidden;"></div>' + '<div id="WzTtShDwB" style="position:relative;overflow:hidden;"></div>')
: '') ); tt_GetSubDivRefs(); if(tt_t2t && !tt_aV[COPYCONTENT])
{ tt_t2tDad = tt_t2t.parentNode || tt_t2t.parentElement || tt_t2t.offsetParent || null; if(tt_t2tDad)
{ tt_MovDomNode(tt_t2t, tt_t2tDad, tt_aElt[6]); tt_t2t.style.display = "block";}
}
tt_ExtCallFncs(0, "SubDivsCreated");}
function tt_GetSubDivRefs()
{ var aId = new Array("WzTiTl", "WzTiTlTb", "WzTiTlI", "WzClOsE", "WzBoDy", "WzBoDyI", "WzTtShDwB", "WzTtShDwR"); for(var i = aId.length; i; --i)
tt_aElt[i] = tt_GetElt(aId[i - 1]);}
function tt_FormatTip()
{ var css, w, h, pad = tt_aV[PADDING], padT, wBrd = tt_aV[BORDERWIDTH], iOffY, iOffSh, iAdd = (pad + wBrd) << 1; if(tt_aV[TITLE].length)
{ padT = tt_aV[TITLEPADDING]; css = tt_aElt[1].style; css.background = tt_aV[TITLEBGCOLOR]; css.paddingTop = css.paddingBottom = padT + "px"; css.paddingLeft = css.paddingRight = (padT + 2) + "px"; css = tt_aElt[3].style; css.color = tt_aV[TITLEFONTCOLOR]; if(tt_aV[WIDTH] == -1)
css.whiteSpace = "nowrap"; css.fontFamily = tt_aV[TITLEFONTFACE]; css.fontSize = tt_aV[TITLEFONTSIZE]; css.fontWeight = "bold"; css.textAlign = tt_aV[TITLEALIGN]; if(tt_aElt[4])
{ css = tt_aElt[4].style; css.background = tt_aV[CLOSEBTNCOLORS][0]; css.color = tt_aV[CLOSEBTNCOLORS][1]; css.fontFamily = tt_aV[TITLEFONTFACE]; css.fontSize = tt_aV[TITLEFONTSIZE]; css.fontWeight = "bold";}
if(tt_aV[WIDTH] > 0)
tt_w = tt_aV[WIDTH]; else
{ tt_w = tt_GetDivW(tt_aElt[3]) + tt_GetDivW(tt_aElt[4]); if(tt_aElt[4])
tt_w += pad; if(tt_aV[WIDTH] < -1 && tt_w > -tt_aV[WIDTH])
tt_w = -tt_aV[WIDTH];}
iOffY = -wBrd;}
else
{ tt_w = 0; iOffY = 0;}
css = tt_aElt[5].style; css.top = iOffY + "px"; if(wBrd)
{ css.borderColor = tt_aV[BORDERCOLOR]; css.borderStyle = tt_aV[BORDERSTYLE]; css.borderWidth = wBrd + "px";}
if(tt_aV[BGCOLOR].length)
css.background = tt_aV[BGCOLOR]; if(tt_aV[BGIMG].length)
css.backgroundImage = "url(" + tt_aV[BGIMG] + ")"; css.padding = pad + "px"; css.textAlign = tt_aV[TEXTALIGN]; if(tt_aV[HEIGHT])
{ css.overflow = "auto"; if(tt_aV[HEIGHT] > 0)
css.height = (tt_aV[HEIGHT] + iAdd) + "px"; else
tt_h = iAdd - tt_aV[HEIGHT];}
css = tt_aElt[6].style; css.color = tt_aV[FONTCOLOR]; css.fontFamily = tt_aV[FONTFACE]; css.fontSize = tt_aV[FONTSIZE]; css.fontWeight = tt_aV[FONTWEIGHT]; css.background = ""; css.textAlign = tt_aV[TEXTALIGN]; if(tt_aV[WIDTH] > 0)
w = tt_aV[WIDTH]; else if(tt_aV[WIDTH] == -1 && tt_w)
w = tt_w; else
{ w = tt_GetDivW(tt_aElt[6]); if(tt_aV[WIDTH] < -1 && w > -tt_aV[WIDTH])
w = -tt_aV[WIDTH];}
if(w > tt_w)
tt_w = w; tt_w += iAdd; if(tt_aV[SHADOW])
{ tt_w += tt_aV[SHADOWWIDTH]; iOffSh = Math.floor((tt_aV[SHADOWWIDTH] * 4) / 3); css = tt_aElt[7].style; css.top = iOffY + "px"; css.left = iOffSh + "px"; css.width = (tt_w - iOffSh - tt_aV[SHADOWWIDTH]) + "px"; css.height = tt_aV[SHADOWWIDTH] + "px"; css.background = tt_aV[SHADOWCOLOR]; css = tt_aElt[8].style; css.top = iOffSh + "px"; css.left = (tt_w - tt_aV[SHADOWWIDTH]) + "px"; css.width = tt_aV[SHADOWWIDTH] + "px"; css.background = tt_aV[SHADOWCOLOR];}
else
iOffSh = 0; tt_SetTipOpa(tt_aV[FADEIN] ? 0 : tt_aV[OPACITY]); tt_FixSize(iOffY, iOffSh);}
function tt_FixSize(iOffY, iOffSh)
{ var wIn, wOut, h, add, pad = tt_aV[PADDING], wBrd = tt_aV[BORDERWIDTH], i; tt_aElt[0].style.width = tt_w + "px"; tt_aElt[0].style.pixelWidth = tt_w; wOut = tt_w - ((tt_aV[SHADOW]) ? tt_aV[SHADOWWIDTH] : 0); wIn = wOut; if(!tt_bBoxOld)
wIn -= (pad + wBrd) << 1; tt_aElt[5].style.width = wIn + "px"; if(tt_aElt[1])
{ wIn = wOut - ((tt_aV[TITLEPADDING] + 2) << 1); if(!tt_bBoxOld)
wOut = wIn; tt_aElt[1].style.width = wOut + "px"; tt_aElt[2].style.width = wIn + "px";}
if(tt_h)
{ h = tt_GetDivH(tt_aElt[5]); if(h > tt_h)
{ if(!tt_bBoxOld)
tt_h -= (pad + wBrd) << 1; tt_aElt[5].style.height = tt_h + "px";}
}
tt_h = tt_GetDivH(tt_aElt[0]) + iOffY; if(tt_aElt[8])
tt_aElt[8].style.height = (tt_h - iOffSh) + "px"; i = tt_aElt.length - 1; if(tt_aElt[i])
{ tt_aElt[i].style.width = tt_w + "px"; tt_aElt[i].style.height = tt_h + "px";}
}
function tt_DeAlt(el)
{ var aKid; if(el)
{ if(el.alt)
el.alt = ""; if(el.title)
el.title = ""; aKid = el.childNodes || el.children || null; if(aKid)
{ for(var i = aKid.length; i;)
tt_DeAlt(aKid[--i]);}
}
}
function tt_OpDeHref(el)
{ if(!tt_op)
return; if(tt_elDeHref)
tt_OpReHref(); while(el)
{ if(el.hasAttribute("href"))
{ el.t_href = el.getAttribute("href"); el.t_stats = window.status; el.removeAttribute("href"); el.style.cursor = "hand"; tt_AddEvtFnc(el, "mousedown", tt_OpReHref); window.status = el.t_href; tt_elDeHref = el; break;}
el = el.parentElement;}
}
function tt_OpReHref()
{ if(tt_elDeHref)
{ tt_elDeHref.setAttribute("href", tt_elDeHref.t_href); tt_RemEvtFnc(tt_elDeHref, "mousedown", tt_OpReHref); window.status = tt_elDeHref.t_stats; tt_elDeHref = null;}
}
function tt_OverInit()
{ if(window.event)
tt_over = window.event.target || window.event.srcElement; else
tt_over = tt_ovr_; tt_DeAlt(tt_over); tt_OpDeHref(tt_over);}
function tt_ShowInit()
{ tt_tShow.Timer("tt_Show()", tt_aV[DELAY], true); if(tt_aV[CLICKCLOSE] || tt_aV[CLICKSTICKY])
tt_AddEvtFnc(document, "mouseup", tt_OnLClick);}
function tt_Show()
{ var css = tt_aElt[0].style; css.zIndex = Math.max((window.dd && dd.z) ? (dd.z + 2) : 0, 1010); if(tt_aV[STICKY] || !tt_aV[FOLLOWMOUSE])
tt_iState &= ~0x4; if(tt_aV[DURATION] > 0)
tt_tDurt.Timer("tt_HideInit()", tt_aV[DURATION], true); tt_ExtCallFncs(0, "Show")
css.visibility = "visible"; tt_iState |= 0x2; if(tt_aV[FADEIN])
tt_Fade(0, 0, tt_aV[OPACITY], Math.round(tt_aV[FADEIN] / tt_aV[FADEINTERVAL])); tt_ShowIfrm();}
function tt_ShowIfrm()
{ if(tt_ie56)
{ var ifrm = tt_aElt[tt_aElt.length - 1]; if(ifrm)
{ var css = ifrm.style; css.zIndex = tt_aElt[0].style.zIndex - 1; css.display = "block";}
}
}
function tt_Move(e)
{ if(e)
tt_ovr_ = e.target || e.srcElement; e = e || window.event; if(e)
{ tt_musX = tt_GetEvtX(e); tt_musY = tt_GetEvtY(e);}
if(tt_iState & 0x04)
{ if(!tt_op && !tt_ie)
{ if(tt_bWait)
return; tt_bWait = true; tt_tWaitMov.Timer("tt_bWait = false;", 1, true);}
if(tt_aV[FIX])
{ var iY = tt_aV[FIX][1]; if(tt_aV[ABOVE])
iY -= tt_h; tt_iState &= ~0x4; tt_SetTipPos(tt_aV[FIX][0], tt_aV[FIX][1]);}
else if(!tt_ExtCallFncs(e, "MoveBefore"))
tt_SetTipPos(tt_Pos(0), tt_Pos(1)); tt_ExtCallFncs([tt_musX, tt_musY], "MoveAfter")
}
}
function tt_Pos(iDim)
{ var iX, bJmpMode, cmdAlt, cmdOff, cx, iMax, iScrl, iMus, bJmp; if(iDim)
{ bJmpMode = tt_aV[JUMPVERT]; cmdAlt = ABOVE; cmdOff = OFFSETY; cx = tt_h; iMax = tt_maxPosY; iScrl = tt_scrlY; iMus = tt_musY; bJmp = tt_bJmpVert;}
else
{ bJmpMode = tt_aV[JUMPHORZ]; cmdAlt = LEFT; cmdOff = OFFSETX; cx = tt_w; iMax = tt_maxPosX; iScrl = tt_scrlX; iMus = tt_musX; bJmp = tt_bJmpHorz;}
if(bJmpMode)
{ if(tt_aV[cmdAlt] && (!bJmp || tt_CalcPosAlt(iDim) >= iScrl + 16))
iX = tt_PosAlt(iDim); else if(!tt_aV[cmdAlt] && bJmp && tt_CalcPosDef(iDim) > iMax - 16)
iX = tt_PosAlt(iDim); else
iX = tt_PosDef(iDim);}
else
{ iX = iMus; if(tt_aV[cmdAlt])
iX -= cx + tt_aV[cmdOff] - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0); else
iX += tt_aV[cmdOff];}
if(iX > iMax)
iX = bJmpMode ? tt_PosAlt(iDim) : iMax; if(iX < iScrl)
iX = bJmpMode ? tt_PosDef(iDim) : iScrl; return iX;}
function tt_PosDef(iDim)
{ if(iDim)
tt_bJmpVert = tt_aV[ABOVE]; else
tt_bJmpHorz = tt_aV[LEFT]; return tt_CalcPosDef(iDim);}
function tt_PosAlt(iDim)
{ if(iDim)
tt_bJmpVert = !tt_aV[ABOVE]; else
tt_bJmpHorz = !tt_aV[LEFT]; return tt_CalcPosAlt(iDim);}
function tt_CalcPosDef(iDim)
{ return iDim ? (tt_musY + tt_aV[OFFSETY]) : (tt_musX + tt_aV[OFFSETX]);}
function tt_CalcPosAlt(iDim)
{ var cmdOff = iDim ? OFFSETY : OFFSETX; var dx = tt_aV[cmdOff] - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0); if(tt_aV[cmdOff] > 0 && dx <= 0)
dx = 1; return((iDim ? (tt_musY - tt_h) : (tt_musX - tt_w)) - dx);}
function tt_Fade(a, now, z, n)
{ if(n)
{ now += Math.round((z - now) / n); if((z > a) ? (now >= z) : (now <= z))
now = z; else
tt_tFade.Timer("tt_Fade(" + a + "," + now + "," + z + "," + (n - 1) + ")", tt_aV[FADEINTERVAL], true);}
now ? tt_SetTipOpa(now) : tt_Hide();}
function tt_SetTipOpa(opa)
{ tt_SetOpa(tt_aElt[5], opa); if(tt_aElt[1])
tt_SetOpa(tt_aElt[1], opa); if(tt_aV[SHADOW])
{ opa = Math.round(opa * 0.8); tt_SetOpa(tt_aElt[7], opa); tt_SetOpa(tt_aElt[8], opa);}
}
function tt_OnScrl()
{ tt_scrlX = tt_GetScrollX(); tt_scrlY = tt_GetScrollY();}
function tt_OnCloseBtnOver(iOver)
{ var css = tt_aElt[4].style; iOver <<= 1; css.background = tt_aV[CLOSEBTNCOLORS][iOver]; css.color = tt_aV[CLOSEBTNCOLORS][iOver + 1];}
function tt_OnLClick(e)
{ e = e || window.event; if(!((e.button && e.button & 2) || (e.which && e.which == 3)))
{ if(tt_aV[CLICKSTICKY] && (tt_iState & 0x4))
{ tt_aV[STICKY] = true; tt_iState &= ~0x4;}
else if(tt_aV[CLICKCLOSE])
tt_HideInit();}
}
function tt_Int(x)
{ var y; return(isNaN(y = parseInt(x)) ? 0 : y);}
Number.prototype.Timer = function(s, iT, bUrge)
{ if(!this.value || bUrge)
this.value = window.setTimeout(s, iT);}
Number.prototype.EndTimer = function()
{ if(this.value)
{ window.clearTimeout(this.value); this.value = 0;}
}
function tt_SetOpa(el, opa)
{ var css = el.style; tt_opa = opa; if(tt_flagOpa == 1)
{ if(opa < 100)
{ if(typeof(el.filtNo) == tt_u)
el.filtNo = css.filter; var bVis = css.visibility != "hidden"; css.zoom = "100%"; if(!bVis)
css.visibility = "visible"; css.filter = "alpha(opacity=" + opa + ")"; if(!bVis)
css.visibility = "hidden";}
else if(typeof(el.filtNo) != tt_u)
css.filter = el.filtNo;}
else
{ opa /= 100.0; switch(tt_flagOpa)
{ case 2:
css.KhtmlOpacity = opa; break; case 3:
css.KHTMLOpacity = opa; break; case 4:
css.MozOpacity = opa; break; case 5:
css.opacity = opa; break;}
}
}
function tt_MovDomNode(el, dadFrom, dadTo)
{ if(dadFrom)
dadFrom.removeChild(el); if(dadTo)
dadTo.appendChild(el);}
function tt_Err(sErr, bIfDebug)
{ if(tt_Debug || !bIfDebug)
alert("Tooltip Script Error Message:\n\n" + sErr);}
function tt_ExtCmdEnum()
{ var s; for(var i in config)
{ s = "window." + i.toString().toUpperCase(); if(eval("typeof(" + s + ") == tt_u"))
{ eval(s + " = " + tt_aV.length); tt_aV[tt_aV.length] = null;}
}
}
function tt_ExtCallFncs(arg, sFnc)
{ var b = false; for(var i = tt_aExt.length; i;)
{--i; var fnc = tt_aExt[i]["On" + sFnc]; if(fnc && fnc(arg))
b = true;}
return b;}
tt_Init(); 