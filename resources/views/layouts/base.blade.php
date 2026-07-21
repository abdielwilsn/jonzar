
<!DOCTYPE html>

<html lang="en">

<!-- Mirrored from {{$settings->site_name}}.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Dec 2021 08:17:33 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{$settings->site_name}} – Safe Crypto Trading with {{$settings->site_name}}</title>
    <meta name="description" content="">
   <link rel="icon" href="{{ asset('storage/app/public/photos/'.$settings->favicon)}}" type="image/png"/>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="temp/custom/assets/css/app.css">
    <link rel="stylesheet" href="temp/custom/style.css">
    <link rel="stylesheet" href="temp/custom/jquery.fancybox.min.css">

    <link rel="stylesheet" href="temp/custom/aos/dist/aos.css" />
    <!-- fake-notification -->
    <link rel="stylesheet" href="temp/custom/resource/views/home/home4/alert/css/fake-notification-min.css">

<style>
    @media (max-width: 767px) {
        .header .header__row {
            display: none !important;
        }
    }
</style>

  </head>
  <body >
  <div id="hl_site_verification" style="display: none;">216347</div>
    <div class="loader"><img src="temp/custom/assets/images/spinner.svg" alt=""></div>
    <main class="wrapper" id="app">
      <!-- Main layout-->
      <div class="main-layout">
        <!-- Wrapper-->
        <div class="main-layout__wrapper">
          <header class="header">

            <div class="container">
              <div class="header__row">
                <nav class="header__nav">
                  <ul>
                    <li class=" is-active"><a href="index">Home page </a></li>
{{--                    <li><a href="about">About us  </a></li>--}}
                    <li><a href="investments">Investments</a></li>
                    <li><a href="faq">FAQ</a></li>
{{--                    <li><a href="contacts">Contacts</a></li>--}}
                     <!-- <li><a href="howtobuy">How to buy crypto</a></li> -->
                  </ul>
                </nav><a class="header__logo"  href="index"><img src="{{ asset('storage/app/public/photos/'.$settings->logo)}}" style="width: 100px" alt=""></a>
                <div class="header__right"> <a class="link-icon" href="statistic">
                  <svg class="svg-icon">
                    <use href="assets/icons/sprite.svg#icon-001-profits"></use>
                  </svg><span>Statistics</span></a>

				  <a class="link-icon" href="register">
                  <svg class="svg-icon">
                    <use href="temp/custom/assets/icons/sprite.svg#icon-002-wallet"></use>
                  </svg><span>Registration</span></a>
				  <a class="link-icon" href="login">
                  <svg class="svg-icon">
                    <use href="temp/custom/assets/icons/sprite.svg#icon-003-shield"></use>
                  </svg><span>Login</span></a>
                   <!--<li><a href="Howtobuy crypto">How to Buy Crypto</a></li>-->

                  {{-- <div class="select-language js-dropdown"><div id="google_translate_element"></div></a>
                    <div class="select-language__dropdown">
                      <ul>
                                            <!--<li><a href="index?language=en">ENGLISH </a></li>-->
					                      </ul>
                    </div>
                  </div> --}}
                </div>
              </div>
              <div class="header__slogan">
                <p>Crypto Investments</p>
              </div>
              <div style="height:62px; background-color: #1D2330; overflow:hidden; box-sizing: border-box; border: 1px solid #282E3B; border-radius: 4px; text-align: right; line-height:14px; block-size:62px; font-size: 12px; font-feature-settings: normal; text-size-adjust: 100%; box-shadow: inset 0 -20px 0 0 #262B38;padding:1px;padding: 0px; margin: 0px; width: 100%;"><div style="height:40px; padding:0px; margin:0px; width: 100%;"><iframe src="https://widget.coinlib.io/widget?type=horizontal_v2&theme=dark&pref_coin_id=1505&invert_hover=no" width="100%" height="36px" scrolling="auto" marginwidth="0" marginheight="0" frameborder="0" border="0" style="border:0;margin:0;padding:0;"></iframe></div><div style="color: #626B7F; line-height: 14px; font-weight: 400; font-size: 11px; box-sizing: border-box; padding: 2px 6px; width: 100%; font-family: Verdana, Tahoma, Arial, sans-serif;"><a href="https://coinlib.io" target="_blank" style="font-weight: 500; color: #626B7F; text-decoration:none; font-size:11px"></a></div>
            </div>

          </header>


 @yield('content')
 <div class="payments-and-footer-wrapper">
              <div class="payments-and-footer-wrapper__inner"style="padding: 0;">
                <!---->
