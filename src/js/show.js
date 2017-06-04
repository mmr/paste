/*
 * $Id: show.js,v 1.7 2005/02/06 23:36:41 mmr Exp $
 * mmr 2005-02-05
 */

var save_margin;
function showLinesToggle(o){
  var ol = document.getElementById('geshi_ol').style;

  if(o.checked){
    ol.listStyleType = 'decimal';

    ol.marginLeft = save_margin;
  }
  else {
    ol.listStyleType = 'none';
    save_margin = ol.marginLeft;
    ol.marginLeft = 0;
  }

  // Setting the cookie (lifetime: 2592000); 60*60*24*30 (30 days in sec)
  //setCookie('line_numbers', o.checked, 2592000);
}

var save_color  = new Array();
var save_style  = new Array();
var save_weight = new Array();
function showHighlightToggle(o){
  var s = document.getElementById("geshi_ol").getElementsByTagName("span");

  if(o.checked){
    for(i=0; i<s.length; i++){
      s[i].style.color      = save_color[i];
      s[i].style.fontStyle  = save_style[i];
      s[i].style.fontWeight = save_weight[i];
    }
  }
  else {
    for(i=0; i<s.length; i++){
      save_color[i]  = s[i].style.color;
      save_style[i]  = s[i].style.fontStyle;
      save_weight[i] = s[i].style.fontWeight;

      s[i].style.color      = '#000';
      s[i].style.fontStyle  = 'normal';
      s[i].style.fontWeight = 'normal';
    }
  }

  // Setting the cookie (lifetime: 2592000); 60*60*24*30 (30 days in sec)
  //setCookie('highlight', o.checked, 2592000);
}

function getDataText(o){
  var f = o.form;
  f.elements['action'].value = 'text';
  f.target = '_blank';
  f.submit();
}
