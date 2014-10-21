(function( $ ) {'use strict';

$.fn.donutChart = function( options ) {

  var defaults = {
    width: 700,
    height: 550,
    radius: 180,//radius of a circle
    innerRadius: 60,//inner radius of the circle
    data: false,//data in json format [{"title": "title 1", "value": 20, "color":#3333,"description":"description 1"},...]
    textBox: '#donutDesc',//light box id or class
    title: '.title', //title box inside of light box
    desc: '.desc',//description box inside of light box
    labelColor: '#2b79ec', //color of labels
    labelActive: '#333333',//active label color
    speed: 500,//animation speed
    font: {
      'font-size': '14px',
      'font-family': 'Arial'
    },
    innerCicrleColor: '#f4f4f4',
    lineColor: '#999'
  };

  var opt = $.extend( {}, defaults, options );
  
  var data = opt.data;

  var paper = Raphael($(this).attr('id'),opt.width,opt.height);

  var cx = paper.width/2,//center position on x
      cy = paper.height/2,//center position on y
      r  = opt.radius,//radius
      angle = 0,//Angle
      total = 100,//percentage sum of sectors
      rad = Math.PI / 180,
      spaceToText = 36,//space in pixels from circle border to text
      lineOut = spaceToText/ 2,//length of line out of circle
      labelColor = opt.labelColor,
      labelActive = opt.labelActive,
      lineColor = opt.lineColor,
      activeSectorInfo = [];

  var sectors = paper.set(),
      labels = paper.set(),
      lines = paper.set();

  var infoTitle = $(opt.textBox).find(opt.title),
      infoDesc  = $(opt.textBox).find(opt.desc);

  // Making coordinate to sector painting
  function sectorCoordinate(cx, cy, r, startAngle, endAngle) {
    var x1 = cx + r * Math.cos(-startAngle * rad),
      x2 = cx + r * Math.cos(-endAngle * rad),
      y1 = cy + r * Math.sin(-startAngle * rad),
      y2 = cy + r * Math.sin(-endAngle * rad);
    return ["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"];
  }

  function makeLabel(value,title){
    var txt = value + "% " + title;//title text
    var res = '';
    var arr = txt.split(' ');//array of words
    var lengthPerRow = 12;//approximately size of text chars in one row
    var rows = Math.ceil(txt.length/lengthPerRow);//approximately rows
    var iterations = Math.ceil(arr.length/rows);//how many elements from array join to one row
    for(var i = 0; i<=arr.length; i++){
      var text = arr.splice(0,iterations).join(' ');
      if (text != '') {
        res += text + '\n';
      }//if
    }//for
    return res;
  }//make label

  function fireEvent(element,event) {
    if (document.createEventObject) {
      // dispatch for IE
      var evt = document.createEventObject();
      return element.fireEvent('on'+event,evt)
    } else {
      // dispatch for firefox + others
      var evt = document.createEvent("HTMLEvents");
      evt.initEvent(event, true, true); // event type,bubbling,cancelable
      return !element.dispatchEvent(evt);
    }
  }//fireEvent

  function pieGroup(index){

    var value = data[index].value; //sector width
    var angleplus = 360 * value / total;
    var popangle = angle + (angleplus / 2);
    //console.log();

    //Creating sector
    var sector = paper.path(sectorCoordinate(cx, cy, r, angle, angle + angleplus))
      .attr({
        fill: data[index].color,
        "stroke-width": 0,
        'cursor':'pointer'
      });
    sectors.push(sector);
    //Creating line
    var lineStartX = cx + (r - r/3) * Math.cos(-popangle * rad);
    var lineStartY = cy + (r - r/3) * Math.sin(-popangle * rad);
    var line = paper.path(["M", lineStartX, lineStartY, "L", cx + (r + lineOut) * Math.cos(-popangle * rad), cy + (r + lineOut) * Math.sin(-popangle * rad)])
      .attr({
        fill: lineColor,
        stroke:lineColor,
        "stroke-width": 1
      });
    lines.push(line);
    //Creating label
    var txtX = cx + (r + spaceToText) * Math.cos(-popangle * rad);
    var txtY = cy + (r + spaceToText) * Math.sin(-popangle * rad);
    var label = paper.text(txtX, txtY, makeLabel(data[index].value,data[index].title))
      .attr({
        fill: labelColor
      })
      .attr(opt.font);
      var anchor;
      if((Math.cos(-popangle * rad)) > 0){
        anchor = {'text-anchor': 'start'}
      }else{
        anchor = {'text-anchor': 'end'}
      }
    label.attr(anchor);
    labels.push(label);
    //New coordinates for label animation
    var txtNewX = cx + (r + spaceToText*1.5) * Math.cos(-popangle * rad);
    var txtNewY = cy + (r + spaceToText*1.5) * Math.sin(-popangle * rad);

    //Sector Events
    sector.mouseover(function () {
      sector.stop().animate({transform: "s1.1 1.1 " + cx + " " + cy}, opt.speed, "elastic");
      line.stop().animate({transform: "s1.1 1.1 " + cx + " " + cy}, opt.speed, "elastic");
      label.stop().animate({x:txtNewX,y:txtNewY}, opt.speed, "elastic");
      label.attr('font-weight','bold');
    }).mouseout(function () {
      //if it is active sector
      if(index == activeSectorInfo[0]){
        return false;
      }
      sector.stop().animate({transform: ""}, opt.speed, "elastic");
      line.stop().animate({transform: ""}, opt.speed, "elastic");
      label.stop().animate({x:txtX,y:txtY}, opt.speed, "elastic");
      label.attr('font-weight','normal');
    }).click(function(e){
      labels.attr('fill',labelColor);
      label.attr('fill',labelActive);
      infoTitle.text(data[index].value + '% ' + data[index].title);
      infoDesc.text(data[index].description);

      //animate sector and de-animate active sector
      if(index != activeSectorInfo[0]){
        sector.stop().animate({transform: "s1.1 1.1 " + cx + " " + cy}, opt.speed, "elastic");
        line.stop().animate({transform: "s1.1 1.1 " + cx + " " + cy}, opt.speed, "elastic");
        label.stop().animate({x:txtNewX,y:txtNewY}, opt.speed, "elastic");
        label.attr('font-weight','bold');
        sectors[activeSectorInfo[0]].stop().animate({transform: ""}, opt.speed, "elastic");
        lines[activeSectorInfo[0]].stop().animate({transform: ""}, opt.speed, "elastic");
        labels[activeSectorInfo[0]].stop().animate(activeSectorInfo[1], opt.speed, "elastic");
        labels[activeSectorInfo[0]].attr('font-weight','normal');
      }//if
      activeSectorInfo[0] = index;
      activeSectorInfo[1] = {x:txtX,y:txtY};
    });
    angle += angleplus;

  }//pieGroup

  this.render = function(data){
    var l = data.length;
    total=0;
    for(var i = 0; i < l; i++){
      total+=data[i].value;
    }

    for(var i=0; i < l; i++){
      pieGroup(i);
    }//for

    paper.circle(cx,cy, opt.innerRadius)
      .attr({
        'fill': opt.innerCicrleColor,
        'stroke-width': 0
      });
    //set active sector info of first sector
    activeSectorInfo[0] = 0;
    activeSectorInfo[1] = {x:labels[0].attr('x'),y:labels[0].attr('y')};
    fireEvent(sectors[0].node, 'click');
    fireEvent(sectors[0].node, 'mouseover');
    fireEvent(sectors[0].node, 'mouseout');

  }//render method

  this.render(opt.data);
  return this;
};//pieChart

})( jQuery );




