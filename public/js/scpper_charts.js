/** Changes charts **/
scpper.charts = {
    
    getDateGroupTooltip: function (date, group) {
        var period = '';            
        if (group === 'day') {
            period = date.format('UTC:mmm d, yyyy');
        } else if (group === 'week') {
            var nextDate = new Date(date);
            nextDate.setUTCDate(date.getUTCDate()+6);
            if (nextDate.getUTCFullYear() !== date.getUTCFullYear()) {
                period = date.format('UTC:mmm d, yyyy')+' - '+nextDate.format('UTC:mmm d, yyyy');
            } else if (nextDate.getUTCMonth() !== date.getUTCMonth()) {
                period = date.format('UTC:mmm d')+' - '+nextDate.format('UTC:mmm d')+nextDate.format(', yyyy');
            } else {
                period = date.format('UTC:mmm d')+' - '+nextDate.format('UTC:d')+nextDate.format(', yyyy');
            }                    
        } else if (group === 'month') {
            period = date.format('UTC:mmm, yyyy');
        } else {
            period = date.format('UTC:yyyy');
        }            
        return period;
    },

    getDateTooltipContent: function (date, group, text, value) {
        var period = scpper.charts.getDateGroupTooltip(date, group);
        return '<b>' + period + ' </b><br>' + text+': <b>' + value + '</b> <br>'
    },

    setFailedBackground: function (id)
    {
        var elem = document.getElementById(id);
        elem.className = "chart-failed";
    },

    removeChartContainer: function (id)
    {
        var elem = document.getElementById(id);
        elem.parentNode.removeChild(elem);
    }
};