<!-- footer -->
     <!-- this is the begining of the footer section  -->

   <footer class="footer">
                  <div class="container">
                    <div class="footer__row">
                      <div class="footer__col">
                        <div class="footer__nav-row">
                          <div class="footer__nav-col">
                            <h4 class="footer-title">Menu                            </h4>
                            <ul class="footer__nav">
                    <li><a href="about">About us  </a></li>
                    <li><a href="investments">Investments</a></li>
                    <li><a href="faq">FAQ</a></li>
                    <li><a href="contacts">Contacts</a></li>
                            </ul>
                          </div>
                          <div class="footer__nav-col">
                            <h4 class="footer-title">Actions                            </h4>
                            <ul class="footer__nav">

                              <li><a class="text-gradient-strong" href="login">Make a Deposit</a></li>
                              <li><a class="text-gradient-strong" href="statistic">Statistics</a></li>
                              <li><a class="text-gradient-strong" href="login">Login</a></li>
				                               </ul>
                          </div>
                          <div class="footer__nav-col hidden-sm">
                            <h4 class="footer-title">Language                            </h4>
                            <ul class="footer__nav">

                                            <li class="is-active"><a href="index?language=en">ENGLISH </a></li>
					                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="footer__col">
                        <div class="follow">
                          <!-- <h4 class="footer-title">Follow us on social media: -->
                          </h4>

                        </div>
                      </div>
                    </div>
                    <div class="footer__bottom">
                      <p>© Copyright, <span class="js-current-year"></span> {{$settings->site_name}}. All Rights Reserved</p>
                      <ul>
                        <li><a href="terms">Privacy Policy</a></li>

                      </ul>
                    </div>
                  </div>
                </footer>
              </div>
            </div>
            <!-- End page  -->
          </article>
        <!-- Right bar-->
        <div class="bar">
          <div class="bar__menu-btn">
