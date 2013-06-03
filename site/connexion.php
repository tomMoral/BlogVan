<?php
include("header.php");
?>
<div class="center">
    <form action="#" method="post" enctype="multipart/form-data" autocomplete="off">

        <div class="box_password">
            <table style="font-size:12px">
                <tr>
                    <td class="droit"><input type=text name="name" class="name" id="name" maxlength="255" required="required" placeholder="Username"/></td>
                </tr>
                <tr>
                    <td class="droit"><input type=text name="email" class="email"maxlength="255" placeholder="Email"/></td>
                </tr>
                <tr>
                    <td class="droit"><input type=password name="password" class="password"maxlength="255" required="required"  placeholder="Password"/></td>
                </tr>
            </table> 
        </div>
    </form>
</div>




<?php
include("footer.php");
?>


