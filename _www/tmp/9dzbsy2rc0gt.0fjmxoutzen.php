<div class="navbar-form search disinl" id="search" role="form">

    <div class="input-group">
        <input name="search" type="text" class="form-control" id="input-search" placeholder="<?php echo (isset($filtervalue) && strlen($filtervalue)>0 ? $filtervalue : $search_header); ?>" />
        <span class="input-group-btn btn-search">
            <span class="bouton btn btn-success input-group-addon has-tip-up" id="search_btn" type="button" title="<?php echo $reset_search[$l]; ?>">
                <i class="glyphicon glyphicon-search"></i>
            </span>
        </span>
        <i id="search_reset_btn" class="glyphicon glyphicon-remove-circle has-tip-up curpoi <?php echo (isset($filtervalue) && strlen($filtervalue)>0 ? '' : 'vishid'); ?>" title="<?php echo $reset_search[$l]; ?>"></i>
        <span class="spinner" id="search-loading"></span>
    </div>

    <script type="text/javascript">
        function processSearch(){
            $("#search-loading").css({'visibility':'visible'});  
            var search_val = $('input[name="search"]').val()
            if( search_val.length > 0 ) {
                var url = "<?php echo $search_pat; ?>"
                url = url.replace('___', search_val)
                location.href=url
            } else {
                var url = "<?php echo $no_search_pat; ?>"
                location.href=url
            }
        }

        window.onload = function (){
            $('#input-search').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    processSearch();   
                }
            });
            $('#search_btn').click(function(){
                processSearch();
            });
            $('#search_reset_btn').click(function(){
                $("#search-loading").css({'visibility':'visible'}); 
                var url = "<?php echo $no_search_pat; ?>"
                location.href=url
            });
        }
    </script>
</div>