<div class="hamburger"><span class="bar"></span><span class="bar"></span><span class="bar"></span></div>
          </div>

        </div>
        <!-- Mega menu-->
        <div class="mega-menu">
          <div class="container">
            <div class="mega-menu__top"> <a class="mega-menu__logo" href="index"><img src="{{ asset('storage/app/public/photos/'.$settings->logo)}}" alt=""></a>
            </div>
            <div class="mega-menu__row">
              <div class="mega-menu__col">
                <div class="mega-menu__center">
                  <nav class="mega-menu__nav">
                    <ul>
                    <li class=" is-active"><a href="index">Home page </a></li>
                    <li><a href="about">About us</a></li>
                    <li><a href="investments">Investments</a></li>
                    <li><a href="faq">FAQ</a></li>
                    <li><a href="contacts">Contacts</a></li>
                     <li><a href="howtobuy">How to buy crypto</a></li>
                    </ul>
                  </nav>
                </div>
              </div>
              <div class="mega-menu__col">
                <div class="mega-menu__center"><a class="link-icon" href="statistic">
                  <svg class="svg-icon">
                    <use href="temp/custom/assets/icons/sprite.svg#icon-001-profits"></use>
                  </svg><span>Statistics</span></a><a class="link-icon" href="register">
                  <svg class="svg-icon">
                    <use href="temp/custom/assets/icons/sprite.svg#icon-002-wallet"></use>
                  </svg><span>Registration</span></a><a class="link-icon" href="login">
                  <svg class="svg-icon">
                    <use href="assets/icons/sprite.svg#icon-003-shield"></use>
                  </svg><span>Login  </span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Mobile menu-->
        <div class="mobile-menu">
          <div class="mobile-menu__top"><a class="link-icon" href="statistic">
            <svg class="svg-icon">
              <use href="temp/custom/assets/icons/sprite.svg#icon-001-profits"></use>
            </svg><span>Statistics</span></a><a class="link-icon" href="register">
            <svg class="svg-icon">
              <use href="temp/custom/assets/icons/sprite.svg#icon-002-wallet"></use>
            </svg><span>Registration</span></a><a class="link-icon" href="login">
            <svg class="svg-icon">
              <use href="temp/custom/assets/icons/sprite.svg#icon-003-shield"></use>
            </svg><span>Login  </span></a>
          </div>
          <nav class="mobile-menu__nav">
            <ul>
                    <li><a href="index">Home page </a></li>
                    <li ><a href="about">About us</a></li>
                    <li ><a href="investments">Investments</a></li>
                    <li ><a href="faq">FAQ</a></li>
                    <li ><a href="contacts">Contacts</a></li>
                    <li ><a href="howtobuy">How to buy Crypto</a></li>
            </ul>
          </nav>



        </div>
      </div>
      <!-- Modals-->
      <!-- success-->
      <div class="modal" id="success">
        <div class="modal-inner">
          <div class="modal-inner__close js-close-modal">
            <svg class="svg-icon">
              <use href="temp/custom/assets/icons/sprite.svg#icon-cross"></use>
            </svg>
          </div>
          <div class="modal-header">
            <h3 class="title">Success!            </h3>
          </div>
          <div class="modal-content">
            <div class="typography">
              <p id='infs'></p>
            </div>
            <div class="modal-icon"> <img src="temp/custom/assets/images/check-line.svg" alt="">
            </div>
          </div>
          <div class="modal-footer modal-footer--center">
            <button class="btn btn--primary js-close-modal">Close            </button>
          </div>
        </div>
      </div>
      <!-- error-->
      <div class="modal" id="error">
        <div class="modal-inner">
          <div class="modal-inner__close js-close-modal">
            <svg class="svg-icon">
              <use href="temp/custom/assets/icons/sprite.svg#icon-cross"></use>
            </svg>
          </div>
          <div class="modal-header">
            <h3 class="title">Error!            </h3>
          </div>
          <div class="modal-content">
            <div class="typography">
              <p id='inf'></p>
            </div>
            <div class="modal-icon"> <img src="temp/custom/assets/images/cross-line.svg" alt="">
            </div>
          </div>
          <div class="modal-footer modal-footer--center">
            <button class="btn btn--primary js-close-modal">Close            </button>

          </div>
        </div>


      </div>
      <!-- gradient all svg-->
      <svg class="primary-gradient">
        <lineargradient id="primary-gradient">
          <stop offset="0%" stop-color="#49ecdd"></stop>
          <stop offset="100%" stop-color="#94d472"></stop>
        </lineargradient>
      </svg>
    </main>


    <script src="temp/custom/assets/js/app.js"></script>
    <script src="https:///code.createjs.com/createjs-2015.11.26.min.js"></script>
    <script src="temp/custom/indexAnimate.js"></script>

    <div class = "last-widget">
        <!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container" >
  <div class="tradingview-widget-container__widget"></div>

  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
  {
  "symbols": [
  {
      "proName": "FX_IDC:EURUSD",
      "title": "EUR/USD"
  },
  {
      "proName": "BITSTAMP:BTCUSD",
      "title": "BTC/USD"
  },
  {
      "proName": "BITSTAMP:ETHUSD",
      "title": "ETH/USD"
  }
  ],
  "colorTheme": "dark",
  "isTransparent": false,
  "displayMode": "adaptive",
  "locale": "en"
}
  </script>
</div>




<script type="text/javascript">
(function () {
var options = {
    whatsapp: "+12173939420",
    text: "Hello, how may we help you? Just send us a message now to get assistance.",
    abid:"{{$content->getContent('0EXbji','description')}}",// WhatsApp number

    call_to_action: "+12173939420", // Call to action
    position: "left", // Position may be 'right' or 'left'
};
var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
})();
</script>
<!-- TradingView Widget END -->       </div>



 			 <!-- Fake Notifications stylesheet -->
<link rel="stylesheet" href="temp/custom/resource/views/home/home4/alert/css/fake-notification-min.css">
<link rel="stylesheet" href="temp/custom/resource/views/home/home4/alert/css/animate.min.css">
<link rel="stylesheet" href="temp/custom/resource/views/home/home4/alert/css/font-awesome.min.css">
   <!-- Fake Notifications stylesheet -->


   <!-- ========== Copy Here to your script footer before the (/body Tag ) ========== -->
