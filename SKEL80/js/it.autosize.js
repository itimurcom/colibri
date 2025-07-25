// ================ CRC ================
// version: 1.15.02
// hash: be9e1fb6e95f3aa372d57cb28bc815c8e9b01872706e1d4d04af85a94ca5a9e9
// date: 24 August 2019 16:44
// ================ CRC ================
//!function(e,t){if("function"==typeof define&&define.amd)define(["exports","module"],t);else if("undefined"!=typeof exports&&"undefined"!=typeof module)t(exports,module);else{var n={exports:{}};t(n.exports,n),e.autosize=n.exports}}(this,function(e,t){"use strict";function n(e){function t(){var t=window.getComputedStyle(e,null);"vertical"===t.resize?e.style.resize="none":"both"===t.resize&&(e.style.resize="horizontal"),l="content-box"===t.boxSizing?-(parseFloat(t.paddingTop)+parseFloat(t.paddingBottom)):parseFloat(t.borderTopWidth)+parseFloat(t.borderBottomWidth),isNaN(l)&&(l=0),a()}function n(t){var n=e.style.width;e.style.width="0px",e.offsetWidth,e.style.width=n,e.style.overflowY=t,r()}function o(e){for(var t=[];e&&e.parentNode&&e.parentNode instanceof Element;)e.parentNode.scrollTop&&t.push({node:e.parentNode,scrollTop:e.parentNode.scrollTop}),e=e.parentNode;return t}function r(){var t=e.style.height,n=o(e),r=document.documentElement&&document.documentElement.scrollTop;e.style.height="auto";var i=e.scrollHeight+l;return 0===e.scrollHeight?void(e.style.height=t):(e.style.height=i+"px",s=e.clientWidth,n.forEach(function(e){e.node.scrollTop=e.scrollTop}),void(r&&(document.documentElement.scrollTop=r)))}function a(){r();var t=window.getComputedStyle(e,null),o=Math.round(parseFloat(t.height)),i=Math.round(parseFloat(e.style.height));if(o!==i?"visible"!==t.overflowY&&n("visible"):"hidden"!==t.overflowY&&n("hidden"),u!==o){u=o;var a=d("autosize:resized");e.dispatchEvent(a)}}if(e&&e.nodeName&&"TEXTAREA"===e.nodeName&&!i.has(e)){var l=null,s=e.clientWidth,u=null,c=function(){e.clientWidth!==s&&a()},p=function(t){window.removeEventListener("resize",c,!1),e.removeEventListener("input",a,!1),e.removeEventListener("keyup",a,!1),e.removeEventListener("autosize:destroy",p,!1),e.removeEventListener("autosize:update",a,!1),i["delete"](e),Object.keys(t).forEach(function(n){e.style[n]=t[n]})}.bind(e,{height:e.style.height,resize:e.style.resize,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",p,!1),"onpropertychange"in e&&"oninput"in e&&e.addEventListener("keyup",a,!1),window.addEventListener("resize",c,!1),e.addEventListener("input",a,!1),e.addEventListener("autosize:update",a,!1),i.add(e),e.style.overflowX="hidden",e.style.wordWrap="break-word",t()}}function o(e){if(e&&e.nodeName&&"TEXTAREA"===e.nodeName){var t=d("autosize:destroy");e.dispatchEvent(t)}}function r(e){if(e&&e.nodeName&&"TEXTAREA"===e.nodeName){var t=d("autosize:update");e.dispatchEvent(t)}}var i="function"==typeof Set?new Set:function(){var e=[];return{has:function(t){return Boolean(e.indexOf(t)>-1)},add:function(t){e.push(t)},"delete":function(t){e.splice(e.indexOf(t),1)}}}(),d=function(e){return new Event(e)};try{new Event("test")}catch(a){d=function(e){var t=document.createEvent("Event");return t.initEvent(e,!0,!1),t}}var l=null;"undefined"==typeof window||"function"!=typeof window.getComputedStyle?(l=function(e){return e},l.destroy=function(e){return e},l.update=function(e){return e}):(l=function(e,t){return e&&Array.prototype.forEach.call(e.length?e:[e],function(e){return n(e,t)}),e},l.destroy=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],o),e},l.update=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],r),e}),t.exports=l});
! function(e, t) {
    if ("function" == typeof define && define.amd) define(["exports", "module"], t);
    else if ("undefined" != typeof exports && "undefined" != typeof module) t(exports, module);
    else {
        var n = {
            exports: {}
        };
        t(n.exports, n), e.autosize = n.exports
    }
}(this, function(e, t) {
    "use strict";

    function n(e) {
        function t() {
            var t = window.getComputedStyle(e, null);
            "vertical" === t.resize ? e.style.resize = "none" : "both" === t.resize && (e.style.resize = "horizontal"), l = "content-box" === t.boxSizing ? -(parseFloat(t.paddingTop) + parseFloat(t.paddingBottom)) : parseFloat(t.borderTopWidth) + parseFloat(t.borderBottomWidth), isNaN(l) && (l = 0), a()
        }

        function n(t) {
            var n = e.style.width;
            e.style.width = "0px", e.offsetWidth, e.style.width = n, e.style.overflowY = t, r()
        }

        function o(e) {
            for (var t = []; e && e.parentNode && e.parentNode instanceof Element;) e.parentNode.scrollTop && t.push({
                node: e.parentNode,
                scrollTop: e.parentNode.scrollTop
            }), e = e.parentNode;
            return t
        }

        function r() {
            var t = e.style.height,
                n = o(e),
                r = document.documentElement && document.documentElement.scrollTop;
            e.style.height = "auto";
            var i = e.scrollHeight + l;
            return 0 === e.scrollHeight ? void(e.style.height = t) : (e.style.height = i + "px", s = e.clientWidth, n.forEach(function(e) {
                e.node.scrollTop = e.scrollTop
            }), void(r && (document.documentElement.scrollTop = r)))
        }

        function a() {
            r();
            var t = window.getComputedStyle(e, null),
                o = Math.round(parseFloat(t.height)),
                i = Math.round(parseFloat(e.style.height));
            if (o !== i ? "visible" !== t.overflowY && n("visible") : "hidden" !== t.overflowY && n("hidden"), u !== o) {
                u = o;
                var a = d("autosize:resized");
                e.dispatchEvent(a)
            }
        }
        if (e && e.nodeName && "TEXTAREA" === e.nodeName && !i.has(e)) {
            var l = null,
                s = e.clientWidth,
                u = null,
                c = function() {
                    e.clientWidth !== s && a()
                },
                p = function(t) {
                    window.removeEventListener("resize", c, !1), e.removeEventListener("input", a, !1), e.removeEventListener("keyup", a, !1), e.removeEventListener("autosize:destroy", p, !1), e.removeEventListener("autosize:update", a, !1), i["delete"](e), Object.keys(t).forEach(function(n) {
                        e.style[n] = t[n]
                    })
                }.bind(e, {
                    height: e.style.height,
                    resize: e.style.resize,
                    overflowY: e.style.overflowY,
                    overflowX: e.style.overflowX,
                    wordWrap: e.style.wordWrap
                });
            e.addEventListener("autosize:destroy", p, !1), "onpropertychange" in e && "oninput" in e && e.addEventListener("keyup", a, !1), window.addEventListener("resize", c, !1), e.addEventListener("input", a, !1), e.addEventListener("autosize:update", a, !1), i.add(e), e.style.overflowX = "hidden", e.style.wordWrap = "break-word", t()
        }
    }

    function o(e) {
        if (e && e.nodeName && "TEXTAREA" === e.nodeName) {
            var t = d("autosize:destroy");
            e.dispatchEvent(t)
        }
    }

    function r(e) {
        if (e && e.nodeName && "TEXTAREA" === e.nodeName) {
            var t = d("autosize:update");
            e.dispatchEvent(t)
        }
    }
    var i = "function" == typeof Set ? new Set : function() {
            var e = [];
            return {
                has: function(t) {
                    return Boolean(e.indexOf(t) > -1)
                },
                add: function(t) {
                    e.push(t)
                },
                "delete": function(t) {
                    e.splice(e.indexOf(t), 1)
                }
            }
        }(),
        d = function(e) {
            return new Event(e)
        };
    try {
        new Event("test")
    } catch (a) {
        d = function(e) {
            var t = document.createEvent("Event");
            return t.initEvent(e, !0, !1), t
        }
    }
    var l = null;
    "undefined" == typeof window || "function" != typeof window.getComputedStyle ? (l = function(e) {
        return e
    }, l.destroy = function(e) {
        return e
    }, l.update = function(e) {
        return e
    }) : (l = function(e, t) {
        return e && Array.prototype.forEach.call(e.length ? e : [e], function(e) {
            return n(e, t)
        }), e
    }, l.destroy = function(e) {
        return e && Array.prototype.forEach.call(e.length ? e : [e], o), e
    }, l.update = function(e) {
        return e && Array.prototype.forEach.call(e.length ? e : [e], r), e
    }), t.exports = l
});

$(document).ready(function()
	{
	set_autosize_events();
	});

function set_autosize_events()
	{
//	$('textarea').autosize();
	autosize($('textarea:not(.fixed)'));
	}
