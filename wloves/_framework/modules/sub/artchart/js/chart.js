var currentThemeChart = document.documentElement.getAttribute("data-theme");
if (currentThemeChart == "dark") {
  var text_color = "#ffffff";
  var line_color = "#DADEE3";
  var cursor_color = "#E6E6E6";
} else {
  var text_color = "#202125";
  var line_color = "#2A3238";
  var cursor_color = "#3A4248";
}

var artChart = {
  pieChart: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));
    // options
    var unit = options.unit ? options.unit : "";
    var percent = options.show_percent
      ? " ({valuePercentTotal.formatNumber('0.00')}%)"
      : "";
    var legend_options = options.legend ? options.legend : [];
    var root = am5.Root.new(id);
    var responsive = am5themes_Responsive.newEmpty(root);

    // responsive.addRule({
    //   relevant: am5themes_Responsive.widthXL,
    //   applying: function () {
    //     // chart.set("layout", root.verticalLayout);
    //     legend.setAll({
    //       // y: am5.p50,
    //       // centerY: am5.p50,
    //       // x: am5.p100,
    //       // centerX: am5.p100,
    //     });
    //   },
    //   removing: function () {
    //     // chart.set("layout", root.horizontalLayout);
    //     legend.setAll({
    //       // y: am5.p50,
    //       // centerY: am5.p50,
    //       // x: null,
    //       // centerX: null
    //     });
    //   }
    // });

    root.setThemes([am5themes_Animated.new(root), responsive]);

    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }

    var chart = root.container.children.push(
      am5percent.PieChart.new(root, {
        layout: root.verticalLayout, // verticalLayout horizontalLayout
      })
    );
    chart.root._logo.dispose();

    var series = chart.series.push(
      am5percent.PieSeries.new(root, {
        valueField: "value",
        categoryField: "category",
        legendLabelText: "{category}",
        legendValueText: "",
      })
    );

    series.labels.template.set("forceHidden", true);
    series.ticks.template.set("forceHidden", true);
    series.slices.template.setAll({
      // toggleKey: "none",
      // stroke: am5.color(0xffffff),
      // strokeWidth: 2
    });

    series.slices.template.set(
      "tooltipText",
      "[bold]{category}[/] : {value}" + unit + percent + ""
    );
    // คำนวนpercent จาก value  {valuePercentTotal.formatNumber('0.00')}

    series.data.setAll(data);

    if (legend_options.is_show) {
      var legend = chart.children.push(
        am5.Legend.new(root, {
          centerX: am5.percent(50),
          x: am5.percent(50),

          // y: am5.p50,
          // centerY: am5.p50,
          // x: am5.p100,
          // centerX: am5.p100,

          layout: am5.GridLayout.new(root, {
            maxColumns: 2,
            fixedWidthGrid: true,
          }),
          // clickTarget: "none" // กด Legend ไม่ได้
        })
      );

      legend.data.setAll(series.dataItems);
    }

    await artChart.hideLoadingChart(id);
    series.appear(1000, 100);
  },
  donutChart: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));

    var options_radius = options.radius ? options.radius : 90;
    var options_color = options.color ? options.color : false;
    var text_inside = options.text_inside ? options.text_inside : false;
    var legend_options = options.legend ? options.legend : false;

    var root = am5.Root.new(id);

    root.setThemes([am5themes_Animated.new(root)]);

    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Dark.new(root)]);
    }

    var chart = root.container.children.push(
      am5percent.PieChart.new(root, {
        layout: root.verticalLayout,
        innerRadius: am5.percent(options_radius), // รัศมีตรงกลาง
      })
    );

    chart.root._logo.dispose();

    var series = chart.series.push(
      am5percent.PieSeries.new(root, {
        valueField: "value",
        categoryField: "category",
        alignLabels: false,
        legendValueText: "{valuePercentTotal.formatNumber('0.00p')}",
      })
    );

    if (options_color) {
      series.get("colors").set("colors", options_color);
    }
    // labels ขนานไปกับวงกลม
    // series.labels.template.setAll({
    //   textType: "circular",
    //   centerX: 0,
    //   centerY: 0
    // });

    series.labels.template.set("forceHidden", true); // ซ่อน label
    series.ticks.template.set("forceHidden", true); // ซ่อน เล้นของ Label

    series.data.setAll(data);
    if (legend_options) {
      if (legend_options.is_show) {
        var legend = chart.children.push(
          am5.Legend.new(root, {
            centerX: am5.percent(50),
            x: am5.percent(50),
            marginTop: 15,
            marginBottom: 15,
            layout: root.horizontalLayout,
          })
        );
        legend.data.setAll(series.dataItems);

        if (legend_options.is_show_value) {
          if (legend_options.value_type == "percent") {
            series._settings.legendValueText =
              "{valuePercentTotal.formatNumber('0.00p')}";
          } else {
            series._settings.legendValueText = "{value.formatNumber('#,###')}";
          }

          if (legend_options.is_circle) {
            legend.markerRectangles.template.setAll({
              cornerRadiusTL: 10,
              cornerRadiusTR: 10,
              cornerRadiusBL: 10,
              cornerRadiusBR: 10,
            });
          }
        } else {
          legend.valueLabels.template.set("forceHidden", true);
        }
      }

      // legend.labels.template.setAll({
      //   maxWidth: 150,
      //   //centerY: 0, // if we want labels to be top-aligned
      //   oversizedBehavior: "wrap"
      // })
    }

    if (text_inside) {
      var label = chart.seriesContainer.children.push(
        am5.Label.new(root, {
          textAlign: "center",
          centerY: am5.p50,
          centerX: am5.p50,
          text: text_inside,
        })
      );
    }

    await artChart.hideLoadingChart(id);
    series.appear(1000, 100);
  },
  lineChart: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));
    // options
    var unit = options.unit ? options.unit : "";

    var root = am5.Root.new(id);
    root.setThemes([am5themes_Animated.new(root)]);
    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }

    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        maxTooltipDistance: 0,
        pinchZoomX: true,
      })
    );
    chart.root._logo.dispose();

    var xAxis = chart.xAxes.push(
      am5xy.DateAxis.new(root, {
        maxDeviation: 0.2,
        baseInterval: {
          //"millisecond", "second", "minute", "hour", "day", "week", "month", "year".
          timeUnit: "day",
          count: 1,
          // text color
          label: {
            text: "[" + text_color + "]{value.formatDate('dd MMMM yyyy')}[/]",
          },
        },
        renderer: am5xy.AxisRendererX.new(root, {}),
        tooltip: am5.Tooltip.new(root, {}),
      })
    );
    xAxis.get("dateFormats")["day"] = "dd MMMM yyyy";
    xAxis.get("dateFormats")["month"] = "MMMM";

    var yAxis = chart.yAxes.push(
      am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {}),
      })
    );

    Object.keys(data).forEach((key) => {
      // item.date = new Date(item.date).getTime();

      data[key].forEach(function (element) {
        element.labels = new Date(element.labels).getTime();
      });

      var series = chart.series.push(
        am5xy.LineSeries.new(root, {
          name: key,
          xAxis: xAxis,
          yAxis: yAxis,
          valueYField: "value",
          valueXField: "labels",
          legendValueText: "{valueY}",
          tooltip: am5.Tooltip.new(root, {
            pointerOrientation: "horizontal",
            labelText: "{valueY} " + unit + "",
          }),
        })
      );

      series.data.setAll(data[key]);

      series.bullets.push(function () {
        return am5.Bullet.new(root, {
          sprite: am5.Circle.new(root, {
            radius: 5,
            fill: series.get("fill"),
          }),
        });
      });

      series.appear();
    });

    var legend = chart.bottomAxesContainer.children.push(
      am5.Legend.new(root, {
        centerX: am5.percent(50),
        x: am5.percent(50),
      })
    );
    legend.data.setAll(chart.series.values);

    var cursor = chart.set(
      "cursor",
      am5xy.XYCursor.new(root, {
        behavior: "none",
      })
    );
    // cursor.lineY.set("visible", false);

    chart.set(
      "scrollbarX",
      am5.Scrollbar.new(root, {
        orientation: "horizontal",
        marginBottom: 20,
      })
    );

    // chart.set("scrollbarY", am5.Scrollbar.new(root, {
    //   orientation: "vertical"
    // }));

    await artChart.hideLoadingChart(id);
    chart.appear(1000, 100);
  },
  lineChartString: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));

    var options = jQuery.parseJSON($.base64.decode(__options));
    var root = am5.Root.new(id);
    var is_bullet = options.bullet ? options.bullet : false;
    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelY: "zoomXY",
        pinchZoomX: true,
      })
    );
    chart.root._logo.dispose();
    chart.get("colors").set("step", 2);

    var xRenderer = am5xy.AxisRendererX.new(root, {
      minGridDistance: 50,
    });
    xRenderer.labels.template.setAll({
      rotation: -80,
      centerY: am5.p50,
      centerX: am5.p100,
      paddingRight: 15,
    });

    var xAxis = chart.xAxes.push(
      am5xy.CategoryAxis.new(root, {
        categoryField: "labels",
        renderer: xRenderer,
      })
    );

    var yAxis = chart.yAxes.push(
      am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {
          strokeOpacity: 0.1,
        }),
      })
    );

    Object.keys(data).forEach((key) => {
      if (options.color) {
        var series = chart.series.push(
          am5xy.LineSeries.new(root, {
            name: key,
            xAxis: xAxis,
            yAxis: yAxis,
            stroke: am5.color(options.color[key]),
            strokeWidth: 2,
            valueYField: "value",
            categoryXField: "labels",
            legendValueText: "{valueY}",
            tooltip: am5.Tooltip.new(root, {
              pointerOrientation: "horizontal",
              labelText: "{valueY}",
            }),
          })
        );
      } else {
        var series = chart.series.push(
          am5xy.LineSeries.new(root, {
            name: key,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            categoryXField: "labels",
            legendValueText: "{valueY}",
            tooltip: am5.Tooltip.new(root, {
              pointerOrientation: "horizontal",
              labelText: "{valueY}",
            }),
          })
        );
      }

      if (is_bullet == true) {
        series.bullets.push(function () {
          return am5.Bullet.new(root, {
            sprite: am5.Circle.new(root, {
              strokeWidth: 2,
              radius: 5,
              stroke: options.color
                ? am5.color(options.color[key])
                : series.get("fill"),
              fill: am5.color("#fff"),
            }),
          });
        });
      }

      xAxis.data.setAll(data[key]);
      series.data.setAll(data[key]);
      series.appear();
    });

    chart.set(
      "scrollbarX",
      am5.Scrollbar.new(root, {
        orientation: "horizontal",
        marginBottom: 20,
      })
    );

    await artChart.hideLoadingChart(id);
    chart.appear(1000, 100);
  },
  barChartHorizontal: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));

    // options
    var unit = options.unit ? options.unit : "";
    var bullet = options.bullet ? options.bullet : false;

    var root = am5.Root.new(id);
    root.setThemes([am5themes_Animated.new(root)]);
    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }
    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "none", // panX,Y zoomX,Y
        wheelY: "zoomY", // panX,Y zoomX,Y
        pinchZoomX: true,
      })
    );
    chart.root._logo.dispose();

    var xRenderer = am5xy.AxisRendererX.new(root, {
      minGridDistance: 100,
    });
    xRenderer.grid.template.setAll({
      visible: false,
    });
    var xAxis = chart.xAxes.push(
      am5xy.ValueAxis.new(root, {
        maxDeviation: 0,
        min: 0,
        renderer: xRenderer,
      })
    );

    var yRenderer = am5xy.AxisRendererY.new(root, {
      minGridDistance: 1,
    });
    yRenderer.grid.template.setAll({
      visible: true,
    });
    var yAxis = chart.yAxes.push(
      am5xy.CategoryAxis.new(root, {
        maxDeviation: 0,
        categoryField: "category",
        renderer: yRenderer,
      })
    );

    var tooltip = am5.Tooltip.new(root, {
      labelText: "{valueX} " + unit + "",
    });

    tooltip.label.setAll({
      fill: am5.color(0xffffff),
    });

    var series = chart.series.push(
      am5xy.ColumnSeries.new(root, {
        name: "Series 1",
        xAxis: xAxis,
        yAxis: yAxis,
        valueXField: "value",
        sequencedInterpolation: true,
        categoryYField: "category",
        tooltip: tooltip,
      })
    );

    series.columns.template.adapters.add("fill", function (fill, target) {
      return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
      return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    if (bullet) {
      series.bullets.push(function (root) {
        return am5.Bullet.new(root, {
          locationX: 0.5,
          locationY: 0.5,
          sprite: am5.Label.new(root, {
            text: "{valueX} " + unit + "",
            centerX: am5.percent(50),
            centerY: am5.percent(50),
            populateText: true,
            fill: am5.color(0xffffff),
            minBulletDistance: 1000,
          }),
        });
      });
      series.columns.template.onPrivate("width", function (width, target) {
        am5.array.each(target.dataItem.bullets, function (bullet) {
          if (target.dataItem.get("valueX") > 0) {
            if (width > 100) {
              bullet.set("locationX", 0.5);
              bullet.get("sprite").set("centerX", am5.p50);
              bullet.get("sprite").set("fill", am5.color(0xffffff));
            } else {
              bullet.set("locationX", 0);
              bullet.get("sprite").set("centerX", am5.p0);
              bullet.get("sprite").set("fill", am5.color(0x00000));
            }
          } else {
            bullet.get("sprite").hide();
          }
        });
      });
    }

    var columnTemplate = series.columns.template;

    columnTemplate.setAll({
      cursorOverStyle: "pointer",
      tooltipText: "{valuX}",
      // height: 40,
    });
    yAxis.data.setAll(data);
    series.data.setAll(data);
    await artChart.hideLoadingChart(id);
    series.appear(1000);
    chart.appear(1000, 100);
  },
  barChartVertical: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));

    // options
    var unit = options.unit ? options.unit : "";
    var labels_options = options.labels ? options.labels : [];

    var root = am5.Root.new(id);
    root.setThemes([am5themes_Animated.new(root)]);
    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }
    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "none",
        wheelY: "zoomX",
        pinchZoomX: true,
      })
    );
    chart.root._logo.dispose();

    var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
    xRenderer.grid.template.setAll({
      visible: false,
    });
    xRenderer.labels.template.setAll({
      paddingTop: 15,
      oversizedBehavior: "wrap",
      textAlign: "center",
      maxWidth: 150,
    });

    if (labels_options.layout && labels_options.layout == "vertical") {
      xRenderer.labels.template.setAll({
        rotation: -80,
        centerY: am5.p50,
        centerX: am5.p100,
      });
    }

    var xAxis = chart.xAxes.push(
      am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "category",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {}),
      })
    );

    if (!labels_options.layout || labels_options.layout != "vertical") {
      xRenderer.labels.template.adapters.add("width", function (width, target) {
        var x0 = xAxis.getDataItemCoordinateY(
          xAxis.dataItems[0],
          "category",
          0
        );
        var x1 = xAxis.getDataItemCoordinateY(
          xAxis.dataItems[0],
          "category",
          1
        );
        target.set("maxWidth", x1 - x0);
        return x1 - x0;
      });
    }

    var yRenderer = am5xy.AxisRendererY.new(root, { minGridDistance: 30 });
    yRenderer.grid.template.setAll({
      visible: true,
    });
    var yAxis = chart.yAxes.push(
      am5xy.ValueAxis.new(root, {
        maxPrecision: 0,
        numberFormat: "#",
        maxDeviation: 0.3,
        renderer: yRenderer,
      })
    );

    var series = chart.series.push(
      am5xy.ColumnSeries.new(root, {
        name: "Series 1",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "value",
        sequencedInterpolation: true,
        categoryXField: "category",
        tooltip: am5.Tooltip.new(root, {
          labelText: "{valueY} " + unit + "",
        }),
      })
    );


    series.columns.template.adapters.add("fill", function (fill, target) {
      return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
      return chart.get("colors").getIndex(series.columns.indexOf(target));
    });


    var columnTemplate = series.columns.template;

    columnTemplate.setAll({
      cursorOverStyle: "pointer",
      tooltipText: "{valuX}",
    });

    chart.set(
      "scrollbarX",
      am5.Scrollbar.new(root, {
        orientation: "horizontal",
        marginBottom: 20,
      })
    );

    xAxis.data.setAll(data);
    series.data.setAll(data);

    await artChart.hideLoadingChart(id);

    series.appear(1000);
    chart.appear(1000, 100);
  },
  scheduleChart: async function (id, __data, __options) {
    var root = am5.Root.new(id);
    root.dateFormatter.setAll({
      dateFormat: "HH:mm",
      dateFields: ["valueX", "openValueX"],
    });

    root.setThemes([am5themes_Animated.new(root)]);
    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }
    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        layout: root.verticalLayout,
      })
    );
    chart.root._logo.dispose();
    var legend = chart.children.push(
      am5.Legend.new(root, {
        centerX: am5.p50,
        x: am5.p50,
      })
    );

    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));
    var color_template = options.color_template ? options.color_template : [];
    if (color_template) {
      data.forEach(function (value, index) {
        color_template.forEach(function (color_value, color_index) {
          if (value["type"] == "timeline") {
            data[index]["columnSettings"] = {
              fillOpacity: 0,
            };
          } else {
            if (value["type"] == color_value["type"]) {
              data[index]["columnSettings"] = {
                fill: am5.color(color_value["color"]),
                tooltipText: "{detail} , {openValueX} - {valueX}",
              };
            }
          }
        });
      });
    }

    var yAxis = chart.yAxes.push(
      am5xy.CategoryAxis.new(root, {
        categoryField: "category",
        renderer: am5xy.AxisRendererY.new(root, {
          inversed: true,
          minGridDistance: 10,
        }),
        tooltip: am5.Tooltip.new(root, {
          themeTags: ' ["axis"]',
          animationDuration: 200,
        }),
        fillRule: function (dataItem) {
          if (dataItem && dataItem.dataContext) {
            let category_txt = dataItem.dataContext.category;
            let dateParts = category_txt.split("/");
            let date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
            let get_day = date.getDay();
            var axisFill = dataItem.get("axisFill");
            if (get_day == 0) {
              axisFill.set("visible", true);
              axisFill.set("fill", am5.color("#0xFF0000"));
              axisFill.set("fillOpacity", "0.2");
            } else if (get_day == 6) {
              axisFill.set("visible", true);
              axisFill.set("fill", am5.color("#4b80d6"));
              axisFill.set("fillOpacity", "0.2");
            }
          }
        },
      })
    );

    yAxis
      .get("renderer")
      .labels.template.adapters.add("fill", function (fill, target) {
        if (target.dataItem && target.dataItem.dataContext) {
          let category_txt = target.dataItem.dataContext.category;
          let dateParts = category_txt.split("/");
          let date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
          let get_day = date.getDay();
          if (get_day == 0) {
            return am5.color("#c71616");
          } else if (get_day == 6) {
            return am5.color("#4b80d6");
          }
        }
      });

    var category_filter = [];
    var category_temp = [];

    data.forEach(function (value, index) {
      category_filter.indexOf(value["category"]) == -1
        ? category_filter.push(value["category"])
        : "";
    });

    category_filter.forEach(function (value, index) {
      var sub_temp = {
        category: value,
      };
      category_temp.push(sub_temp);
    });

    yAxis.data.setAll(category_temp);

    var xAxis = chart.xAxes.push(
      am5xy.DateAxis.new(root, {
        baseInterval: {
          timeUnit: "minute",
          count: 1,
        },
        markUnitChange: undefined,
        renderer: am5xy.AxisRendererX.new(root, {
          minGridDistance: 60,
        }),
      })
    );
    var series = chart.series.push(
      am5xy.ColumnSeries.new(root, {
        xAxis: xAxis,
        yAxis: yAxis,
        openValueXField: "fromDate",
        valueXField: "toDate",
        categoryYField: "category",
        sequencedInterpolation: true,
      })
    );

    // tooltip hover
    series.columns.template.setAll({
      templateField: "columnSettings",
      strokeOpacity: 0,
    });

    series.data.processor = am5.DataProcessor.new(root, {
      dateFields: ["fromDate", "toDate"],
      dateFormat: "HH:mm",
    });

    series.data.setAll(data);

    chart.set(
      "scrollbarX",
      am5.Scrollbar.new(root, {
        orientation: "horizontal",
        marginBottom: 20,
      })
    );

    chart.set(
      "scrollbarY",
      am5.Scrollbar.new(root, {
        orientation: "vertical",
        marginLeft: 30,
      })
    );

    await artChart.hideLoadingChart(id);

    series.appear();
    chart.appear(1000, 100);
  },
  stackVertical: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));

    var unit = options.unit ? options.unit : "";
    var labelX = options.labels.labelX ? options.labels.labelX : "";
    var labelY = options.labels.labelY ? options.labels.labelY : "";
    var scrollBarX = options.scrollbar ? options.scrollbar : "";
    var labels_layout = options.labels.layout
      ? options.labels.layout
      : "horizontal";
    var random_color = options.random_color;

    var root = am5.Root.new(id);
    root.setThemes([am5themes_Animated.new(root)]);
    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }

    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        layout: root.verticalLayout,
      })
    );

    chart.root._logo.dispose();

    var xRenderer = am5xy.AxisRendererX.new(root, {
      minGridDistance: 1,
    });

    xRenderer.labels.template.setAll({
      paddingTop: 15,
      oversizedBehavior: "wrap",
      textAlign: "center",
      maxWidth: 150,
    });

    if (labels_layout == "vertical") {
      xRenderer.labels.template.setAll({
        rotation: -80,
        centerY: am5.p50,
        centerX: am5.p100,
      });
    }

    var xAxis = chart.xAxes.push(
      am5xy.CategoryAxis.new(root, {
        categoryField: "category",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {}),
      })
    );

    if (labels_layout != "vertical") {
      xRenderer.labels.template.adapters.add("width", function (width, target) {
        var x0 = xAxis.getDataItemCoordinateY(
          xAxis.dataItems[0],
          "category",
          0
        );
        var x1 = xAxis.getDataItemCoordinateY(
          xAxis.dataItems[0],
          "category",
          1
        );
        target.set("maxWidth", x1 - x0);
        return x1 - x0;
      });
    }

    if (labelX) {
      xAxis.children.push(
        am5.Label.new(root, {
          text: labelX,
          x: am5.p50,
          centerX: am5.p50,
        })
      );
    }

    xRenderer.grid.template.setAll({
      visible: false,
    });

    xAxis.data.setAll(data);

    var yAxis = chart.yAxes.push(
      am5xy.ValueAxis.new(root, {
        min: 0,
        renderer: am5xy.AxisRendererY.new(root, {
          strokeOpacity: 0.1,
        }),
      })
    );

    if (labelY) {
      yAxis.children.unshift(
        am5.Label.new(root, {
          rotation: -90,
          text: labelY,
          y: am5.p50,
          centerX: am5.p50,
        })
      );
    }

    var legend = chart.children.push(
      am5.Legend.new(root, {
        centerX: am5.p50,
        x: am5.p50,
      })
    );

    function makeSeries(name, fieldName) {
      var series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
          name: name,
          stacked: true,
          xAxis: xAxis,
          yAxis: yAxis,
          valueYField: fieldName,
          categoryXField: "category",
        })
      );

      series.columns.template.setAll({
        tooltipText: "{name}, {categoryX}: {valueY} " + unit,
        tooltipY: am5.percent(10),
      });

      if (!random_color) {
        series.columns.template.setAll({
          fill: am5.color(artChart.getColor(options.data_key, fieldName)),
          stroke: am5.color(artChart.getColor(options.data_key, fieldName)),
        });
      }

      series.data.setAll(data);

      series.appear();

      series.bullets.push(function () {
        return am5.Bullet.new(root, {
          sprite: am5.Label.new(root, {
            // text: "{valueY}",
            fill: am5.color(0xffffff),
            centerY: am5.p50,
            centerX: am5.p50,
            populateText: true,
          }),
        });
      });
      series.columns.template.onPrivate("height", function (height, target) {
        am5.array.each(target.dataItem.bullets, function (bullet) {
          if (target.dataItem.get("valueY") > 0 && height > 10) {
            bullet.get("sprite").show();
            bullet.set("locationX", 0.5);
            bullet.get("sprite").set("centerX", am5.p50);
            bullet.get("sprite").set("fill", am5.color(0xffffff));
          } else {
            bullet.get("sprite").hide();
          }
        });
      });

      legend.data.push(series);
    }

    options.data_key.forEach(function (value, index) {
      makeSeries(value.name, value.key);
    });

    await artChart.hideLoadingChart(id);

    chart.appear(1000, 100);

    if (scrollBarX) {
      chart.set(
        "scrollbarX",
        am5.Scrollbar.new(root, {
          orientation: "horizontal",
          marginBottom: 20,
        })
      );
    }
  },
  stackHorizontal: async function (id, __data, __options) {
    var data = jQuery.parseJSON($.base64.decode(__data));
    var options = jQuery.parseJSON($.base64.decode(__options));

    // options
    var unit = options.unit ? options.unit : "";
    var labelX = options.labels.labelX ? options.labels.labelX : "";
    var labelY = options.labels.labelY ? options.labels.labelY : "";
    var legend_options = options.legend ? options.legend : [];
    var random_color = options.random_color;
    var maxPrecision = options.decimal ? options.decimal : 0;
    var root = am5.Root.new(id);

    root.setThemes([am5themes_Animated.new(root)]);

    if (currentThemeChart == "dark") {
      root.setThemes([am5themes_Animated.new(root), am5themes_Dark.new(root)]);
    }

    var chart = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panY",
        wheelY: "zoomY",
        layout: root.verticalLayout,
      })
    );
    chart.root._logo.dispose();

    var yRenderer = am5xy.AxisRendererY.new(root, {
      minGridDistance: 1,
    });
    var yAxis = chart.yAxes.push(
      am5xy.CategoryAxis.new(root, {
        categoryField: "category",
        renderer: yRenderer,
        tooltip: am5.Tooltip.new(root, {}),
      })
    );

    if (labelY) {
      yAxis.children.unshift(
        am5.Label.new(root, {
          rotation: -90,
          text: labelY,
          y: am5.p50,
          centerX: am5.p50,
        })
      );
    }

    yRenderer.grid.template.setAll({
      location: 1,
    });

    yAxis.data.setAll(data);

    var xRenderer = am5xy.AxisRendererX.new(root, {
      strokeOpacity: 0.1,
    });

    xRenderer.grid.template.setAll({
      visible: false,
    });

    var xAxis = chart.xAxes.push(
      am5xy.ValueAxis.new(root, {
        min: 0,
        renderer: xRenderer,
        maxPrecision: maxPrecision,
      })
    );

    if (labelX) {
      xAxis.children.push(
        am5.Label.new(root, {
          text: labelX,
          x: am5.p50,
          centerX: am5.p50,
        })
      );
    }

    var legend = chart.children.push(
      am5.Legend.new(root, {
        centerX: am5.p50,
        x: am5.p50,
      })
    );

    function makeSeries(name, fieldName) {
      var series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
          name: name,
          stacked: true,
          xAxis: xAxis,
          yAxis: yAxis,
          baseAxis: yAxis,
          valueXField: fieldName,
          categoryYField: "category",
        })
      );

      series.columns.template.setAll({
        tooltipText: "{name}, {categoryY}: {valueX} " + unit,
        tooltipY: am5.percent(90),
      });

      if (!random_color) {
        series.columns.template.setAll({
          fill: am5.color(artChart.getColor(options.data_key, fieldName)),
          stroke: am5.color(artChart.getColor(options.data_key, fieldName)),
        });
      }

      series.data.setAll(data);

      series.appear();

      series.bullets.push(function () {
        return am5.Bullet.new(root, {
          sprite: am5.Label.new(root, {
            text: "{valueX}",
            fill: root.interfaceColors.get("alternativeText"),
            centerY: am5.p50,
            centerX: am5.p50,
            fill: am5.color(0xffffff),
            populateText: true,
          }),
        });
      });
      series.columns.template.onPrivate("width", function (width, target) {
        am5.array.each(target.dataItem.bullets, function (bullet) {
          if (target.dataItem.get("valueX") > 0 && width > 15) {
            bullet.get("sprite").show();
            bullet.set("locationX", 0.5);
            bullet.get("sprite").set("centerX", am5.p50);
            bullet.get("sprite").set("fill", am5.color(0xffffff));
          } else {
            bullet.get("sprite").hide();
          }
        });
      });

      if (legend_options.is_show) {
        legend.data.push(series);
      }
    }

    options.data_key.forEach(function (value, index) {
      makeSeries(value.name, value.key);
    });

    await artChart.hideLoadingChart(id);

    chart.appear(1000, 100);
  },
  getColor: function (data_key, fieldName, type) {
    var result = data_key.find((category) => category.key === fieldName);
    result =
      result.color === undefined
        ? artChart.getRandomPastelColor()
        : result.color;
    return result;
  },
  hideLoadingChart: function (id) {
    w("#" + id)
      .find(".i-chart-loading")
      .css("display", "none");
  },
};

w.extend({ artChart });
