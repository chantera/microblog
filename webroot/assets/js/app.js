var App = (function($) {
  var _this = {};

  if (!('repeat' in String.prototype)) {
    String.prototype.repeat = function(num) {
      return Array(num + 1).join(this);
    };
  }

  var pad = function(str, len, pad_char, right) {
    str = '' + str;
    pad_char = '' + pad_char;
    if (str.length < len) {
      if (right) {
        str = str + pad_char.repeat(len - str.length);
      } else {
        str = pad_char.repeat(len - str.length) + str;
      }
    }
    return str;
  };

  var getDateTimeStr = function(date) {
    var str = date.getFullYear();
    str = str + '-' + pad(date.getMonth() + 1, 2, '0');
    str = str + '-' + pad(date.getDate(), 2, '0');
    str = str + ' ' + pad(date.getHours(), 2, '0');
    str = str + ':' + pad(date.getMinutes(), 2, '0');
    str = str + ':' + pad(date.getSeconds(), 2, '0');
    return str;
  };

  var addLink = function(str) {
    return str.replace(/(https?:\/\/[\x21-\x7e]+)/gi, "<a href=\"$1\" target=\"_blank\">$1</a>");
  };

  var append = function(data) {
    var post_date = new Date(data.post_date + ' UTC');
    var $message = $('<li>');
    $message.append('<header><span class="user">' + data.user + '</span><span class="date">' + getDateTimeStr(post_date) + '</span></header>');
    $message.append('<div class="body">' + addLink(data.body) + '</div>');
    $('.message').append($message);
  };

  var initialize = function(messages) {
    for (var i=0; i < messages.length; i++ ) {
      append(messages[i]);
    }
  };

  _this.init = initialize;

  return _this;
}(jQuery));
