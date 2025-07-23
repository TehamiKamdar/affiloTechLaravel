"use strict";

$(function () {
	changeChartData("sales", "#00a9da");
    top5AdvertChart();
    loadAdvertisersRecord();
	// select all on checkbox click
	$("[data-checkboxes]").each(function () {
		var me = $(this),
			group = me.data('checkboxes'),
			role = me.data('checkbox-role');

		me.change(function () {
			var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
				checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
				dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
				total = all.length,
				checked_length = checked.length;

			if (role == 'dad') {
				if (me.is(':checked')) {
					all.prop('checked', true);
				} else {
					all.prop('checked', false);
				}
			} else {
				if (checked_length >= total) {
					dad.prop('checked', true);
				} else {
					dad.prop('checked', false);
				}
			}
		});
	});



});

let salesChartInstance = null;

function changeChartData(type, color) {
    showLoader('salesChart');
    const typeColorMap = {
        sales: 'text-primary',
        approved: 'text-success',
        pending: 'text-warning',
        rejected: 'text-danger'
    };

    // Remove all highlights
    Object.keys(typeColorMap).forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('text-lg', ...Object.values(typeColorMap));
        }
    });

    // Add highlight to selected item
    const selectedItem = document.getElementById(type);
    if (selectedItem && typeColorMap[type]) {
        selectedItem.classList.add('text-lg', typeColorMap[type]);
    }

    fetch('/publisher/cart-data', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: type, color: color })
    })
    .then(response => response.json())
    .then(data => {
        if (salesChartInstance) {
            salesChartInstance.dispose();
        }

        const dataCombined = [];
        const currentMonthData = data.currentMonth;
        const previousMonthData = data.previousMonth;

        for (let i = 0; i < currentMonthData.length; i++) {
            dataCombined.push({
                date: currentMonthData[i].date, // must be "YYYY-MM-DD"
                current: currentMonthData[i].total,
                previous: previousMonthData[i] ? previousMonthData[i].total : 0
            });
        }

        const totals = data.totals;
        if (totals) {
            document.getElementById("sales").innerText = `$${totals.sales.toFixed(2)}`;
            document.getElementById("approved").innerText = `$${totals.approved.toFixed(2)}`;
            document.getElementById("pending").innerText = `$${totals.pending.toFixed(2)}`;
            document.getElementById("rejected").innerText = `$${totals.rejected.toFixed(2)}`;
        }

        let yAxisLabel = "";
        switch (data.type) {
            case 'sales': yAxisLabel = 'Sales'; break;
            case 'approved': yAxisLabel = 'Approved Commission'; break;
            case 'pending': yAxisLabel = 'Pending Commission'; break;
            case 'rejected': yAxisLabel = 'Rejected Commission'; break;
            case 'tracked': yAxisLabel = 'Tracked Commission'; break;
        }

        const titleEl = document.getElementById("chartTitle");
        if (titleEl) titleEl.innerText = yAxisLabel;

        am4core.useTheme(am4themes_animated);

        let chart = am4core.create("salesChart", am4charts.XYChart);
        salesChartInstance = chart;
        chart.data = dataCombined;

        // Date Axis with MM-DD format
        let dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.dataFields.date = "date";
        dateAxis.dateFormats.setKey("day", "MM-dd");
        dateAxis.periodChangeDateFormats.setKey("day", "MM-dd");
        dateAxis.title.text = "Date";
        dateAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");
        dateAxis.renderer.minGridDistance = 30;
        // ✅ Rotate the date labels
        dateAxis.renderer.labels.template.rotation = -45;
        dateAxis.renderer.labels.template.horizontalCenter = "right";
        dateAxis.renderer.labels.template.verticalCenter = "middle";
        // Value Axis
        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = yAxisLabel + " ($)";
        valueAxis.min = 0;
        valueAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");

        // Current Series
        let currentSeries = chart.series.push(new am4charts.LineSeries());
        currentSeries.dataFields.valueY = "current";
        currentSeries.dataFields.dateX = "date";
        currentSeries.name = "Current Month";
        currentSeries.strokeWidth = 3;
        currentSeries.stroke = am4core.color(color);
        currentSeries.tooltipText = "[bold]{dateX.formatDate('MM-dd')}[/]: {valueY}";
        currentSeries.tensionX = 0.8;

        let currentBullet = currentSeries.bullets.push(new am4charts.CircleBullet());
        currentBullet.circle.radius = 4;
        currentBullet.circle.fill = am4core.color(color);
        currentBullet.circle.strokeWidth = 2;

        // Previous Series
        let previousSeries = chart.series.push(new am4charts.LineSeries());
        previousSeries.dataFields.valueY = "previous";
        previousSeries.dataFields.dateX = "date";
        previousSeries.name = "Previous Month";
        previousSeries.strokeWidth = 2;
        previousSeries.stroke = am4core.color("#bbbbbb");
        previousSeries.strokeDasharray = "5,5";
        previousSeries.tooltipText = "[bold]{dateX.formatDate('MM-dd')}[/]: {valueY}";
        previousSeries.tensionX = 0.8;

        let previousBullet = previousSeries.bullets.push(new am4charts.CircleBullet());
        previousBullet.circle.radius = 4;
        previousBullet.circle.fill = am4core.color("#bbbbbb");
        previousBullet.circle.strokeWidth = 2;

        chart.cursor = new am4charts.XYCursor();
        chart.legend = new am4charts.Legend();
        chart.legend.position = "top";
        chart.legend.align = "right";
        chart.scrollbarX = new am4core.Scrollbar();
    })
    .finally(()=>{
        hideLoader('salesChart');
    });
}

