(function( $ ) {'use strict';

  var THRESHOLD = 100;
  var timeoutId;
  var resizing = false;
  var win = $(window);
  var dcw = $('#donutChart').width();
  var dds = $('#donutDesc > span');

  var pwidth = $('#donutChart').parent().width();
  var chart_data = [
  {
    "title": "\nMembres",
    "value": cpie1,
    "color": "#7bc126",
    "description": cdesc1
  },
  {
    "title": "\nHors groupes",
    "value": cpie2,
    "color": "#b1d635",
    "description": cdesc2
  }
  ];

  $('#donutChart').donutChart({
    data: chart_data,
    // height: 110,
    height: ($(window).width() < 991 ? (dcw * 50) / 100 : 200),
    innerRadius: ($(window).width() < 991 ? (dcw * 15) / 100 : 30),
    innerCicrleColor: "#FFF",
    font: {
      'font-size': '11px',
      'font-family': 'sans-serif'
    },
    labelColor: "#999",
    radius: ($(window).width() < 991 ? (dcw * 17) / 100 : 38),
    width: pwidth-10,
  });

  dds.css({
    'top': ( $(window).width() < 991 ? -($('#donutChart').height() * 50 / 100)+'px' : '-102px' ),
    'font-size': ($('#donutChart').height() * 20 / 100 )+'px'
  });

  win.on('resize', function () {
    var timeout = function () {
      clearTimeout(timeoutId);

      timeoutId = setTimeout(function () {
        resizing = false;
        win.trigger('resizeEnd');
        location.reload();
      }, THRESHOLD);
    };
    if (!resizing) {
      resizing = true;
      win.trigger('resizeStart');
      $('#donutChart').parent().hide();
    }
    timeout();
  });
})( jQuery );