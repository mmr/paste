function getCookie(name){
  var i;
  var cookie;
  var jar = document.cookie.split('; ');

  for(i = 0; i < jar.length; i++){
    cookie = jar[i].split('=');
    if(cookie[0] == escape(name)){
      return unescape(cookie[1]);
    }
  }
  return null;
}

function setCookie(
  name, value, lifetime, path, domain, is_secure)
{
  if(!name){
    return false;
  }

  document.cookie = escape(name) + '=' + escape(value) +
    (lifetime   ? ';expires=' + (new Date((new Date()).getTime() +
      (1000 * lifetime))).toGMTString() : '') +
    (path       ? ';path='    + path    : '') +
    (domain     ? ';domain='  + domain  : '') +
    (is_secure  ? ';secure' : '');

  //check if the cookie has been set/deleted as required
  if(lifetime < 0){
    if(typeof(getCookie(name)) == 'string'){
      return false;
    }
    return true;
  }
  if(typeof(getCookie(name)) == 'string'){
    return true;
  }
  return false;
}

