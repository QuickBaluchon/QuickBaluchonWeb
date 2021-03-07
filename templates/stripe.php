<?php
//require_once (WEB_ROOT . 'core/SidebarMenu.php');

$this->_css = ['sidebar'];
$url = explode("/", $_GET['url']);
$id = $url[2];
if(!isset($_SESSION["price$id"]) &&  $_SESSION["price$id"] <= 0)
    header("Location:http://localhost:8888/QuickBaluchonWeb/client/bills");
extract($this->_template);
?>

 <section id="back-content">
   <section id="heroBanner-login">
     <div class="container-xl">
       <div class="row">
         <div class="col-lg">

           <!-- FORM LOGIN-->
           <div class="jumbotron bg-white">
             <h1 class="display-4">Stripe</h1>

             <form id="form" action="../../media/script/stripe/payement.php" method="POST" onsubmit="return false">
                 <div class="form-group row">
                   <div class="col-sm-10">
                     <span><?= $LabelAmount . ' ' . $_SESSION["price$id"] . '€' ?></span>
                   </div>
                 </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <input type="text" name="name" placeholder="<?= $InputName ?>" class="form-control" id="inputName">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <input type="email" name="email" class="form-control" id="IputEmailemail" class="form-control" placeholder="<?= $InputEmail ?>">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <input type="text" placeholder="<?= $InputCard ?>" class="form-control" id="number">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <input type="text" placeholder="<?= $InputMM ?>" class="form-control" id="exp_month">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <input type="text" placeholder="<?= $InputYY ?>" class="form-control" id="exp_year">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <input type="text" placeholder="<?= $InputCVC ?>" class="form-control" id="cvc">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-10">
                   <button type="submit" id="<?= $id ?>" class="btn btn-round btn-primary"><?= $ButtonPay ?></button>
                 </div>
               </div>
             </form>

           </div>

         </div>

         <div class="col-sm">
           <!-- IMAGE -->
           <img class="d-lg-block mx-auto" src="<?=WEB_ROOT?>media/assets/paid.png" alt="delivering" style="display: none; width: inherit">
         </div>
       </div>
     </div>
   </section>
 </section>


<?php unset($_SESSION["price$id"]);?>
<script src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="../../media/script/main.js"></script>
<script type="text/javascript" src="../../media/script/stripe/payement.js"></script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
  });
</script>