function loadAdvertisersRecord(){
        showLoader('advertisersChart')
        am4core.ready(function () {
            am4core.useTheme(am4themes_animated);

            let publisher_id = document.getElementById('publisher_id').value;

            fetch(`/publisher/advertiser-status/${publisher_id}`)
                .then(response => response.json())
                .then(data => {
                    // Prepare data for amCharts
                    var chartData = [
                        { category: "Joined", value: data.joined, color: am4core.color("#28a745") },
                        { category: "Rejected", value: data.rejected, color: am4core.color("#dc3545") },
                        { category: "Pending", value: data.pending, color: am4core.color("#ffc107") },
                        { category: "Not Joined", value: data.not_joined, color: am4core.color("#00a9da") }
                    ];

                    // Create chart
                    var chart = am4core.create("advertisersChart", am4charts.PieChart);
                    chart.data = chartData;

                    // Create pie series
                    var pieSeries = chart.series.push(new am4charts.PieSeries());
                    pieSeries.dataFields.value = "value";
                    pieSeries.dataFields.category = "category";
                    pieSeries.slices.template.propertyFields.fill = "color";

                    // Donut style
                    pieSeries.innerRadius = am4core.percent(50);

                    // Legend
                    chart.legend = new am4charts.Legend();
                    chart.legend.position = "bottom";
                })
                .catch(error => console.error('Error fetching advertiser status:', error))
                .finally(()=>{
                     hideLoader('advertisersChart')
                })
        });
}


var swiper = new Swiper(".mySwiper", {
	slidesPerView: "auto", // Cards in a row
	spaceBetween: 20,
	loop: true,
	autoplay: {
		delay: 3000, // 3 seconds
		disableOnInteraction: true
	},
	pagination: {
		el: ".swiper-pagination",
		clickable: true
	},
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev"
	}
});
$("#advertiserDeeplinkForm").submit(function () {

    $("#deeplinkBottomWrapper").remove()
    $("#mainDeeplinkBody").removeClass("border-bottom mb-20")
    $("#deeplinkContent").html(loaderHTML());

    $("#mainDeeplinkBody").addClass('disableDiv');
    $("#showLoader").show();

    let url = "";

    if($("#landing_url").val())
    {
        url = '{{ route("publisher.deeplink.check-availability") }}';
    }
    else
    {
        url = '{{ route("publisher.tracking.check-availability") }}';
    }
    console.log($(this).serialize());

    $.ajax({
        url: url,
        type: 'POST',
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: $(this).serialize(),
        success: function (response) {
            setTimeout(() => {
                if(response.success)
                {
                    setDeeplinkContent(response);
                } else
                {
                    $("#deeplinkContent").html("");
                    console.log(response.message);
                    normalMsg({"message": response.message, "success": response.success});
                }
            }, 1000);
        },
        error: function (response) {
            showErrors(response)
            $("#deeplinkContent").html("");
        }
    }).done(function () {
        $("#mainDeeplinkBody").removeClass('disableDiv');
        $("#showLoader").hide();
    });
});

$(document).ready(function () {
    const $dropdown = $('#dropdownSelect');
    const $statusContainer = $('#deeplinkStatusContainer');
    const checkGif = "{{ $checkGif }}";
    const crossGif = "{{ $crossGif }}";


    function updateDeeplinkStatus() {
        const $selectedOption = $dropdown.find('option:selected');
        const isDeeplinkEnabled = $selectedOption.data('dd');

        $statusContainer.empty();

        if (isDeeplinkEnabled === 1) {
            $('#input1').removeAttr('disabled');
            $('#input2').removeAttr('disabled');
            $statusContainer.html(`
                <div class="d-flex align-items-center p-2 bg-light-success rounded mb-2"
                    style="background-color: rgba(40, 167, 69, 0.1);">
                    <img src="../publisherAssets/assets/icons8-check.gif" class="rounded-circle mr-2" height="24" alt="Verified">
                    <span class="text-success font-weight-bold">
                        Deep Link Verified
                    </span>
                </div>`);
        } else {
            $('#input1').removeAttr('disabled');
            $('#input2').attr('disabled', true);
            $statusContainer.html(`
                <div class="d-flex align-items-center p-2 bg-light-danger rounded mb-2"
                    style="background-color: rgba(167, 40, 40, 0.1);">
                    <img src="../publisherAssets/assets/icons8-cross.gif" class="rounded-circle mr-2" height="24" alt="Not Verified">
                    <span class="text-danger font-weight-bold">
                        Deep Link Not Verified
                    </span>
                </div>`);
        }
    }

    // Bind change event
    $dropdown.on('change', updateDeeplinkStatus);

    // Trigger on page load if value is selected
    if ($dropdown.val()) {
        updateDeeplinkStatus();
    }
});
