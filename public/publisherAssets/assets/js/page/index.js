"use strict";

$(function () {
	changeChartData("sales", "#b32ab3");
	chart2();
	chart3();

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
    // Highlight selected card item
    document.querySelectorAll('.sale-value').forEach(item => {
        item.classList.remove(
            'text-success', 'text-warning', 'text-danger', 'text-purple', 'text-lg'
        );
    });
    const selectedItem = document.getElementById(type);
    if (selectedItem) {
        selectedItem.classList.add('text-lg');
        if (type === 'approved') {
            selectedItem.classList.add('text-success');
        } else if (type === 'pending') {
            selectedItem.classList.add('text-warning');
        } else if (type === 'rejected') {
            selectedItem.classList.add('text-danger');
        } else if (type === 'sales') {
            selectedItem.classList.add('text-purple');
        }
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
        // Clean up old chart
        if (salesChartInstance) {
            salesChartInstance.dispose();
        }

        // Format data for amCharts
        const dataCombined = [];
        const currentMonthData = data.currentMonth;
        const previousMonthData = data.previousMonth;

        for (let i = 0; i < currentMonthData.length; i++) {
            dataCombined.push({
                date: currentMonthData[i].date,
                current: currentMonthData[i].total,
                previous: previousMonthData[i] ? previousMonthData[i].total : 0
            });
        }

        // Update totals
        const totals = data.totals;
        if (totals) {
            document.getElementById("sales").innerText = `$${totals.sales.toFixed(2)}`;
            document.getElementById("approved").innerText = `$${totals.approved.toFixed(2)}`;
            document.getElementById("pending").innerText = `$${totals.pending.toFixed(2)}`;
            document.getElementById("rejected").innerText = `$${totals.rejected.toFixed(2)}`;
        }

        // Axis label text
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

        // Use amCharts animated theme
        am4core.useTheme(am4themes_animated);

        // Create chart instance
        let chart = am4core.create("chart1", am4charts.XYChart);
        salesChartInstance = chart;

        chart.data = dataCombined;

        // Category Axis (dates)
        let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "date";
        categoryAxis.title.text = "Date";
        categoryAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");
        categoryAxis.renderer.minGridDistance = 30;

        // Value Axis
        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = yAxisLabel + " ($)";
        valueAxis.min = 0;
        valueAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");

        // Series - Current Month
        let currentSeries = chart.series.push(new am4charts.LineSeries());
        currentSeries.dataFields.valueY = "current";
        currentSeries.dataFields.categoryX = "date";
        currentSeries.name = "Current Month";
        currentSeries.strokeWidth = 3;
        currentSeries.stroke = am4core.color(color);
        currentSeries.tooltipText = "[bold]{categoryX}[/]: {valueY}";
        currentSeries.tensionX = 0.8;

        let currentBullet = currentSeries.bullets.push(new am4charts.CircleBullet());
        currentBullet.circle.radius = 4;
        currentBullet.circle.fill = am4core.color(color);
        currentBullet.circle.strokeWidth = 2;

        // Series - Previous Month
        let previousSeries = chart.series.push(new am4charts.LineSeries());
        previousSeries.dataFields.valueY = "previous";
        previousSeries.dataFields.categoryX = "date";
        previousSeries.name = "Previous Month";
        previousSeries.strokeWidth = 2;
        previousSeries.stroke = am4core.color("#bbbbbb");
        previousSeries.strokeDasharray = "5,5";
        previousSeries.tooltipText = "[bold]{categoryX}[/]: {valueY}";
        previousSeries.tensionX = 0.8;

        let previousBullet = previousSeries.bullets.push(new am4charts.CircleBullet());
        previousBullet.circle.radius = 4;
        previousBullet.circle.fill = am4core.color("#bbbbbb");
        previousBullet.circle.strokeWidth = 2;

        // Enable cursor
        chart.cursor = new am4charts.XYCursor();

        // Add legend
        chart.legend = new am4charts.Legend();
        chart.legend.position = "top";
        chart.legend.align = "right";

        // Scrollbar
        chart.scrollbarX = new am4core.Scrollbar();
    });
}


