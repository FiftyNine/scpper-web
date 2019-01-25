/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

scpper.roundup = {
    seriesTimelapse: (function() {
        // VAR
        state = '';        
        playing = false;
        canvas = null;
        stateButton = null;
        context = null;
        pages = null;
        page = 0;
        time = null;
        fading = null;
        // CONST
        frameDuration = 3*60*60*1000, // ms        
        fillOccupied = 'green',
        fillEmpty = 'black',
        fillDeleted = 'red',
        fadeFrames = 30;
        cellsPerRow = 50;
        cellSize = 10; // px
        legendHeight = 20;
        headerHeight = 50;
        ticksWidth = 30;
    
        drawHeader = function() {
            context.save();
            context.fillStyle = 'white';
            context.fillRect(0, 0, canvas.width, headerHeight);
            context.font = '24px Helvetica';
            context.fillStyle = 'black';
            context.textAlign = 'center';
            context.fillText(time.format('mmmm yyyy'), canvas.width/2+ticksWidth, headerHeight-8);
            context.restore();
        };
    
        drawLegendItem = function(style, text) {
            context.font = '12px serif';
            context.fillStyle = style;
            context.fillRect(10, 5, cellSize, cellSize);
            context.fillText(text, 13+cellSize, 4+cellSize);
            textSize = context.measureText(text);
            context.translate(13+cellSize+textSize.width+20, 0);
        };
    
        drawTicks = function() {
            context.save();
            context.font = '12px serif';
            context.fillStyle = 'black';
            context.textAlign = 'right';
            context.translate(ticksWidth, headerHeight);
            for (var i=0; i<10; i++) {
                context.fillText(3000+i*100, -5, i*(cellSize+1)*2+10);
            }
            context.restore();
        };
    
        clean = function() {
            drawHeader();
            context.save();
            context.translate(0, headerHeight);
            context.fillStyle = 'black';
            context.fillRect(ticksWidth, 0, canvas.width-ticksWidth, canvas.height-legendHeight-headerHeight);
            context.restore();
        };
    
        reset = function() {
            if (state !== 'ok')
                return;
            pause();            
            time = new Date(pages[1].time.getTime()); // Magic const 1 because 0 page in this set is buggered
            page = 0;  
            fading = {};
            clean();
        },
    
        prepare = function(canvasId, stateButtonId, dataUrl) {
            canvas = document.getElementById(canvasId);
            playButton = document.getElementById(stateButtonId);
            context = canvas.getContext('2d', { alpha: false });
            context.save();
            context.fillStyle = 'white';
            context.fillRect(0, 0, canvas.width, headerHeight);
            context.fillRect(0, 0, ticksWidth, canvas.height);
            context.translate(0, canvas.height-legendHeight);
            context.fillRect(0, 0, canvas.width, legendHeight);
            context.translate(ticksWidth, 0);
            drawLegendItem(fillEmpty, ' - empty');
            drawLegendItem(fillOccupied, ' - occupied');
            drawLegendItem(fillDeleted, ' - deleted');
            context.restore();
            drawTicks();
            $.getJSON( dataUrl, function(data) {
                pages = [];
                for (var d in data) {
                    var page = {
                        number: Number(data[d].n)-3000,
                        time: scpper.convertDate(data[d].t),
                        deletion: data[d].d
                    }
                    pages.push(page);
                }       
                state = 'ok';                
                reset();
            });
        };
    
        drawCell = function (num, fill) {
            row = Math.floor(num/cellsPerRow);
            col = num%cellsPerRow;
            context.fillStyle = fill;
            context.fillRect(col*(cellSize+1), row*(cellSize+1), cellSize, cellSize);            
        };
    
        draw = function() {   
            time.setMilliseconds(time.getMilliseconds()+frameDuration);    
            drawHeader();
            context.save();
            context.translate(ticksWidth, headerHeight);
            while (page < pages.length && pages[page].time <= time) {
                var fill = '';
                if (pages[page].deletion) {
                    fill = fillDeleted;
                }
                else {
                    fill = fillOccupied;
                }
                fading[pages[page].number] = {
                    frame: fadeFrames,
                    deleted: pages[page].deletion
                }
                drawCell(pages[page].number, fill);
                page++;
            }
            var newFading = {};
            for (var num in fading) {
                if (fading.hasOwnProperty(num)) {                    
                    var fill = '';
                    if (fading[num].deleted) {
                        var redFade = 255/30*fading[num].frame;
                        fill = "rgba("+redFade+", 0, 0, 1)";
                    } else {
                        var greenFade = 127+(128/30*fading[num].frame);
                        fill = "rgba(0, "+greenFade+", 0, 1)";                        
                    }                    
                    drawCell(Number(num), fill);
                    fading[num].frame--;
                    if (fading[num].frame > 0) {
                        newFading[num] = fading[num];
                    }
                }
            }
            fading = newFading;
            context.restore();
            if (page >= pages.length)
                pause();
            if (playing)
                window.requestAnimationFrame(draw);            
        };
        
        play = function() {
            if (state === 'ok') {
                playing = true;
                playButton.src = "/img/pause-btn.png";
                window.requestAnimationFrame(draw);
            }
        };
        
        pause = function() {
            if (state === 'ok') {
                playing = false;
                playButton.src = "/img/play-btn.png";
                playButton.hidden = false;
            }                
        };
        
        return {
            init: function (canvasId, stateButtonId, dataUrl) {
                if (state === '') {
                    prepare(canvasId, stateButtonId, dataUrl)
                }
            },
            isPlaying: function() {
              return playing;  
            },
            switch: function() {
                if (state === 'ok') {
                    if (playing ) 
                        pause();
                    else {
                        if (page >= pages.length) {
                            reset();
                        }
                        play();
                    }
               }
            }
        };        
    }()),

    charts: {
        c1_3: {
            go: function(raw, id) {
                var data = new google.visualization.DataTable();
                var formatted = [];                
                raw.forEach(function(i) {
                    formatted.push([
                        new Date(i.Date),
                        i.Good,
                        `<b>Date: </b>${i.Date} <br><b>Remaining: </b>${i.Good} (${(i.Good/(i.Good+i.Bad)*100).toFixed(1)}%) <br>`,
                        i.Bad,
                        `<b>Date: </b>${i.Date} <br><b>Deleted: </b>${i.Bad} (${(i.Bad/(i.Good+i.Bad)*100).toFixed(1)}%) <br>`
                    ]);
                });
                data.addColumn({type: 'date', label: 'Time'});
                data.addColumn({type: 'number', label: 'Remaining'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                data.addColumn({type: 'number', label: 'Deleted'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addRows(formatted);
                var options = {
                    title: 'Number of posts by date',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Date', format: 'MMM'},                    
                    vAxis: {title: 'Pages'},
                    colors: ['#6DAECF', '#DB9191'],
                    tooltip: { isHtml: true },
                    isStacked: true,
                    bar: {groupWidth: '90%'},
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(data, options);                    
            }            
        },
     
        c1_4: {
            go: function(raw, id) {
                var data = new google.visualization.DataTable();
                var formatted = [];
                raw.forEach(function(i) {
                    var day = dateFormat.i18n.dayNames[i.Day+6];
                    formatted.push([
                        day,
                        i.Good,
                        `<b>Day: </b>${day} <br><b>Remaining: </b>${i.Good} (${(i.Good/(i.Good+i.Bad)*100).toFixed(1)}%) <br>`,
                        i.Bad,
                        `<b>Day: </b>${day} <br><b>Deleted: </b>${i.Bad} (${(i.Bad/(i.Good+i.Bad)*100).toFixed(1)}%) <br>`
                    ]);
                });
                data.addColumn({type: 'string', label: 'Day'});
                data.addColumn({type: 'number', label: 'Remaining'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                data.addColumn({type: 'number', label: 'Deleted'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addRows(formatted);
                var options = {    
                    title: 'Number of posts by weekday',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Day'},
                    vAxis: {title: 'Pages'},
                    colors: ['#6DAECF', '#DB9191'],
                    tooltip: { isHtml: true },
                    isStacked: true,
                    
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(data, options);                    
            }            
        },
    
        c1_5: {
            go: function(raw, id) {
                var data = new google.visualization.DataTable();
                var formatted = [];
                raw.forEach(function(i) {
                    var hstr = i.Hour.toString();
                    if (hstr.length === 1)
                        hstr="0"+hstr;
                    var hintStr = `${hstr}:00&nbsp-&nbsp${hstr}:59`;
                    formatted.push([
                        hstr,
                        i.Good,
                        `<b>Time: </b>${hintStr} <br><b>Remaining: </b>${i.Good} (${(i.Good/(i.Good+i.Bad)*100).toFixed(1)}%) <br>`,
                        i.Bad,
                        `<b>Time: </b>${hintStr} <br><b>Deleted: </b>${i.Bad} (${(i.Bad/(i.Good+i.Bad)*100).toFixed(1)}%) <br>`
                    ]);
                });
                data.addColumn({type: 'string', label: 'Hour'});
                data.addColumn({type: 'number', label: 'Remaining'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                data.addColumn({type: 'number', label: 'Deleted'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addRows(formatted);
                var options = {
                    title: 'Number of posts by hour',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Hour'},
                    vAxis: {title: 'Pages'},                    
                    colors: ['#6DAECF', '#DB9191'],
                    tooltip: { isHtml: true },
                    isStacked: true
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(data, options);                    
            }            
        },

        c1_20: {
            go: function(pages, id) {
                var data = new google.visualization.DataTable();
                data.addColumn({type: 'number', label: 'Rating'});
                data.addColumn({type: 'number', label: 'PageCount'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                var formatted = [];                
                var minRating = 1e9;
                var maxRating = -1e9;
                for (var pid in pages) {
                    if (pages.hasOwnProperty(pid)) {
                        minRating = Math.min(minRating, pages[pid].r);
                        maxRating = Math.max(maxRating, pages[pid].r);
                    }
                }
                var step = 10;
                minRating = Math.floor(minRating/step)*10;
                maxRating = Math.ceil(maxRating/step)*10;
                formatted.length = Math.ceil((maxRating-minRating)/step);
                for (var i=0; i<formatted.length; i++) {
                    formatted[i] = [
                        minRating+step*(i+0.5),
                        0,
                        null
                    ]
                }
                for (var pid in pages) {
                    if (pages.hasOwnProperty(pid)) {
                        var i = Math.floor((pages[pid].r-minRating)/step);
                        formatted[i][1]++;
                    }                
                }
                formatted.forEach(function(f, index) {
                    f[2] = `<b>Rating: </b>${minRating+index*step} to ${minRating+(index+1)*step-1} <br><b>Pages: </b>${f[1]} `;
                });
                data.addRows(formatted);
                var options = {
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Rating'},
                    vAxis: {title: 'Pages'},                    
                    tooltip: { isHtml: true },
                    isStacked: true,
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                                        
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(data, options);                    
            }            
        },        
        
        c1_6_1: {
            go: function(raw, hidden, id) {
                var data = new google.visualization.DataTable();                
                var formatted = [];
                data.addColumn({type: 'number', label: 'Length'});
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                var goodColorStyle = 'fill-color: #6DAECF;';
                var badColorStyle = 'fill-color: #DB9191;';                
                var numFormat = new Intl.NumberFormat('en-US');
                for (var pid in raw) {
                    if (raw.hasOwnProperty(pid)) {                   
                        var style = "stroke-color: #333333; stroke-width: 0.5; " + (raw[pid].d ? badColorStyle : goodColorStyle);
                        var col = 13;
                        switch (raw[pid].k) {
                            case 1: // SCP
                                col = 1;
                                break;
                            case 2: // Tale
                                col = 4;
                                break;
                            case 3: // Joke
                                col = 7;
                                break;
                            case 5: // GOI
                                col = 10;
                                break;                                          
                        }
                        var row = [raw[pid].l];
                        row.length = 16;
                        row[col] = raw[pid].r;
                        row[col+1] = style;
                        row[col+2] = `<b><a href="/page/${pid}">${raw[pid].t + (raw[pid].at ? " - "+raw[pid].at : "")}</a></b> 
                             <br>
                             <b>Length: </b>${numFormat.format(raw[pid].l)} 
                             <br>
                             <b>Rating: </b>${raw[pid].r} `;
                        formatted.push(row);
                    }
                }                                                
                data.addRows(formatted);
                var options = {
                    title: 'Individual pages',
                    height: 600,
                    pointSize: 6,
                    series: [
                        {pointShape: 'circle', color: '#6DAECF', pointsVisible: !hidden.includes(0)},
                        {pointShape: 'triangle', color: '#6DAECF', pointsVisible: !hidden.includes(1)},
                        {pointShape: 'square', color: '#6DAECF', pointsVisible: !hidden.includes(2)},
                        {pointShape: 'diamond', color: '#6DAECF', pointsVisible: !hidden.includes(3)},
                        {pointShape: 'polygon', color: '#6DAECF', pointsVisible: !hidden.includes(4)}
                    ],                    
                    hAxis: {title: 'Source length, characters'},
                    vAxis: {title: 'Rating'},
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    tooltip: { isHtml: true, trigger: 'both' },
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      keepInBounds: true,
                      maxZoomIn: 16.0
                    },
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ScatterChart(div);
                google.visualization.events.addListener(chart, 'click', function (a) {
                    chart.setSelection([])
                });
                chart.draw(data, options);                    
            }                        
        },
        
        c1_6_2: {
            go: function(raw, id) {
                var data = new google.visualization.DataTable();
                data.addColumn({type: 'number', label: 'Length'});
                data.addColumn({type: 'number', label: 'Under 0 pts'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number', label: '0-19 pts'});                
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                                               
                data.addColumn({type: 'number', label: '20-49 pts'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number', label: '50-99 pts'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number', label: 'Over 100 pts'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                               
                var ratingIntervals = [0, 20, 50, 100, 100000];
                var minLength = 1e9;
                var maxLength = -1;
                for (var pid in raw) {
                    if (raw.hasOwnProperty(pid)) {
                        minLength = Math.min(minLength, raw[pid].l);
                        maxLength = Math.max(maxLength, raw[pid].l);
                    }
                }
                if (maxLength <= minLength) 
                    return;
                var step = 1000; 
                var formatted = [];
                for (var i=0; i<Math.ceil(maxLength/step); i++) {
                    for (var j=0; j<2; j++)
                        formatted.push([
                            (i+j)*step,
                            0,
                            null,
                            0,
                            null,
                            0,
                            null,
                            0,
                            null,
                            0,
                            null
                        ]);
                }
                for (var pid in raw) {
                    if (raw.hasOwnProperty(pid)) {
                        var i = Math.floor(raw[pid].l/step);
                        for (var j=0; j < ratingIntervals.length; j++) {
                            if (raw[pid].r < ratingIntervals[j]) {
                                formatted[i*2+1][1+j*2]++;
                                break;
                            }
                        }
                    }
                }
                var numFormat = new Intl.NumberFormat('en-US');
                formatted.forEach(function(i, index) {
                    if (index%2 === 1)
                        for (var j=0; j < ratingIntervals.length; j++) {                        
                            i[1+j*2+1] = `<b>Length: </b>${numFormat.format(formatted[index-1][0])} to ${numFormat.format(formatted[index][0])} <br><b>Pages: </b>${i[1+j*2]} <br>`;
                            formatted[index-1][1+j*2] = i[1+j*2];
                            formatted[index-1][1+j*2+1] = i[1+j*2+1];
                        }
                });
                data.addRows(formatted);
                var options = {
                    title: 'Distribution of pages by length (grouped by rating)',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    tooltip: { isHtml: true },
                    hAxis: {
                        title: 'Source length, characters',
                        minValue: 0
                    },
                    vAxis: {title: 'Pages'},                    
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                         
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.LineChart(div);
                chart.draw(data, options);                    
            }            
        },
        
        c1_7: {
            go: function(pages, id) {
                var data = new google.visualization.DataTable();
                data.addColumn({type: 'number', label: 'Rating'});
                data.addColumn({type: 'number', label: 'Pages with image, %'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                data.addColumn({type: 'number'});
                var minRating = 1e9;
                var maxRating = -1e9;
                for (var pid in pages) {
                    if (pages.hasOwnProperty(pid)) {
                        minRating = Math.min(minRating, pages[pid].r);
                        maxRating = Math.max(maxRating, pages[pid].r);
                    }
                }
                if (minRating > maxRating)
                    return;
                var step = 10;
                var dataPoints = [minRating, 0, 10, 20, 30, 40, 50, 75, 100, 150, 200, maxRating+1];
                var formatted = [];
                formatted.push([minRating, 0, null, 0]);
                for (var i=1; i<dataPoints.length-1; i++) {
                    formatted.push([dataPoints[i], 0, null, 0]);
                    formatted.push([dataPoints[i], 0, null, 0]);
                }
                formatted.push([maxRating, 0, null, 0]);
                for (var pid in pages) {
                    if (pages.hasOwnProperty(pid)) {
                        // var i = Math.floor((pages[pid].r-minRating)/step);
                        for (var i=1; i<formatted.length; i++)
                            if (pages[pid].r < formatted[i][0]) {
                                if (pages[pid].i) 
                                    formatted[i][1]++;
                                formatted[i][3]++;
                                break;
                            }
                    }
                }
                formatted.forEach(function(i, index) {
                    if (index%2 === 1) {
                        i[1] = (i[1]/i[3])*100;
                        i[2] = `<b>Rating: </b>${formatted[index-1][0]} to ${formatted[index][0]} <br><b>Pages with image: </b>${i[1].toFixed(1)}% (${i[3]} pages) <br>`;
                        formatted[index-1][1] = i[1];
                        formatted[index-1][2] = i[2];
                        formatted[index-1][3] = i[3];
                    } 
                });
                data.addRows(formatted);
                var options = {
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    tooltip: { isHtml: true },
                    legend: 'none',
                    vAxis: {title: 'With image, %'},
                    hAxis: {
                        minValue: 0,
                        title: 'Rating'              
                    },
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",                      
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                         
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.LineChart(div);
                var dataView = new google.visualization.DataView(data);
                dataView.hideColumns([3]);
                chart.draw(dataView, options);                    
            }            
        },
        
        c1_8: {
            go: function(pages, users, hidden, newbies, id) {
                var data = new google.visualization.DataTable();                
                var formatted = [];
                data.addColumn({type: 'number', label: 'Length'});
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number'});
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                var goodColorStyle = 'fill-color: #6DAECF;';
                var badColorStyle = 'fill-color: #DB9191;';                
                var numFormat = new Intl.NumberFormat('en-US');
                for (var pid in pages) {                    
                    if (pages.hasOwnProperty(pid)) {
                        var auids = Object.getOwnPropertyNames(pages[pid].a);
                        if (auids.length !== 1)
                            continue;
                        var author = pages[pid].a[auids[0]];
                        if (newbies && (author.es>0))
                            continue;
                        var style = "stroke-color: #333333; stroke-width: 0.5; " + (pages[pid].d ? badColorStyle : goodColorStyle);
                        var col = 13;
                        switch (pages[pid].k) {
                            case 1: // SCP
                                col = 1;
                                break;
                            case 2: // Tale
                                col = 4;
                                break;
                            case 3: // Joke
                                col = 7;
                                break;
                            case 5: // GOI
                                col = 10;
                                break;                                          
                        }
                        var row = [Number(author.ew)];
                        row.length = 16;
                        row[col] = pages[pid].r;
                        row[col+1] = style;
                        if (!users[auids[0]]) {
                            window.alert();
                        }
                        row[col+2] = `<b><a href="/page/${pid}">${pages[pid].t + (pages[pid].at ? " - "+pages[pid].at : "")}</a></b> 
                             <br>
                             <b>Author: </b><a href="/user/${auids[0]}">${users[auids[0]].dn}</a>
                             <br>
                             <b>Weeks since joining: </b>${numFormat.format(author.ew)} 
                             <br>
                             <b>Rating: </b>${pages[pid].r} `;
                        formatted.push(row);
                    }
                }                                                
                data.addRows(formatted);
                var options = {
                    title: 'Individual pages',
                    height: 600,
                    pointSize: 6,
                    series: [
                        {pointShape: 'circle', color: '#6DAECF', pointsVisible: !hidden.includes(0)},
                        {pointShape: 'triangle', color: '#6DAECF', pointsVisible: !hidden.includes(1)},
                        {pointShape: 'square', color: '#6DAECF', pointsVisible: !hidden.includes(2)},
                        {pointShape: 'diamond', color: '#6DAECF', pointsVisible: !hidden.includes(3)},
                        {pointShape: 'polygon', color: '#6DAECF', pointsVisible: !hidden.includes(4)}
                    ],                    
                    hAxis: {title: 'Weeks since joining'},
                    vAxis: {title: 'Rating'},
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    tooltip: { isHtml: true, trigger: 'both' },
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      keepInBounds: true,
                      maxZoomIn: 16.0
                    },
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ScatterChart(div);
                google.visualization.events.addListener(chart, 'click', function (a) {
                    chart.setSelection([])
                });
                chart.draw(data, options);                    
            }                        
        },
        
        prepare_1_8_x_data: function (pages) {
            var res = {
                maxExp: -1e9,
                maxVotes: -1e9,
                maxAttempts: -1e9,
                maxPages: -1e9,
                filtered: []              
            };
            for (var pid in pages) {                  
                if (pages.hasOwnProperty(pid)) {
                    var auids = Object.getOwnPropertyNames(pages[pid].a);                        
                    if (auids.length !== 1)
                        continue;
                    var author = pages[pid].a[auids[0]];
                    if (author.hasOwnProperty('ew') && author.ew >= 0)
                        res.maxExp = Math.max(res.maxExp, author.ew);
                    res.maxVotes = Math.max(res.maxVotes, author.ev);
                    res.maxAttempts = Math.max(res.maxAttempts, author.ea);
                    res.maxPages = Math.max(res.maxPages, author.es);
                        res.filtered.push({
                            'r': pages[pid].r,
                            'd': pages[pid].d,
                            'a': author
                        });
                    }
                }
            return res;
        },
        
        c1_8_x: {
            go: function(pages, cumulative, newbies, hDataPoints, hMaxValue, hPropName, hLabel, id) {
                if (hMaxValue < 0)
                    return;                
                var data = new google.visualization.DataTable();
                data.addColumn({type: 'number'});
                data.addColumn({type: 'number', label: 'Success ratio'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                
                data.addColumn({type: 'number', label: 'Average rating (for "good" pages)'});                
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});                                               
                data.addColumn({type: 'number'});              
                var formatted = [];
                // formatted.length = hDataPoints.length;
                for (var i=0; i<hDataPoints.length; i++) {
                    for (var j=0; j<2; j++) {
                        formatted.push([
                            hDataPoints[i+j],
                            0,
                            null,
                            0,
                            null,
                            0,
                        ]);
                    }
                }
                pages.forEach(function(p) {
                    if (p.a.hasOwnProperty(hPropName) && (p.a[hPropName] >= 0) && (!newbies || (p.a['es'] == 0)))
                        for (var i=1; i<formatted.length; i+=2) {
                            if (p.a[hPropName] < formatted[i][0]) {
                                if (!p.d) {
                                    formatted[i][1]++;
                                    formatted[i][3]+=p.r;
                                }
                                formatted[i][5]++;   
                                if (!cumulative)
                                    break;
                            }
                        }
                });
                formatted.forEach(function(i, index) {
                    if (index % 2 == 0)
                        return;
                    var label = '';
                    if (index === 0) 
                        label = i[0]-1;
                    else 
                        if (cumulative)
                            label = `&lt;${i[0]}`;
                        else if (formatted[index][0] - formatted[index-1][0] === 1)
                            label = formatted[index-1][0];
                        else
                            label = `${formatted[index-1][0]} to ${formatted[index][0]-1}`;                            
                    i[3] /= i[1];
                    i[4] = `<b>${hLabel}: </b>${label} <br><b>Average rating: </b>${i[3].toFixed(1)} (${i[1]} pages) <br>`;
                    i[1] = (i[1]/i[5])*100;                    
                    i[2] = `<b>${hLabel}: </b>${label} <br><b>Good: </b>${i[1].toFixed(1)}% (${i[5]} pages) <br>`;                    
                    for (var j=1; j<formatted[index].length; j++) {
                        formatted[index-1][j]=formatted[index][j];
                    }
                });
                data.addRows(formatted);
                var options = {
                    title: hLabel,
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    tooltip: { isHtml: true },
                    series: {
                      0: {targetAxisIndex: 0},
                      1: {targetAxisIndex: 1}
                    },
                    vAxes: {
                      // Adds titles to each axis.
                      0: {title: 'Percentage of good pages'},
                      1: {title: 'Average rating of good pages'}
                    },
                    hAxis: {
                        title: hLabel
                    },
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                         
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.LineChart(div);
                var dataView = new google.visualization.DataView(data);
                dataView.hideColumns([5]);
                chart.draw(dataView, options);                    
            }            
        },

        c1_19: {
            go: function(raw, id) {
                var data = new google.visualization.DataTable();
                data.addColumn({type: 'number', label: 'Days'});
                data.addColumn({type: 'number', label: 'Mean'});
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                data.addColumn({id:'i0', type:'number', role:'interval'});
                data.addColumn({id:'i1', type:'number', role:'interval'});
                data.addColumn({id:'i2', type:'number', role:'interval'});
                data.addColumn({id:'i2', type:'number', role:'interval'});
                data.addColumn({id:'i1', type:'number', role:'interval'});
                data.addColumn({id:'i0', type:'number', role:'interval'});
                
                var formatted = [];                
                formatted.push([0, 0, '', 0, 0, 0, 0, 0, 0]);
                for (var day in raw) {                  
                    if (raw.hasOwnProperty(day)) {
                        formatted.push([
                            Number(day),
                            raw[day]['m']*100,
                            '',
                            raw[day]['95'][0]*100,
                            raw[day]['75'][0]*100,
                            raw[day]['50'][0]*100,
                            raw[day]['50'][1]*100,
                            raw[day]['75'][1]*100,
                            raw[day]['95'][1]*100
                        ]);
                    }
                }
                formatted.forEach(function(i) {
                    i[2] = `<b>Day: </b>${i[0]} <br>
                            <b>Mean: </b>${i[1].toFixed(1)}% <br>
                            <b>50 percentile: </b>${i[5].toFixed(1)}% to ${i[6].toFixed(1)}% <br>
                            <b>75 percentile: </b>${i[4].toFixed(1)}% to ${i[7].toFixed(1)}% <br>
                            <b>95 percentile: </b>${i[3].toFixed(1)}% to ${i[8].toFixed(1)}% <br>`;
                });
                data.addRows(formatted);
                var options = {
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Days'},                    
                    vAxis: {title: 'Percentage of rating at 1 year'},
                    curveType: 'function',
                    tooltip: { isHtml: true },
                    intervals: { style: 'area' },                    
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                                       
                };    
                var div = document.getElementById(id);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.LineChart(div);
                chart.draw(data, options);                    
            }            
        },        
    },
    
    users: {
        joinsByDate: {
            go: function (data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'date', label: 'Time'});
                table.addColumn({type: 'number', label: 'Joined'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    var date = new Date(i.Date);
                    formatted.push([
                        date,
                        i.Count,
                        `<b>Month: </b>${date.format('mmmm')} <br><b>Joined: </b>${i.Count} <br>`
                    ]);
                });
                
                table.addRows(formatted);
                var options = {
                    title: 'Number of new users by date',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Month', format: 'MMM'},
                    vAxis: {
                        title: 'Joins',
                        minValue: 0
                    },
                    colors: ['#94C282'],
                    tooltip: { isHtml: true },
                    bar: {groupWidth: '90%'},
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                    
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(table, options);                   
            }
        },
        
        joinsByWeekday: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'string', label: 'Day'});
                table.addColumn({type: 'number', label: 'Joined'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    var day = dateFormat.i18n.dayNames[i.Day+6];
                    formatted.push([
                        day,
                        i.Count,
                        `<b>Day: </b>${day} <br><b>Joined: </b>${i.Count} <br>`
                    ]);
                });
                table.addRows(formatted);
                var options = {    
                    title: 'Number of new users by weekday',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Day'},
                    vAxis: {
                        title: 'Joins',
                        minValue: 0
                    },
                    colors: ['#94C282'],
                    tooltip: { isHtml: true },                    
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(table, options);                    
            }            
        },
    
        joinsByHour: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'string', label: 'Hour'});
                table.addColumn({type: 'number', label: 'Joins'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    var hstr = i.Hour.toString();
                    if (hstr.length === 1)
                        hstr="0"+hstr;
                    var hintStr = `${hstr}:00&nbsp-&nbsp${hstr}:59`;
                    formatted.push([
                        hstr,
                        i.Count,
                        `<b>Time: </b>${hintStr} <br><b>Joined: </b>${i.Count} <br>`
                    ]);
                });                
                table.addRows(formatted);
                var options = {
                    title: 'Number of new users by hour',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Hour'},
                    vAxis: {title: 'Joins'},
                    colors: ['#94C282'],
                    tooltip: { isHtml: true },
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(table, options);                    
            }            
        },

        daysToPost: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'number', label: 'Days since joining'});
                table.addColumn({type: 'number', label: 'First posts'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                makeHint = function(days, posts) {
                    return `<b>Days since joining: </b>${days} <br><b>First posts: </b>${posts} <br>`;
                };
                data.forEach(function(item, index) {
                    if (index > 0) 
                        for (var j=data[index-1].Days+1; j<item.Days; j++) {
                            formatted.push([j, 0, makeHint(j, 0)]);                            
                        }
                    formatted.push([item.Days, item.Count, makeHint(item.Days, item.Count)]);
                });
                table.addRows(formatted);
                var options = {    
                    title: 'Days between joining and first post',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Days'},
                    vAxis: {
                        title: 'First posts',
                        minValue: 0
                    },
                    tooltip: { isHtml: true },
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                    
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.LineChart(div);
                chart.draw(table, options);                    
            }            
        },
        
        daysToSuccess: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'number', label: 'Days since joining'});
                table.addColumn({type: 'number', label: 'First successful posts'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    formatted.push([
                        i.Days,
                        i.Count,
                        `<b>Days since joining: </b>${i.Days} <br><b>First successes: </b>${i.Count} <br>`
                    ]);
                });
                table.addRows(formatted);
                var options = {    
                    title: 'Days between joining and first successful page',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Days'},
                    vAxis: {
                        title: 'First successes',
                        minValue: 0
                    },
                    bar: {groupWidth: '90%'},
                    colors: ['#6DAECF'],
                    tooltip: { isHtml: true },     
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                    
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(table, options);                    
            }            
        },        
        
        postsToSuccess: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'number', label: 'Failed posts'});
                table.addColumn({type: 'number', label: 'First successful posts'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    formatted.push([
                        i.Fails,
                        i.Count,
                        `<b>Failed pages: </b>${i.Fails} <br><b>First successes: </b>${i.Count} <br>`
                    ]);
                });
                table.addRows(formatted);
                var options = {    
                    title: 'Failed attempts until first successful page',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Fails'},
                    vAxis: {
                        title: 'First successes',
                        minValue: 0
                    },
                    colors: ['#DB9191'],
                    tooltip: { isHtml: true },     
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(table, options);                    
            }            
        },
        
        activeSpanAfterPost: {
            go: function(data, title, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'number', label: 'Days'});
                table.addColumn({type: 'number', label: 'Still active'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(item) {
                    if (item.Days < 0)
                        return;
                    for (var i=0; i<formatted.length; i++)
                        formatted[i][1]+=item.Count
                    formatted.push([
                        item.Days,
                        item.Count,
                        null
                    ]);
                });
                formatted.forEach(function(row) {
                    row[2] = `<b>Days: </b>${row[0]} <br><b>Still active: </b>${row[1]} <br>`
                });                
                table.addRows(formatted);
                var options = {
                    title: title,
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Days'},
                    vAxis: {
                        title: 'Still active',
                        minValue: 0
                    },
                    colors: ['#94C282'],
                    tooltip: { isHtml: true },
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');
                var chart = new google.visualization.LineChart(div);
                chart.draw(table, options);
            }
        },
        
        activeSpan: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'number', label: 'Days active'});
                table.addColumn({type: 'number', label: 'Users'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    formatted.push([
                        i.Days,
                        i.Count,
                        `<b>Days active: </b>${i.Days} <br><b>Users: </b>${i.Count} <br>`
                    ]);
                });
                table.addRows(formatted);
                var options = {    
                    title: 'Distribution of users by activity period',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {title: 'Days'},
                    vAxis: {
                        title: 'Users',
                        minValue: 0
                    },
                    bar: {groupWidth: '90%'},
                    colors: ['#94C282'],
                    tooltip: { isHtml: true },     
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                    
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.ColumnChart(div);
                chart.draw(table, options);                    
            }            
        },        
        
        activeOverTime: {
            go: function(data, containerId) {
                var table = new google.visualization.DataTable();
                table.addColumn({type: 'date', label: 'Date'});
                table.addColumn({type: 'number', label: 'Active users'});
                table.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
                var formatted = [];
                data.forEach(function(i) {
                    date = new Date(i.Date);
                    formatted.push([
                        date,
                        i.Count,
                        `<b>Date: </b>${date.format(dateFormat.masks.isoDate)} <br><b>Active users: </b>${i.Count} <br>`
                    ]);
                });
                table.addRows(formatted);
                var options = {    
                    title: 'Active users over time',
                    legend: 'none',
                    height: 400,
                    chartArea: {left: 60, bottom: 40, top: 20, width: '85%'},
                    hAxis: {
                        title: 'Date', 
                        format: 'MMM'
                    },
                    vAxis: {
                        title: 'Active users',
                        minValue: 0
                    },
                    colors: ['#94C282'],
                    tooltip: { isHtml: true },     
                    explorer: { 
                      actions: ['dragToZoom', 'rightClickToReset'],
                      axis: "horizontal",
                      keepInBounds: true,
                      maxZoomIn: 4.0
                    },                    
                };    
                var div = document.getElementById(containerId);
                $(div).removeClass('chart-container').addClass('chart-loaded');        
                var chart = new google.visualization.LineChart(div);
                chart.draw(table, options);                    
            }            
        },            
    }
};
