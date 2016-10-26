function getURLVar(key) {
  var value = [];

  var query = String(document.location).split('?');

  if (query[1]) {
    var part = query[1].split('&');

    for (i = 0; i < part.length; i++) {
      var data = part[i].split('=');

      if (data[0] && data[1]) {
        value[data[0]] = data[1];
      }
    }

    if (value[key]) {
      return value[key];
    } else {
      return '';
    }
  }
}

// shopping cart product counter
function getQuantity() {
  var nop = document.getElementById("cart-total").innerHTML;

  nop = nop.replace(/(^\d+)(.+$)/i,'$1');

  document.getElementById("cart-total").innerHTML = nop;

  if (document.getElementById("cart-total").innerHTML !== "0") {
    document.getElementById("cart-total").style.display = "block";
  }
}

$(document).ready(function() {
  // adding the clear Fix
  cols1 = $('#column-right, #column-left').length;

  if (cols1 == 2) {
    $('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
  } else if (cols1 == 1) {
    $('#content .product-layout:nth-child(3n+3)').after('<div class="clearfix visible-lg"></div>');
  } else {
    $('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix"></div>');
  }

  // highlight any found errors
  $('.text-danger').each(function() {
    var element = $(this).parent().parent();

    if (element.hasClass('form-group')) {
      element.addClass('has-error');
    }
  });

  // currency
  $('#form-currency .currency-select').on('click', function(e) {
    e.preventDefault();

    $('#form-currency input[name=\'code\']').attr('value', $(this).attr('name'));

    $('#form-currency').submit();
  });

  // language
  $('#form-language .language-select').on('click', function(e) {
    e.preventDefault();

    $('#form-language input[name=\'code\']').attr('value', $(this).attr('name'));

    $('#form-language').submit();
  });

  // search
  $('#search input[name=\'search\']').parent().find('button').on('click', function() {
    url = $('base').attr('href') + 'index.php?route=product/search';

    var value = $('nav input[name=\'search\']').val();

    if (value) {
      url += '&search=' + encodeURIComponent(value);
    }

    location = url;
  });

  $('#search input[name=\'search\']').on('keydown', function(e) {
    if (e.keyCode == 13) {
      $('nav input[name=\'search\']').parent().find('button').trigger('click');
    }
  });

  // product list
  $('#list-view').click(function() {
    $('#content .product-layout > .clearfix').remove();

    $('#content .row > .product-layout').attr('class', 'product-layout product-list col-xs-12');

    localStorage.setItem('display', 'list');
  });

  // product grid
  $('#grid-view').click(function() {
    $('#content .product-layout > .clearfix').remove();

    // what a shame bootstrap does not take into account dynamically loaded columns
    cols = $('#column-right, #column-left').length;

    if (cols == 2) {
      $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
    } else if (cols == 1) {
      $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
    } else {
      $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
    }

     localStorage.setItem('display', 'grid');
  });

  if (localStorage.getItem('display') == 'list') {
    $('#list-view').trigger('click');
  } else {
    $('#grid-view').trigger('click');
  }

  // checkout
  $(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
    if (e.keyCode == 13) {
      $('#collapse-checkout-option #button-login').trigger('click');
    }
  });

  // tooltips on hover
  $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

  // makes tooltips work on ajax generated content
  $(document).ajaxStop(function() {
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
  });

  getQuantity();

});