function chart2() {
	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end



	// Create chart instance
	var chart = am4core.create("chart2", am4charts.RadarChart);

	// Add data
	chart.data = [{
		"category": "Not Joined",
		"value": 80,
		"full": 100
	}, {
		"category": "Pending",
		"value": 35,
		"full": 100
	}, {
		"category": "Rejected",
		"value": 92,
		"full": 100
	}, {
		"category": "Approved",
		"value": 68,
		"full": 100
	}];

	// Make chart not full circle
	chart.startAngle = -90;
	chart.endAngle = 180;
	chart.innerRadius = am4core.percent(20);

	// Set number format
	chart.numberFormatter.numberFormat = "#.#'%'";

	// Create axes
	var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "category";
	categoryAxis.renderer.grid.template.location = 0;
	categoryAxis.renderer.grid.template.strokeOpacity = 0;
	categoryAxis.renderer.labels.template.horizontalCenter = "right";
	categoryAxis.renderer.labels.template.fontWeight = 500;
	categoryAxis.renderer.labels.template.adapter.add("fill", function (fill, target) {
		return (target.dataItem.index >= 0) ? chart.colors.getIndex(target.dataItem.index) : fill;
	});
	categoryAxis.renderer.minGridDistance = 10;

	var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
	valueAxis.renderer.grid.template.strokeOpacity = 0;
	valueAxis.min = 0;
	valueAxis.max = 100;
	valueAxis.strictMinMax = true;
	valueAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");

	// Create series
	var series1 = chart.series.push(new am4charts.RadarColumnSeries());
	series1.dataFields.valueX = "full";
	series1.dataFields.categoryY = "category";
	series1.clustered = false;
	series1.columns.template.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
	series1.columns.template.fillOpacity = 0.08;
	series1.columns.template.cornerRadiusTopLeft = 20;
	series1.columns.template.strokeWidth = 0;
	series1.columns.template.radarColumn.cornerRadius = 20;

	var series2 = chart.series.push(new am4charts.RadarColumnSeries());
	series2.dataFields.valueX = "value";
	series2.dataFields.categoryY = "category";
	series2.clustered = false;
	series2.columns.template.strokeWidth = 0;
	series2.columns.template.tooltipText = "{category}: [bold]{value}[/]";
	series2.columns.template.radarColumn.cornerRadius = 20;

	series2.columns.template.adapter.add("fill", function (fill, target) {
		return chart.colors.getIndex(target.dataItem.index);
	});

	// Add cursor
	chart.cursor = new am4charts.RadarCursor();
}

function chart3() {
  am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chart3", am4maps.MapChart);
    chart.projection = new am4maps.projections.Orthographic();
    chart.panBehavior = "rotateLongLat";
    chart.deltaLatitude = -20;
    chart.padding(20, 20, 20, 20);

    var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
    polygonSeries.useGeodata = true;
    polygonSeries.geodata = am4geodata_worldLow;

    var polygonTemplate = polygonSeries.mapPolygons.template;
    polygonTemplate.tooltipText = "{name}";
    polygonTemplate.fill = am4core.color("#cccccc");
    polygonTemplate.stroke = am4core.color("#000000");
    polygonTemplate.strokeWidth = 0.5;
    polygonTemplate.nonScalingStroke = true;

    // Remove default hover fill
    polygonTemplate.states.removeKey("hover");

    var highlightedCountries = [
      { id: "PK", name: "Pakistan", color: "#FF5733", advertisers: 120 },
      { id: "US", name: "United States", color: "#33FF57", advertisers: 300 },
      { id: "CN", name: "China", color: "#3357FF", advertisers: 250 },
      { id: "RU", name: "Russia", color: "#FF33A1", advertisers: 150 },
      { id: "BR", name: "Brazil", color: "#FFC300", advertisers: 180 },
      { id: "IN", name: "India", color: "#A133FF", advertisers: 220 },
      { id: "ZA", name: "South Africa", color: "#33FFF6", advertisers: 90 },
      { id: "AU", name: "Australia", color: "#FF8F33", advertisers: 110 },
      { id: "DE", name: "Germany", color: "#33FF99", advertisers: 160 },
      { id: "GB", name: "United Kingdom", color: "#FF3333", advertisers: 140 }
    ];

    polygonSeries.events.once("inited", function () {
      highlightedCountries.forEach(function (country) {
        var polygon = polygonSeries.getPolygonById(country.id);
        if (polygon) {
          polygon.fill = am4core.color(country.color);
          polygon.tooltipText = country.name + ": " + country.advertisers + " Advertisers";

          // Add hover state *only* for highlighted countries
          var hs = polygon.states.create("hover");
          hs.properties.fill = am4core.color(country.color); // or any hover color you prefer
        }
      });
    });
  });
}


function chart4() {
	var options = {
		chart: {
			height: 250,
			type: 'area',
			toolbar: {
				show: false
			},

		},
		colors: ['#999b9c', '#4CC2B0'], // line color
		fill: {
			colors: ['#999b9c', '#4CC2B0'] // fill color
		},
		dataLabels: {
			enabled: false
		},
		stroke: {
			curve: 'smooth'
		},
		markers: {
			colors: ['#999b9c', '#4CC2B0'] // marker color
		},
		series: [{
			name: 'series1',
			data: [31, 40, 28, 51, 22, 64, 80]
		}, {
			name: 'series2',
			data: [11, 32, 67, 32, 44, 52, 41]
		}],
		legend: {
			show: false,
		},
		xaxis: {
			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July'],
			labels: {
				style: {
					colors: "#9aa0ac"
				}
			},
		},
		yaxis: {
			labels: {
				style: {
					color: "#9aa0ac"
				}
			}
		},
	}

	var chart = new ApexCharts(
		document.querySelector("#chart4"),
		options
	);

	chart.render();

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

