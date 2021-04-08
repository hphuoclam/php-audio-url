$('#spotify-login').hide();
$(document).on('click', '#spotify-login', e => {
  e.preventDefault();
  window.location.href = "/auth.php";
})
$(document).on('submit', '#form', e => {
  e.preventDefault();
  const _result = $('#result');
  let _url = $('#url').val();
  if (_url) {
    let _send_data = {
      'url': _url,
    };
    if (_url.includes('spotify')) {
      if (!getUrlVars()['code']) {
        $('#spotify-login').show();
        _result.html('Please login with spotify');
        return false;
      } else {
        _send_data.code = getUrlVars()['code'];
      }
      $('#spotify-login').hide();
    }
    $.ajax({
      type: "POST",
      url: '/api.php',
      data: _send_data,
      beforeSend: () => {
        _result.html('Waiting ...'),
          window.history.pushState({}, document.title, "/");
      },
      success: res => {
        if (res.length > 0) {
          _result.html(`<ul>${res.map(v => v && (`<li><a href="${v}" download target="_blank">${v}</a></li>`))}</ul>`)
        } else {
          _result.html('No datas')
        }
      },
      error: () => {
        _result.html('Error!')
      }
    })
  }
})

function getUrlVars() {
  var vars = [],
    hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for (var i = 0; i < hashes.length; i++) {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}