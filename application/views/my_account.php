	<div class="span8 offset2">
      <form class="form-horizontal">
        <fieldset>
          <legend><?=$title?></legend>
          <table class="table table-striped">
          	<tr>
          		<td>Secret Key <i class="icon-question-sign" rel="popover_secret_key" data-content="Secret Key for use in API Requests" data-original-title="Secret Key"></i></td>
          		<td><span id="secret_key"><?=$secret_key?></span> <a id="copy_secret_key">copy</a></td>
          	
          	</tr>
          </table>
         </fieldset>
         <a href="#" class="btn btn-danger popovertest" rel="popover" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title">hover for popover</a>
        </form>
        <script type="text/javascript">
        $(function(){
        		$("i[rel=popover_secret_key]").popover();
	        });
	        </script>	
    </div>