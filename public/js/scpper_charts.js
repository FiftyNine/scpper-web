var chartsData = {
    siteId: -1,
    fromDate: '1899-01-01',
    toDate: '1899-01-01'
};

function isIE() {
  var ms_ie = false;
    var ua = window.navigator.userAgent;
    var old_ie = ua.indexOf('MSIE ');
    var new_ie = ua.indexOf('Trident/');

    if ((old_ie > -1) || (new_ie > -1)) {
        ms_ie = true;
    }
    return ms_ie;
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
    if (!result.success || result.data.length === 0) {
        setFailedBackground(barId);
        removeChartContainer(lineId);        
        return;
    } 
    // Convert returned values from string to date
    var date = null;
    var dateStr = '';
    for (var i=0; i<result.data.length; i++) {
        dateStr = result.data[i][0];
        if (isIE()) {
            // holy shit fuck IE
            date = new Date(dateStr.substring(0, dateStr.length-5));
        } else {
            date = new Date(dateStr);
        }
        
        result.data[i][0] = date;            
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

function drawMemberCharts() {
    $.ajax({
        url: "/recent/getMemberChartData",
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

function drawPageCharts() {
    $.ajax({
        url: "/recent/getPageChartData",
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

function drawRevisionCharts() {
    $.ajax({
        url: "/recent/getRevisionChartData",
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

function drawVoteCharts() {
    $.ajax({
        url: "/recent/getVoteChartData",
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

function drawChangeCharts() {
    if (chartsData.siteId < 0)
        return;
    drawMemberCharts();
    drawPageCharts();
    drawRevisionCharts();
    drawVoteCharts();
}