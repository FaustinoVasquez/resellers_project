var typingTimer;                //timer identifier
var doneTypingInterval = 750;  //time in ms, 5 second for example

$(document).ready(function () {
  $('#quicksearch').DataTable({
    dom: "tr",
    order: [],
    serverSide: true,
    processing: true,
    ajax: "getRecentOrders"
  });
  $('<div class="loading-chart"><i class="fa fa-spinner fa-spin"/></div>').appendTo("#table-salesAll");
  $('<div class="loading-chart"><i class="fa fa-spinner fa-spin"/></div>').appendTo("#table-salesPaid");
  $('<div class="loading-chart"><i class="fa fa-spinner fa-spin"/></div>').appendTo("#table-salesPending");
  initPlots("#table-salesAll", "salesAll?start=2016&end=2015");
  initPlots("#table-salesPaid", "salesPaid?start=2016&end=2015");
  initPlots("#table-salesPending", "salesPending?start=2016&end=2015");
  $("<div id='tooltip'></div>").css({
    position: "absolute",
    display: "none",
    border: "1px solid #fdd",
    padding: "2px",
    "background-color": "#fee",
    opacity: 0.80
  }).appendTo("body");
  //I had to do this little fix because for some odd reason, Datatables was conflicting with Bootstrap dropdown menu.
  $("#name-drop").dropdown();
  $("#button-cart").dropdown();
})

//Handlers

$("#quicksearch-input").keyup(function (e) {
  if (e.which <= 90 && e.which >= 48 || e.which <= 105 && e.which >= 96 || e.which == 8 || e.which == 46 || e.which == 13) {
    var text = this.value;
    if (e.which == 13) {
      var table = $('#quicksearch').DataTable();
      table.search(text).draw();
    }
    else {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(function () {
        var table = $('#quicksearch').DataTable();
        table.search(text).draw();
      }, doneTypingInterval);
    }
  }
})

$("form").submit(function (e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var table = $(this).next();
  $('<div class="loading-chart"><i class="fa fa-spinner fa-spin"/></div>').appendTo(table);
  initPlots("#" + table[0].id, this.action + "?" + $(this).serialize());
})

$("#table-salesAll").bind("plothover", function (event, pos, item) {
  if (item) {
    var x = item.datapoint[0].toFixed(2),
      y = item.datapoint[1].toFixed(2);

    $("#tooltip").html(item.series.xaxis.ticks[item.dataIndex].label + " of " + item.series.label + " = $" + y + " USD")
      .css({ top: item.pageY + 5, left: item.pageX + 5 })
      .fadeIn(200);
  } else {
    $("#tooltip").hide();
  }
});

$("#table-salesPaid").bind("plothover", function (event, pos, item) {
  if (item) {
    var x = item.datapoint[0].toFixed(2),
      y = item.datapoint[1].toFixed(2);

    $("#tooltip").html(item.series.xaxis.ticks[item.dataIndex].label + " of " + item.series.label + " = $" + y + " USD")
      .css({ top: item.pageY + 5, left: item.pageX + 5 })
      .fadeIn(200);
  } else {
    $("#tooltip").hide();
  }
});

$("#table-salesPending").bind("plothover", function (event, pos, item) {
  if (item) {
    var x = item.datapoint[0].toFixed(2),
      y = item.datapoint[1].toFixed(2);

    $("#tooltip").html(item.series.xaxis.ticks[item.dataIndex].label + " of " + item.series.label + " = $" + y + " USD")
      .css({ top: item.pageY + 5, left: item.pageX + 5 })
      .fadeIn(200);
  } else {
    $("#tooltip").hide();
  }
});

$(document).on('click', '.btn-details', function (e) {
  e.preventDefault();
  window.location.hash = 'orderStatus';
  var id = $(this).data('id');
  $.get('orderDetails/' + id + '/Order Manager', function (result) {
    $("#details").html(result)
    $("#infoData").addClass('hidden');
    $("#details").removeClass('hidden').show("slow");
  });
});

$(document).on('click', ".btn-trk", function () {
  var value = this.id;
  $.get('getTrackingInformation', { orderNumber: value }, function (data) {
    var json = parseJSON(data);
    if (json) {
      console.log(json);
      if (json.length > 0) {
        for (var i = 0; i < json.length; i++) {
          var link = document.createElement('a');
          $(link).text(json[i].TrackingID);
          switch (json[i].Carrier) {
            case 'UPS':
              $(link).attr("href", "http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=en_us&InquiryNumber1=" + json[i].TrackingID);
              $(link).attr('target', '_blank');
              break;
            case 'USPS':
              $(link).attr("href", "http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=" + json[i].TrackingID);
              $(link).attr('target', '_blank');
              break;
            case 'FDXE':
              $(link).attr("href", "http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=" + json[i].TrackingID);
              $(link).attr('target', '_blank');
              break;
            case 'FXM':
              $(link).attr("href", "http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=" + json[i].TrackingID);
              $(link).attr('target', '_blank');
              break;
            case 'FDXG':
              $(link).attr("href", "http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=" + json[i].TrackingID);
              $(link).attr('target', '_blank');
              break;
            case 'ONTRC':
              $(link).attr("href", "https://www.ontrac.com/trackingres.asp?tracking_number=" + json[i].TrackingID);
              $(link).attr('target', '_blank');
              break;
          }
          $('#body-tracking-number').append(link);
        }
      }
      else {
        $('#body-tracking-number').append('<h3 class="text-muted text-center">This order doesn\'t have a tracking number yet.</h3>');
      }
      $("#modal-tracking-number").modal('show');
    }
  });
})

$("#modal-tracking-number").on('hidden.bs.modal', function () {
  $("#body-tracking-number").empty();
});

//Functions

function initPlots(holder, url) {
  $.get(url, function (data) {
    console.log(JSON.parse(data));
    console.log([{ label: "test", data: [[0, 3], [5, 4]] }]);
    var options = {
      grid: {
        hoverable: true
      },
      xaxis: {
        mode: "time",
        ticks: [[1, "Jan"], [2, "Feb"], [3, "Mar"], [4, "Apr"], [5, "May"], [6, "Jun"], [7, "Jul"], [8, "Aug"], [9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"]]
      },
      yaxis: {
        min: 0
      }
    }
    var flot = $(holder).data('plot');
    if (flot) // If it's destroyed, then data('plot') will be undefined
      flot.destroy();
    $.plot($(holder), JSON.parse(data), options);
  });
}

function checkOrderStatus(e) {
  if (location.hash === "") {
    $("#details").addClass('hidden');
    $("#infoData").removeClass('hidden').show("slow");
    e.preventDefault();
  }
}

window.onhashchange = checkOrderStatus;
