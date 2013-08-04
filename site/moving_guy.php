<script>
//event play and pause are triggered when needed
//you just have to change repetition and draw stop
    var canvas = document.getElementById("guitariste");
    var size_h = 917,
            size_w = 685;
    var ctx = canvas.getContext("2d");
    canvas.height = 260;
    var scale = canvas.height / size_h;
    canvas.width = 200;
    scale = Math.min(scale, canvas.width / size_w);

    ctx.scale(scale, scale);
    function im(fname) {
        var element = document.createElement('img');
        element.src = "guitar_guy/" + fname + ".png";
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

    function rad(d) {
        return d * pi / 180;
    }
    var angle = rad(0);
    var da = rad(5);

    var scale = 1;
    var ds = 1;

    var pos_x = 64, pos_y = 388;

    var guitarist_moving = false;
    ctx.translate(0, 1200);
    ctx.translate(80, 0);
    var trY = 0;

    function repetition() {
        if (guitarist_moving) {
            ctx.clearRect(-100, -100, 1100, 1100);
            if (trY > -1200) {
                ctx.translate(0, -10);
                trY -= 10;
            }
            ctx.drawImage(body, 0, 0);
            ctx.translate(x_mouth, y_mouth);
            m_height = 50 * scale;
            m_pos = -25 * scale;
            ctx.drawImage(mouth, -25, m_pos, 50, m_height);
            ctx.scale(1, 1);
            ctx.translate(pos_x - x_mouth, pos_y - y_mouth);
            angle += da;
            angle %= 2 * pi;
            ctx.rotate(angle);
            ctx.drawImage(arm, -20, -17);
            ctx.rotate(-angle);
            ctx.translate(-pos_x, -pos_y);
            if (angle < pi / 6) {
                da = rad(10);
                p = Math.random();
                if (p > 0.9 * (pi - 6 * angle) / pi)
                    da = -da;
            }
            else
                da = rad(25);
            if (scale <= 0.5)
                ds = -ds;
            else if (scale >= 1.3)
                ds = -ds;
            else {
                p = Math.random();
                if (p > 0.7 * scale)
                    ds = -ds;
            }
            scale -= ds;
            setTimeout(repetition, 50);
        }
    }
    function draw_play() {
        guitarist_moving = true;
        repetition();
    }

    var x_a2 = 196, y_a2 = 271;
    var x_guit = 183, y_guit = 24;
    var x_mouth = 135, y_mouth = 230;

    function  draw_stop() {
        guitarist_moving = false;
        repetition_stop();
    }

    function repetition_stop() {
        if (!guitarist_moving) {
            if (trY < 0) {
                ctx.translate(0, 10);
                trY += 10;
            }
            ctx.clearRect(-100, -100, 1100, 1100);
            ctx.drawImage(body_rest, 0, 0);
            ctx.translate(x_mouth, y_mouth);
            ctx.drawImage(sad_mouth, -62, -57, 80, 80);
            ctx.translate(pos_x - x_mouth, pos_y - y_mouth);
            ctx.rotate(rad(90));
            ctx.drawImage(arm, -20, -17);
            ctx.rotate(rad(-90));
            ctx.translate(-pos_x + x_a2, -pos_y + y_a2);
            ctx.rotate(rad(70));
            ctx.drawImage(arm2, -10, -18);
            ctx.translate(x_guit, y_guit);
            ctx.rotate(rad(-70));
            ctx.drawImage(guit, -88, -195);
            ctx.rotate(rad(70));
            ctx.translate(-x_guit, -y_guit);
            ctx.rotate(rad(-70));
            ctx.translate(-x_a2, -y_a2);
        setTimeout(repetition_stop, 50);
        }
    }
</script>