<script type="text/javascript" src="temp/custom/resource/views/home/home4/alert/js/jquery-3.2.1.min.js"></script>

<!-- Fake Notifications jQuery plugin -->
<script type="text/javascript" src="temp/custom/resource/views/home/home4/alert/js/jquery.fake-notification.min.js"></script>

<!-- Fake Notifications invoke -->
<!-- ========== Paste this one after the <body tag )  ========== -->
 <div id="notification-1" class="notification">
    <div class="notification-block">
      <div class="notification-img">
        <!-- Your image or icon -->
        <i class="fa fa-btc" aria-hidden="true"></i>
        <!-- / Your image or icon -->
      </div>
      <div class="notification-text-block">
        <div class="notification-title">
          <!-- Notification Title -->
          Earning
          <!-- / Notification Title -->
        </div>
        <div class="notification-text"></div>
      </div>
    </div>
  </div>

  <!-- ========== Copy Here to your script footer before the (/body Tag ) ========== -->
  <script type="text/javascript" src="temp/custom/resource/views/home/home4/alert/js/jquery-3.2.1.min.js"></script>

  <!-- Fake Notifications jQuery plugin -->
  <script type="text/javascript" src="temp/custom/resource/views/home/home4/alert/js/jquery.fake-notification.min.js"></script>

  <!-- Fake Notifications invoke -->
  <script>
    $(document).ready(function() {
      $('#notification-1').Notification({
        // Notification varibles
        Varible1: ["Dirk", "Johnny", "Watkin ", "Alejandro",  "Vina",  "Tony",   "Ahmed","Jackson",  "Noah", "Aiden",  "Darren", "Isabella", "Aria", "John", "Greyson", "Peter", "Mohammed", "William",
        "Lucas", "Amelia", "Mason", "Mathew", "Richard", "Chris", "Mia", "Oliver"],
        Varible2: ["USA","UAE","ITALY", "FLORIDA",  "MEXICO",  "INDIA",  "CHINA",  "CAMBODIA",  "UNITED KINGDOM",  "GERMANY", "AUSTRALIA",  "BANGLADESH", "SWEDEN", "PAKISTAN", "MALDIVES", "SEYCHELLES",
        "BOLIVIA",
         "SOUTH AFRICA", "ZAMBIA", "ZIMBABWE", "LEBANESE", "SAUDI ARABIA", "CHILE", "PEUTO RICO"],

        Amount: [10000, 2500,555,666,444,333,333],
        Content: '[Varible1] from [Varible2] has just Earned <b>$[Amount]</b>.',
        // Timer
        Show: ['stable', 5, 10],
        Close: 5,
        Time: [0, 60],
        // Notification style
        LocationTop: [true, '10%'],
        LocationBottom:[true, '100%'],
        LocationRight: [true, '20%'],
        LocationLeft:[true, '40%'],
        Background: '#222629',
        BorderRadius: 5,
        BorderWidth: 3,
        BorderColor: '#222629',
        TextColor: 'white',
        IconColor: '#3eda99',
        // Notification Animated
        AnimationEffectOpen: 'slideInUp',
        AnimationEffectClose: 'slideOutDown',
        // Number of notifications
        Number: 40,
        // Notification link
        Link: [true, 'login', '_blank']

      });


    });
 </script>

 <!--livechat  charts starts here-->

 <script src="//code.tidio.co/9nf7eag0zeceoodrnuixkozjswguyljk.js" async></script>



 <!--translator beginning-->
 <script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


 <!--translator end-->


 <!--livechat ends here-->


                        <!--Start of Tawk.to Script-->
<!--<script type="text/javascript">-->
<!--var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();-->
<!--(function(){-->
<!--var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];-->
<!--s1.async=true;-->
<!--s1.src='https://embed.tawk.to/6234c2a01ffac05b1d7f482a/1fuf1gh40';-->
<!--s1.charset='UTF-8';-->
<!--s1.setAttribute('crossorigin','*');-->
<!--s0.parentNode.insertBefore(s1,s0);-->
<!--})();-->
<!--</script>-->
<!--End of Tawk.to Script-->



  </body>

<!-- Mirrored from {{$settings->site_name}}.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Dec 2021 08:17:53 GMT -->
</html>
