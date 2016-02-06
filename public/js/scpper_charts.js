function convertDate(dateString) {
    if (isIE()) {
        // holy shit fuck IE
        return new Date(dateString.substring(0, dateString.length-5));
    } else {
        return new Date(dateString);
    }        
}

function roundToDay(datetime) {
    var newDate = new Date(datetime);
    newDate.setHours(0);
    newDate.setMinutes(0);
    newDate.setSeconds(0);
    return newDate;
}

function roundToWeek(datetime) {
    var newDate = roundToDay(datetime);
    newDate.setDate(newDate.getDate()-newDate.getDay());    
    return newDate;
}

function roundToMonth(datetime) {
    var newDate = roundToWeek(datetime);
    newDate.setDate(0);
    return newDate;
}

/** Changes charts **/

function getDateGroupTooltip(date, group) {
    var period = '';            
    if (group === 'day') {
        period = date.format('UTC:mmm d, yyyy');
    } else if (group === 'week') {
        nextDate = new Date(date);
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
}

function getDateTooltipContent(date, group, text, value) {
    var period = getDateGroupTooltip(date, group);
    return '<b>' + period + ' </b><br>' + text+': <b>' + value + '</b> <br>'
}

function setFailedBackground(id)
{
    var elem = document.getElementById(id);
    elem.className = "chart-failed";
}

function removeChartContainer(id)
{
    var elem = document.getElementById(id);
    elem.parentNode.removeChild(elem);
}

function drawColumnChart(rawData, label, title, id, color) {
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
    var chart = new google.visualization.ColumnChart(document.getElementById(id));
    chart.draw(data, options);    
}

function drawLineChart(rawData, label, title, id, color) {
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
    var chart = new google.visualization.LineChart(document.getElementById(id));
    chart.draw(data, options);    
}

function drawTimeLineCharts(result, barLabel, barTitle, barId, lineLabel, lineTitle, lineId, color) {
    // Define the chart to be drawn
    if (!result.success || result.data.length < 2) {
        setFailedBackground(barId);
        removeChartContainer(lineId);        
        return;
    }
    // Convert returned values from string to date
    for (var i=0; i<result.data.length; i++) {
        result.data[i][0] = convertDate(result.data[i][0]);
    }
    // Prepare data for bar chart
    var barData = $.extend(true, [], result.data);
    for (var i=0; i<barData.length; i++) {
        var group = result.group;            
        barData[i][2] = getDateTooltipContent(barData[i][0], group, barLabel, barData[i][1]);
    }
    // Draw bar chart
    drawColumnChart(barData, barLabel, barTitle, barId, color);
    // Prepare data for line chart
    var lineData = $.extend(true, [], result.data);
    lineData[0][1] = lineData[0][1]+result.starting;
    for (var i=1; i<lineData.length; i++) {
        lineData[i][1] = lineData[i-1][1]+lineData[i][1];
    }
    // Draw line chart
    drawLineChart(lineData, lineLabel, lineTitle, lineId, color);
}

function drawMemberCharts(chartsData) {
    $.ajax({
        url: "/changes/getMemberChartData",
        type: "get",
        data: chartsData
    })
    .done(function (result) {
        drawTimeLineCharts(result, 'Joined', 'New members', 'members-joined', 'Members', 'Total members', 'members-total', '#94C282');
    })
    .fail(function () {
        setFailedBackground('members-joined');
        removeChartContainer('members-total');
    });
}

function drawPageCharts(chartsData) {
    $.ajax({
        url: "/changes/getPageChartData",
        type: "get",
        data: chartsData
    })
    .done(function (result) {
        drawTimeLineCharts(result, 'Created', 'New pages', 'pages-created', 'Pages', 'Total pages', 'pages-total', '#6DAECF');
    })
    .fail(function () {
        setFailedBackground('pages-created');
        removeChartContainer('pages-total');
    });
}

function drawRevisionCharts(chartsData) {
    $.ajax({
        url: "/changes/getRevisionChartData",
        type: "get",
        data: chartsData
    })
    .done(function (result) {
        drawTimeLineCharts(result, 'Created', 'New revisions', 'revisions-created', 'Revisions', 'Total revisions', 'revisions-total', '#DB9191');
    })
    .fail(function () {
        setFailedBackground('revisions-created');
        removeChartContainer('revisions-total');
    });
}

function drawVoteCharts(chartsData) {
    $.ajax({
        url: "/changes/getVoteChartData",
        type: "get",
        data: chartsData
    })
    .done(function (result) {
        drawTimeLineCharts(result, 'Votes', 'New votes', 'votes-cast', 'Votes', 'Total votes', 'votes-total', '#E0A96E');
    })
    .fail(function () {
        setFailedBackground('votes-cast');
        removeChartContainer('votes-total');
    });

}

function drawChangeCharts(chartsData) {
    if (chartsData.siteId < 0)
        return;
    drawMemberCharts(chartsData);
    drawPageCharts(chartsData);
    drawRevisionCharts(chartsData);
    drawVoteCharts(chartsData);
}

/** Page rating chart **/

function drawPageRatingChart(pageId) {
    $.ajax({
        url: "/page/ratingChart",
        type: "get",
        data: {pageId: pageId}
    })
    .done(function (result) {
        // Define the chart to be drawn
        if (!result.success || result.votes.length < 2) {
            setFailedBackground('rating-chart');    
            return;
        }
        // Convert returned values from string to date
        for (var i=0; i<result.votes.length; i++) {
            result.votes[i][0] = convertDate(result.votes[i][0]);
        }
        for (var i=0; i<result.revisions.length; i++) {
            result.revisions[i][0] = convertDate(result.revisions[i][0]);
        }        
        // Prepare data for line chart
        var rating = $.extend(true, [], result.votes);        
        for (var i=1; i<rating.length; i++) {
            rating[i][1] = rating[i-1][1]+rating[i][1];
        }
        var revisions = [];
        for (var i=0; i<result.revisions.length; i++) {
            if (result.revisions[i][0] > result.votes[0][0] && result.revisions[i][0] < result.votes[result.votes.length-1][0]) {
                revisions.push([result.revisions[i][0], null, result.revisions[i][1].index.toString(), result.revisions[i][1].comments]);
            }
        }
        // Draw line chart
        var lineData = new google.visualization.DataTable();
        lineData.addColumn({type: 'date', label: 'Time'});
        lineData.addColumn({type: 'number', label: 'Rating'});
        lineData.addRows(rating);
        var pointData = new google.visualization.DataTable();
        pointData.addColumn({type: 'date', label: 'Time'});
        pointData.addColumn({type: 'number', label: 'Rating'});
        pointData.addColumn({type: 'string', role: 'annotation'});
        pointData.addColumn({type: 'string', role: 'annotationText'});
        pointData.addRows(revisions);
        var data = google.visualization.data.join(lineData, pointData, 'full', [[0, 0], [1, 1]], [], [2, 3]);
        var last = 0;
        for (var i=1; i<data.getNumberOfRows(); i++) {
            if (data.getValue(i, 1)) {
                if (i-last > 1) {
                    for (var j=last+1; j<i; j++) {
                        var ratio = (data.getValue(j, 0)-data.getValue(last, 0))/(data.getValue(i, 0)-data.getValue(last, 0));
                        var interp = Math.round((data.getValue(last, 1)+(data.getValue(i, 1)-data.getValue(last, 1))*ratio));
                        data.setValue(j, 1, interp);
                    }
                }
                last = i;
            }
        }
        var options = {
            title: 'Rating',
            annotations: {
              textStyle: {
                fontSize: 14,
                bold: true,
                // The color of the text.
                color: '#DB9191',
              },            
            },
            chartArea: {width: '80%', height: '80%'},
            legend: 'none',
            colors: ['#E0A96E']
        };    
        var chart = new google.visualization.LineChart(document.getElementById('rating-chart'));
        chart.draw(data, options);    
    })
    .fail(function () {
        setFailedBackground('rating-chart');
    });
}
