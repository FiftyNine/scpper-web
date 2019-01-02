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
        // CONST
        frameDuration = 3*60*60*1000, // ms        
        fillOccupied = 'green',
        fillEmpty = 'black',
        fillDeleted = 'red',
        cellsPerRow = 50;
        cellSize = 10; // px
        legendHeight = 20;
        headerHeight = 50;
    
        drawHeader = function() {
            context.save();
            context.fillStyle = 'white';
            context.fillRect(0, 0, canvas.width, headerHeight);
            context.font = '24px Helvetica';
            context.fillStyle = 'black';
            context.textAlign = 'center';
            context.fillText(time.format('mmmm yyyy'), canvas.width/2, headerHeight-8);
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
    
        clean = function() {
            drawHeader();
            context.save();
            context.translate(0, headerHeight);
            context.fillStyle = 'black';
            context.fillRect(0, 0, canvas.width, canvas.height-legendHeight-headerHeight);
            context.restore();
        };
    
        reset = function() {
            if (state !== 'ok')
                return;
            pause();            
            time = new Date(pages[1].time.getTime()); // Magic const 1 because 0 page in this set is buggered
            page = 0;            
            clean();
        },
    
        prepare = function(canvasId, stateButtonId, dataUrl) {
            canvas = document.getElementById(canvasId);
            playButton = document.getElementById(stateButtonId);
            context = canvas.getContext('2d', { alpha: false });
            context.save();
            context.fillStyle = 'white';
            context.fillRect(0, 0, canvas.width, headerHeight);
            context.translate(0, canvas.height-legendHeight);
            context.fillRect(0, 0, canvas.width, legendHeight);
            drawLegendItem(fillEmpty, ' - empty');
            drawLegendItem(fillOccupied, ' - occupied');
            drawLegendItem(fillDeleted, ' - deleted');
            context.restore();
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
    
        draw = function() {   
            time.setMilliseconds(time.getMilliseconds()+frameDuration);    
            drawHeader();
            context.save();
            context.translate(0, headerHeight);
            while (page < pages.length && pages[page].time <= time) {
                row = Math.floor(pages[page].number/cellsPerRow);
                col = pages[page].number%cellsPerRow;
                if (pages[page].deletion)
                    context.fillStyle = fillDeleted;
                else
                    context.fillStyle = fillOccupied;
                context.fillRect(col*(cellSize+1), row*(cellSize+1), cellSize, cellSize);
                page++;
            }
            context.restore();
            if (page >= pages.length)
                pause();
            if (playing)
                window.requestAnimationFrame(draw);            
        };
        
        play = function() {
            if (state === 'ok') {
                playing = true;
                playButton.src = "img/pause-btn.png";
                window.requestAnimationFrame(draw);
            }
        };
        
        pause = function() {
            if (state === 'ok') {
                playing = false;
                playButton.src = "img/play-btn.png";
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
    }())    
}