scpper.charts.changes = {

    drawColumnChart: function (rawData, label, title, id, color) {
        var data = new google.visualization.DataTable();
        data.addColumn({type: 'date', label: 'Time'});
        data.addColumn({type: 'number', label: label});
        data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
        data.addRows(rawData);
        var options = {
            title: title,
            legend: 'none',
            chartArea: {width: '80%', height: '80%'},
            colors: [color],
            tooltip: { isHtml: true }
        };    
        var div = document.getElementById(id);
        $(div).removeClass('chart-container').addClass('chart-loaded');        
        var chart = new google.visualization.ColumnChart(div);
        chart.draw(data, options);    
    },

    drawLineChart: function (rawData, label, title, id, color) {
        var data = new google.visualization.DataTable();
        data.addColumn({type: 'date', label: 'Time'});
        data.addColumn({type: 'number', label: label});
        data.addRows(rawData);
        var options = {
            title: title,
            legend: 'none',
            chartArea: {width: '80%', height: '80%'},
            colors: [color]
        };    
        var div = document.getElementById(id);
        $(div).removeClass('chart-container').addClass('chart-loaded');
        var chart = new google.visualization.LineChart(div);
        chart.draw(data, options);    
    },

    drawTimeLineCharts: function (result, barLabel, barTitle, barId, lineLabel, lineTitle, lineId, color) {
        // Define the chart to be drawn
        if (!result.success || result.data.length < 2) {
            scpper.charts.setFailedBackground(barId);
            scpper.charts.removeChartContainer(lineId);        
            return;
        }
        // Convert returned values from string to date
        for (var i=0; i<result.data.length; i++) {
            result.data[i][0] = scpper.convertDate(result.data[i][0]);
        }
        // Prepare data for bar chart
        var barData = $.extend(true, [], result.data);
        for (var i=0; i<barData.length; i++) {
            var group = result.group;            
            barData[i][2] = scpper.charts.getDateTooltipContent(barData[i][0], group, barLabel, barData[i][1]);
        }
        // Draw bar chart
        scpper.charts.changes.drawColumnChart(barData, barLabel, barTitle, barId, color);
        // Prepare data for line chart
        var lineData = $.extend(true, [], result.data);
        lineData[0][1] = lineData[0][1]+result.starting;
        for (var i=1; i<lineData.length; i++) {
            lineData[i][1] = lineData[i-1][1]+lineData[i][1];
        }
        // Draw line chart
        scpper.charts.changes.drawLineChart(lineData, lineLabel, lineTitle, lineId, color);
    },

    drawMemberCharts: function (chartsData) {
        if ($('#members-joined.chart-container').length && $('#members-joined.chart-container').length) {
            $('#members-joined').block(scpper.loadingOverlayOptions);
            $('#members-total').block(scpper.loadingOverlayOptions);        
            $.ajax({
                url: "/changes/getMemberChartData",
                type: "get",
                data: chartsData
            }).done(function (result) {
                scpper.charts.changes.drawTimeLineCharts(result, 'Joined', 'New members', 'members-joined', 'Members', 'Total members', 'members-total', '#94C282');
            }).fail(function () {
                scpper.charts.setFailedBackground('members-joined');
                scpper.charts.removeChartContainer('members-total');
            }).always(function () {
                $('#members-joined').unblock();
                $('#members-total').unblock();
            });            
        } 
    },

    drawPageCharts: function (chartsData) {
        if ($('#pages-created.chart-container').length && $('#pages-total.chart-container').length) {
            $('#pages-created').block(scpper.loadingOverlayOptions);
            $('#pages-total').block(scpper.loadingOverlayOptions);        
            $.ajax({
                url: "/changes/getPageChartData",
                type: "get",
                data: chartsData
            }).done(function (result) {
                scpper.charts.changes.drawTimeLineCharts(result, 'Created', 'New pages', 'pages-created', 'Pages', 'Total pages', 'pages-total', '#6DAECF');
            }).fail(function () {
                scpper.charts.setFailedBackground('pages-created');
                scpper.charts.removeChartContainer('pages-total');
            }).always(function () {
                $('#pages-created').unblock();
                $('#pages-total').unblock();
            });
        }
    },

    drawRevisionCharts: function (chartsData) {
        if ($('#revisions-created.chart-container').length && $('#revisions-total.chart-container').length) {
            $('#revisions-created').block(scpper.loadingOverlayOptions);
            $('#revisions-total').block(scpper.loadingOverlayOptions);        
            $.ajax({
                url: "/changes/getRevisionChartData",
                type: "get",
                data: chartsData
            }).done(function (result) {
                scpper.charts.changes.drawTimeLineCharts(result, 'Created', 'New revisions', 'revisions-created', 'Revisions', 'Total revisions', 'revisions-total', '#DB9191');
            }).fail(function () {
                scpper.charts.setFailedBackground('revisions-created');
                scpper.charts.removeChartContainer('revisions-total');
            }).always(function () {
                $('#revisions-created').unblock();
                $('#revisions-total').unblock();
            });
        }
    },

    drawVoteCharts: function (chartsData) {
        if ($('#votes-cast.chart-container').length && $('#votes-total.chart-container').length) {
            $('#votes-cast').block(scpper.loadingOverlayOptions);
            $('#votes-total').block(scpper.loadingOverlayOptions);
            $.ajax({
                url: "/changes/getVoteChartData",
                type: "get",
                data: chartsData
            }).done(function (result) {
                scpper.charts.changes.drawTimeLineCharts(result, 'Votes', 'New votes', 'votes-cast', 'Votes', 'Total votes', 'votes-total', '#E0A96E');
            }).fail(function () {
                scpper.charts.setFailedBackground('votes-cast');
                scpper.charts.removeChartContainer('votes-total');
            }).always(function () {
                $('#votes-cast').unblock();
                $('#votes-total').unblock();
            });
        }
    },

    go: function (chartsData) {
        if (chartsData.siteId < 0)
            return;
        scpper.charts.changes.drawMemberCharts(chartsData);
        scpper.charts.changes.drawPageCharts(chartsData);
        scpper.charts.changes.drawRevisionCharts(chartsData);
        scpper.charts.changes.drawVoteCharts(chartsData);
    }
};

/** Rating chart **/

