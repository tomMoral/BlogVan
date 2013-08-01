<canvas id="guitariste" ></canvas>

<script>
  var canvas = document.getElementById("guitariste");
  var size_h = 917,
      size_w = 685;
  var ctx = canvas.getContext("2d");
  
  var scale = canvas.height / size_h;
  scale = Math.min(scale, canvas.width / size_w);
  ctx.scale(scale, scale);  
  function im(fname){
    var element = document.createElement('img');
    element.src = "guitar_guy/"+fname+".png";
    return element;
  }
  
  var body = im("guitar guy"),
    body_rest = im("body");
  var arm = im("arm_move"),
    arm2 = im("arm2");
  var guit = im("guitar"),
    mouth = im("mouth"),
    sad_mouth = im("sad_mouth");
  var cuisse = im("cuisse"),
    foot = im("foot"),
    leg = im("leg");

  var pi = Math.PI;
  var playing = <?php echo isset($_SESSION['is_playing']) ? $_SESSION['is_playing'] : 0;?>;

  function rad(d){
    return d*pi/180;
  }
  var angle = rad(0);
  var da = rad(5);

  var scale = 1;
  var ds = 0.1;

  var pos_x = 64, pos_y = 388;

  function draw_play(){
    ctx.clearRect(0, 0, 1000, 1000);
    ctx.drawImage(body, 0, 0);
    ctx.translate(x_mouth, y_mouth);
    m_height = 50*scale;
    m_pos = -25*scale;
    ctx.drawImage(mouth, -25, m_pos, 50, m_height);
    ctx.scale(1,1);
    ctx.translate(pos_x-x_mouth, pos_y-y_mouth);
    angle += da;
    angle %= 2*pi;
    ctx.rotate(angle);
    ctx.drawImage(arm, -20, -17);
    ctx.rotate(-angle);
    ctx.translate(-pos_x, -pos_y);
    if(angle < pi/6 ){
      da = rad(5);
      p = Math.random();
      if (p > 0.9*(pi-6*angle)/pi)
        da = -da;
    }
    else
      da = rad(25);
    if(scale <= 0.5)
      ds = -ds;
    else if(scale >= 1.3)
      ds = -ds;
    else{
      p = Math.random()
      if(p > 0.7*scale)
        ds = -ds;
    }
    scale -= ds;
    if(playing)
      setTimeout(draw_play,50);
    else
      draw_stop();
  }

  var x_a2 = 196, y_a2=271;
  var x_guit = 183, y_guit=24;
  var x_mouth = 135, y_mouth = 230;

  function  draw_stop(){
    ctx.clearRect(0,0,1000,1000);
    ctx.drawImage(body_rest,0,0);
    ctx.translate(x_mouth, y_mouth);
    ctx.drawImage(sad_mouth, -62 ,-57, 80,80);
    ctx.translate(pos_x-x_mouth, pos_y-y_mouth);
    ctx.rotate(rad(90));
    ctx.drawImage(arm, -20, -17);
    ctx.rotate(rad(-90));
    ctx.translate(-pos_x + x_a2, -pos_y + y_a2);
    ctx.rotate(rad(70));
    ctx.drawImage(arm2, -10,-18);
    ctx.translate(x_guit, y_guit);
    ctx.rotate(rad(-70));
    ctx.drawImage(guit, -88, -195);
    ctx.rotate(rad(70));
    ctx.translate(-x_guit, -y_guit);
    ctx.rotate(rad(-70));
    ctx.translate(-x_a2, -y_a2);
  }

  document.getElementById("manage_music").onclick = function
    if(playing){
      playing=false;
    }
    else{
      playing=true;
      draw_play();
    }
    
  }

  if(playing)
    draw_play();
  else{
    draw_stop();
    draw_stop();
  }
</script>