// cart add remove functions
var cart = {
  'add': function(product_id, quantity) {
    $.ajax({
      url: 'index.php?route=checkout/cart/add',
      type: 'post',
      data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
      dataType: 'json',
      complete: function() {
        $('#cart > a').button('reset');
      },
      success: function(json) {
        $('.alert, .text-danger').remove();

        if (json.redirect) {
          location = json.redirect;
        }

        if (json.success) {
          $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          // need to set timeout otherwise it wont update the total
          setTimeout(function () {
            $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');

            getQuantity();

          }, 100);

          $('html, body').animate({ scrollTop: 0 }, 'slow');

          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  },
  'update': function(key, quantity) {
    $.ajax({
      url: 'index.php?route=checkout/cart/edit',
      type: 'post',
      data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
      dataType: 'json',
      complete: function() {
        $('#cart > a').button('reset');
      },
      success: function(json) {
        // need to set timeout otherwise it wont update the total
        setTimeout(function () {
          $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');

          getQuantity();

        }, 100);

        if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
          location = 'index.php?route=checkout/cart';
        } else {
          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  },
  'remove': function(key) {
    $.ajax({
      url: 'index.php?route=checkout/cart/remove',
      type: 'post',
      data: 'key=' + key,
      dataType: 'json',
      complete: function() {
        $('#cart > a').button('reset');
      },
      success: function(json) {
        // need to set timeout otherwise it wont update the total
        setTimeout(function () {
          $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');

          getQuantity();

        }, 100);

        if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
          location = 'index.php?route=checkout/cart';
        } else {
          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
};

var voucher = {
  'add': function() {

  },
  'remove': function(key) {
    $.ajax({
      url: 'index.php?route=checkout/cart/remove',
      type: 'post',
      data: 'key=' + key,
      dataType: 'json',
      complete: function() {
        $('#cart > a').button('reset');
      },
      success: function(json) {
        // need to set timeout otherwise it wont update the total
        setTimeout(function () {
          $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');

          getQuantity();

        }, 100);

        if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
          location = 'index.php?route=checkout/cart';
        } else {
          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
};

var wishlist = {
  'add': function(product_id) {
    $.ajax({
      url: 'index.php?route=account/wishlist/add',
      type: 'post',
      data: 'product_id=' + product_id,
      dataType: 'json',
      success: function(json) {
        $('.alert').remove();

        if (json.success) {
          $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }

        if (json.info) {
          $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json.info + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }

        $('#wishlist-total span').html(json.total);
        $('#wishlist-total').attr('title', json.total);

        $('html, body').animate({ scrollTop: 0 }, 'slow');
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  },
  'remove': function() {

  }
};

var compare = {
  'add': function(product_id) {
    $.ajax({
      url: 'index.php?route=product/compare/add',
      type: 'post',
      data: 'product_id=' + product_id,
      dataType: 'json',
      success: function(json) {
        $('.alert').remove();

        if (json.success) {
          $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          $('#compare-total').html(json.total);

          $('html, body').animate({ scrollTop: 0 }, 'slow');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  },
  'remove': function() {

  }
};

// agree to terms
$(document).delegate('.agree', 'click', function(e) {
  e.preventDefault();

  $('#modal-agree').remove();

  var element = this;

  $.ajax({
    url: $(element).attr('href'),
    type: 'get',
    dataType: 'html',
    success: function(data) {
      html  = '<div id="modal-agree" class="modal">';
      html += '  <div class="modal-dialog">';
      html += '    <div class="modal-content">';
      html += '      <div class="modal-header">';
      html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
      html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
      html += '      </div>';
      html += '      <div class="modal-body">' + data + '</div>';
      html += '    </div';
      html += '  </div>';
      html += '</div>';

      $('body').append(html);

      $('#modal-agree').modal('show');
    }
  });
});

// autocomplete
(function($) {
  $.fn.autocomplete = function(option) {
    return this.each(function() {
      this.timer = null;
      this.items = [];

      $.extend(this, option);

      $(this).attr('autocomplete', 'off');

      // focus
      $(this).on('focus', function() {
        this.request();
      });

      // blur
      $(this).on('blur', function() {
        setTimeout(function(object) {
          object.hide();
        }, 200, this);
      });

      // keydown
      $(this).on('keydown', function(event) {
        switch(event.keyCode) {
          case 27: // escape
            this.hide();
            break;
          default:
            this.request();
            break;
        }
      });

      // click
      this.click = function(event) {
        event.preventDefault();

        value = $(event.target).parent().attr('data-value');

        if (value && this.items[value]) {
          this.select(this.items[value]);
        }
      };

      // show
      this.show = function() {
        var pos = $(this).position();

        $(this).siblings('ul.dropdown-menu').css({
          top: pos.top + $(this).outerHeight(),
          left: pos.left
        });

        $(this).siblings('ul.dropdown-menu').show();
      };

      // hide
      this.hide = function() {
        $(this).siblings('ul.dropdown-menu').hide();
      };

      // request
      this.request = function() {
        clearTimeout(this.timer);

        this.timer = setTimeout(function(object) {
          object.source($(object).val(), $.proxy(object.response, object));
        }, 200, this);
      };

      // response
      this.response = function(json) {
        html = '';

        if (json.length) {
          for (i = 0; i < json.length; i++) {
            this.items[json[i].value] = json[i];
          }

          for (i = 0; i < json.length; i++) {
            if (!json[i].category) {
              html += '<li data-value="' + json[i].value + '"><a href="#">' + json[i].label + '</a></li>';
            }
          }

          // get all the ones with a categories
          var category = [];

          for (i = 0; i < json.length; i++) {
            if (json[i].category) {
              if (!category[json[i].category]) {
                category[json[i].category] = [];
                category[json[i].category].name = json[i].category;
                category[json[i].category].item = [];
              }

              category[json[i].category].item.push(json[i]);
            }
          }

          for (var i in category) {
            html += '<li class="dropdown-header">' + category[i].name + '</li>';

            for (j = 0; j < category[i].item.length; j++) {
              html += '<li data-value="' + category[i].item[j].value + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i].item[j].label + '</a></li>';
            }
          }
        }

        if (html) {
          this.show();
        } else {
          this.hide();
        }

        $(this).siblings('ul.dropdown-menu').html(html);
      };

      $(this).after('<ul class="dropdown-menu"></ul>');
      $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this)); 

    });
  };
})(window.jQuery);