scpper.charts.rating = {    
    
    convertDates: function (result) {
        // Convert returned values from string to date
        for (var i=0; i<result.votes.length; i++) {
            result.votes[i][0] = scpper.convertDate(result.votes[i][0]);
        }
        for (var i=0; i<result.milestones.length; i++) {
            result.milestones[i][0] = scpper.convertDate(result.milestones[i][0]);
        }                
    },
    
    prepareLineData: function (votes) {
        var rating = $.extend(true, [], votes);                
        var dateString = '';
        for (var i=1; i<rating.length; i++) {
            rating[i][1] = rating[i-1][1]+rating[i][1];
        }
        for (var i=0; i<rating.length; i++) {
            rating[i].push(true);
            dateString = rating[i][0].toISOString().slice(0, 10);
            rating[i].push('['+dateString+'] Rating: ' + rating[i][1]);
        }
        var lineData = new google.visualization.DataTable();
        lineData.addColumn({type: 'date', label: 'Time'});
        lineData.addColumn({type: 'number', label: 'Rating'});
        lineData.addColumn({type: 'boolean', role: 'certainty'});    
        lineData.addColumn({type: 'string', role: 'tooltip'});
        lineData.addRows(rating);
        return lineData;
    },
    
    preparePointData: function (milestones) {
        var points = [];        
        var tooltip = '';
        var dateString = '';
        for (var i=0; i<milestones.length; i++) {
            dateString = milestones[i][0].toISOString().slice(0, 10);
            tooltip = '['+dateString+'] '+milestones[i][1].text;
            points.push([milestones[i][0], null, false, tooltip, 'point { size: 3; color: #EDA200; visible: true}']);
        }
        // Draw line chart
        var pointData = new google.visualization.DataTable();
        pointData.addColumn({type: 'date', label: 'Time'});
        pointData.addColumn({type: 'number', label: 'Rating'});
        pointData.addColumn({type: 'boolean', role: 'certainty'});
        pointData.addColumn({type: 'string', role: 'tooltip'});
        pointData.addColumn({type: 'string', role: 'style'});                
        pointData.addRows(points);
        return pointData;
    },
    
    mergeData: function(lineData, pointData) {
        var data = google.visualization.data.join(lineData, pointData, 'full', [[0, 0], [1, 1], [2, 2], [3, 3]], [], [4]);
        data.setValue(0, 1, 0);
        var last = 0;
        for (var i=1; i<data.getNumberOfRows(); i++) {
            if (data.getValue(i, 1)) {
                if (i-last > 1) {
                    for (var j=last+1; j<i; j++) {
                        var ratio = (data.getValue(j, 0)-data.getValue(last, 0))/(data.getValue(i, 0)-data.getValue(last, 0));
                        var interp = Math.round((data.getValue(last, 1)+(data.getValue(i, 1)-data.getValue(last, 1))*ratio));
                        data.setValue(j, 1, interp);
                        if (data.getValue(last, 2) && data.getValue(i, 2)) {
                            data.setValue(j, 2, true);
                        }
                    }
                }
                last = i;
            }
        }
        return data;
    },
    
    getOptions: function() {
        return {
            title: 'Rating',
            annotations: {
              textStyle: {
                fontSize: 14,
                bold: true,
                // The color of the text.
                color: '#DB9191'
              }
            }, 
            dataOpacity: 0.5,
            chartArea: {width: '80%', height: '80%'},
            legend: 'none',
            colors: ['#E0A96E'],      
        };            
    },
    
    processData: function (result, container) {
        // Define the chart to be drawn
        if (!result.success || result.votes.length < 2) {
            scpper.charts.setFailedBackground(container);    
            return;
        }
        scpper.charts.rating.convertDates(result);
        var lineData = scpper.charts.rating.prepareLineData(result.votes);
        var pointData = scpper.charts.rating.preparePointData(result.milestones);
        // Prepare data for line chart
        var data = scpper.charts.rating.mergeData(lineData, pointData);        
        var div = document.getElementById(container);
        $(div).removeClass('chart-container').addClass('chart-loaded');
        var chart = new google.visualization.LineChart(div);
        var options = scpper.charts.rating.getOptions();        
        chart.draw(data, options);
    },    
    
    go: function (url, payload, container) {
        var graph = $('#'+container);
        graph.block(scpper.loadingOverlayOptions);
        $.ajax({
            url: url,
            type: "get",
            data: payload
        })
        .done(function(result) {
            graph.unblock();
            scpper.charts.rating.processData(result, container);
        })
        .fail(function () {
            graph.unblock();
            scpper.charts.setFailedBackground(container);
        });        
    }
};