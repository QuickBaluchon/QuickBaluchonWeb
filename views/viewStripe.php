<?php
    $url = explode("/", $_GET['url']);
    $id = $url[2];
    if(!isset($_SESSION["price$id"]) &&  $_SESSION["price$id"] <= 0)
        header("Location:http://localhost:8888/QuickBaluchonWeb/client/bills");
?>

<main>

  <div class="wrapper">

    <!-- Sidebar -->
       <nav id="sidebar">

         <!-- Sidebar Header-->
         <div class="sidebar-header h5 text-white">
           <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 523.97 537.64" fill="white">
             <defs>
               <style>.cls-1{fill:none;}.cls-1,.cls-2{stroke:white;stroke-miterlimit:10;stroke-width:24px;}</style>
             </defs>
             <g id="Calque_2" data-name="Calque 2">
               <g id="Calque_2-2" data-name="Calque 2">
                 <path class="cls-1" d="M477.86,147v0a144.14,144.14,0,0,0-21.72-41.65c-22.52-29.8-56-48.68-93.3-48.68-41.3,0-77.84,23.11-100.09,58.53-22.18-36.09-59.06-59.71-100.82-59.71-41.07,0-77.43,22.84-99.72,57.93a1.86,1.86,0,0,0-.1.2,139.57,139.57,0,0,0-10.92,20.83s0,0,0,0A292.15,292.15,0,0,0,23.72,259c0,118.4,68.61,218.76,163.52,253.45h0c2.7,1,5.41,2,8.15,2.94h0c5.92,1.84,11.95,3.43,18,4.78l.86.19,1.15.25c1,.23,2,.44,3,.64,1.29.26,2.59.51,3.88.74,1.79.32,3.57.63,5.37.9l.21,0,3,.45,2.38.32h0a227.83,227.83,0,0,0,115.94-15.15l.11-.05c88.24-38.71,150.76-135.3,150.76-248.32A293.48,293.48,0,0,0,477.86,147Z"/>
                 <path class="cls-2" d="M512,195.3v53.07c0,119.35-68,221.09-163.36,260.16,88.24-38.71,150.76-135.3,150.76-248.32A293.48,293.48,0,0,0,477,147v0a144.14,144.14,0,0,0-21.72-41.65c-22.52-29.8-56-48.68-93.3-48.68-41.3,0-77.84,23.11-100.09,58.53-22.18-36.09-59.06-59.71-100.82-59.71-41.07,0-77.43,22.84-99.72,57.93a1.86,1.86,0,0,0-.1.2,139.57,139.57,0,0,0-10.92,20.83s0,0,0,0A292.15,292.15,0,0,0,22.85,259c0,118.4,68.61,218.76,163.52,253.45h0c2.7,1,5.41,2,8.15,2.94C89.24,482.78,12,375.58,12,248.37V195.3C12,94.36,85.58,12.47,176.48,12H348.37C438.87,13,512,94.68,512,195.3Z"/>
                 <g id="face">
                   <g id="eyes">
                     <ellipse cx="154.44" cy="227.7" rx="43.84" ry="45.69"/>
                     <ellipse cx="369.53" cy="227.71" rx="43.84" ry="45.69"/>
                   </g>
                   <polygon id="nose" points="261.98 481.68 307.38 358.06 331.64 205.3 261.98 344.45 261.99 344.45 192.32 205.3 216.58 358.06 261.99 481.68 261.98 481.68"/>
                 </g>
               </g>
             </g>
           </svg>
           <span>QuickBaluchon</span>
         </div>

         <!-- Menu-->
         <ul class="list-unstyled components">
           <li>
             <a href="#">
               <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <circle cx="27.5" cy="27.5" r="27.5" fill="#81B29A"/>
                 <path d="M28.0001 27.6938C31.1132 27.6938 33.6372 24.6284 33.6372 20.847C33.6372 17.0654 32.8085 14 28.0001 14C23.1917 14 22.3629 17.0654 22.3629 20.847C22.3629 24.6284 24.8868 27.6938 28.0001 27.6938Z" fill="#EDF1F3"/>
                 <path d="M17.3528 38.1483C17.3518 37.9177 17.3509 38.0833 17.3528 38.1483V38.1483Z" fill="#EDF1F3"/>
                 <path d="M38.6469 38.3283C38.6499 38.2652 38.6479 37.8904 38.6469 38.3283V38.3283Z" fill="#EDF1F3"/>
                 <path d="M38.6348 37.8718C38.5304 31.2844 37.6701 29.4074 31.0867 28.2192C31.0867 28.2192 30.1599 29.4001 27.9999 29.4001C25.8399 29.4001 24.9131 28.2192 24.9131 28.2192C18.4015 29.3944 17.4888 31.2436 17.3688 37.6578C17.359 38.1816 17.3544 38.2091 17.3527 38.1483C17.3531 38.2622 17.3535 38.4729 17.3535 38.8404C17.3535 38.8404 18.9209 42 27.9999 42C37.0788 42 38.6463 38.8404 38.6463 38.8404C38.6463 38.6043 38.6465 38.4401 38.6467 38.3284C38.645 38.366 38.6415 38.2932 38.6348 37.8718Z" fill="#EDF1F3"/>
               </svg>
               <span>Données personnelles</span>
             </a>
           </li>

           <li>
             <a href="#">
               <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <circle cx="27.5" cy="27.5" r="27.5" fill="#81B29A"/>
                 <g clip-path="url(#clip0)">
                  <path d="M37.9605 14.7194C37.5348 14.5233 37.0333 14.5996 36.6851 14.9133L34.8482 16.5398C34.7982 16.5843 34.7228 16.584 34.6731 16.5393L32.1737 14.3038C31.7216 13.8987 31.0373 13.8987 30.5852 14.3038L28.0881 16.5372C28.0378 16.5821 27.9619 16.5821 27.9117 16.5372L25.4143 14.3038C24.9622 13.8988 24.2779 13.8988 23.8258 14.3038L21.3261 16.5393C21.276 16.584 21.2004 16.5842 21.1501 16.5397L19.3115 14.9133C18.8237 14.4795 18.0766 14.5233 17.6428 15.011C17.4483 15.2298 17.3419 15.5129 17.3442 15.8056V40.1945C17.3398 40.8482 17.8662 41.3818 18.52 41.3861C18.8129 41.3881 19.096 41.2814 19.3148 41.0868L21.1518 39.4603C21.2018 39.4158 21.2771 39.4161 21.3268 39.4608L23.8263 41.6963C24.2783 42.1013 24.9627 42.1013 25.4147 41.6963L27.9121 39.4629C27.9624 39.418 28.0383 39.418 28.0885 39.4629L30.586 41.6962C31.0381 42.1011 31.7224 42.1011 32.1744 41.6962L34.6743 39.4607C34.7243 39.416 34.7999 39.4158 34.8502 39.4603L36.6888 41.0867C37.1768 41.5203 37.9239 41.4762 38.3574 40.9882C38.5516 40.7697 38.6579 40.4869 38.6558 40.1945V15.8056C38.662 15.3371 38.3885 14.9098 37.9605 14.7194ZM21.7261 23.4994H28.3172C28.6096 23.4994 28.8467 23.7365 28.8467 24.0289C28.8467 24.3213 28.6096 24.5584 28.3172 24.5584H21.7261C21.4337 24.5584 21.1966 24.3213 21.1966 24.0289C21.1966 23.7365 21.4337 23.4994 21.7261 23.4994ZM34.2739 32.5006H21.7261C21.4337 32.5006 21.1966 32.2636 21.1966 31.9711C21.1966 31.6787 21.4337 31.4417 21.7261 31.4417H34.2739C34.5663 31.4417 34.8034 31.6787 34.8034 31.9711C34.8034 32.2636 34.5663 32.5006 34.2739 32.5006ZM34.2739 28.5295H21.7261C21.4337 28.5295 21.1966 28.2924 21.1966 28C21.1966 27.7076 21.4337 27.4706 21.7261 27.4706H34.2739C34.5663 27.4706 34.8034 27.7076 34.8034 28C34.8034 28.2924 34.5663 28.5295 34.2739 28.5295Z" fill="#EDF1F3"/>
                </g>
                <defs>
                  <clipPath id="clip0"><rect width="28" height="28" fill="white" transform="translate(14 14)"/></clipPath>
                </defs>
               </svg>
               <span>Factures</span>
             </a>
           </li>

           <li>
             <a href="#">
               <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <circle cx="27.5" cy="27.5" r="27.5" fill="#81B29A"/>
                  <path d="M18.8243 17.9502C18.8236 17.9502 18.8229 17.9502 18.8222 17.9502C18.5858 17.9502 18.3634 18.0424 18.1954 18.2099C18.0256 18.3792 17.9321 18.6047 17.9321 18.8448V32.2594C17.9321 32.7513 18.3339 33.1525 18.8278 33.1538C20.9102 33.1587 24.3989 33.5928 26.8057 36.1114V22.0726C26.8057 21.9058 26.7631 21.7492 26.6827 21.6196C24.7073 18.4384 20.9114 17.9551 18.8243 17.9502Z" fill="#EDF1F3"/>
                  <path d="M37.068 32.2595V18.8448C37.068 18.6047 36.9745 18.3792 36.8047 18.2099C36.6368 18.0424 36.4142 17.9502 36.178 17.9502C36.1773 17.9502 36.1765 17.9502 36.1758 17.9502C34.0889 17.9552 30.2929 18.4385 28.3175 21.6197C28.2371 21.7493 28.1946 21.9059 28.1946 22.0727V36.1114C30.6013 33.5928 34.0901 33.1587 36.1724 33.1538C36.6662 33.1525 37.068 32.7513 37.068 32.2595Z" fill="#EDF1F3"/>
                  <path d="M39.1056 21.0439H38.4569V32.2596C38.4569 33.5155 37.4336 34.5397 36.1757 34.5428C34.4094 34.547 31.4971 34.8924 29.4346 36.8445C33.0018 35.9711 36.7622 36.5389 38.9053 37.0272C39.1729 37.0882 39.4494 37.0252 39.6638 36.8544C39.8775 36.6839 40 36.4291 40 36.1556V21.9384C40.0001 21.4452 39.5987 21.0439 39.1056 21.0439Z" fill="#EDF1F3"/>
                  <path d="M16.5431 32.2596V21.0439H15.8944C15.4013 21.0439 15 21.4452 15 21.9384V36.1554C15 36.4289 15.1226 36.6836 15.3362 36.8541C15.5504 37.0249 15.8267 37.0881 16.0947 37.027C18.2378 36.5385 21.9983 35.9708 25.5654 36.8443C23.5029 34.8922 20.5906 34.5469 18.8243 34.5427C17.5665 34.5397 16.5431 33.5155 16.5431 32.2596Z" fill="#EDF1F3"/>
               </svg>
               <span>Historique</span>
             </a>
           </li>
         </ul>
       </nav>

       <!-- Content -->
       <div id="content">

         <!-- Header -->
           <div class="container-fluid">
             <nav class="navbar navbar-expand-lg navbar-light bg-white">

                 <!-- Links -->
                 <div class="collapse navbar-collapse" id="navbarNav">
                   <ul class="navbar-nav">

                     <!-- Dropdown Language-->
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Langue</a>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="#">Fr</a>
                          <a class="dropdown-item" href="#">En</a>
                          <a class="dropdown-item" href="#">Ru</a>
                        </div>
                      </li>
                   </ul>
                 </div>

                 <a href="#" class="h5">Amazon.fr</a>

             </nav>
           </div>


         <!-- Content -->
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
                             <span>vous devez régler:</span>
                             <input type="text" name="price" value="<?php if(isset($_SESSION["price$id"])) echo $_SESSION["price$id"] ?>" class="form-control" id="price">
                           </div>
                         </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <input type="text" name="name" placeholder="votre nom" class="form-control" id="inputName">
                         </div>
                       </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <input type="email" name="email" class="form-control" id="IputEmailemail" class="form-control" placeholder="email">
                         </div>
                       </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <input type="text" placeholder="your card number" class="form-control" id="number">
                         </div>
                       </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <input type="text" placeholder="MM" class="form-control" id="exp_month">
                         </div>
                       </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <input type="text" placeholder="YY" class="form-control" id="exp_year">
                         </div>
                       </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <input type="text" placeholder="CVC" class="form-control" id="cvc">
                         </div>
                       </div>
                       <div class="form-group row">
                         <div class="col-sm-10">
                           <button type="submit" id="<?= $id ?>" class="btn btn-round btn-primary">Se connecter</button>
                         </div>
                       </div>
                     </form>

                   </div>

                 </div>

                 <div class="col-sm">
                   <!-- IMAGE -->
                   <img class="d-lg-block mx-auto" src="../../assets/paid.png" alt="delivering">
                 </div>
               </div>
             </div>
           </section>
         </section>




       </div>

  </div>

</main>
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
