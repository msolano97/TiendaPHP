<?php


?>
<div class="container">    
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                    <div class="panel-title">Cambiar Password</div>
            </div>     

            <div style="padding-top:30px" class="panel-body" >

                <form id="loginform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

                    <input type="hidden" id="user_id" name="user_id" value ="<?php echo $user_id; ?>" />

                    <input type="hidden" id="token" name="token" value ="<?php echo $token; ?>" />
                    
                    <div class="form-group">
                        <label for="passwordVieja" class="col-md-3 control-label">Password</label>
                        <div class="col-md-9">
                                <input type="password" class="form-control" name="passwordVieja" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                            <label for="password" class="col-md-3 control-label">Nueva Password</label>
                            <div class="col-md-9">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                    </div>

                    <div class="form-group">
                        <label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
                        </div>
                    </div>

                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-12 controls">
                            <button id="btn-login" type="submit" class="btn btn-success">Cambiar Password</a>
                        </div>
                    </div>   
                </form>
            </div>                     
        </div>  
    </div>
</div>

