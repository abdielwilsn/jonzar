@extends('layouts.base')
@inject('content', 'App\Http\Controllers\FrontController')
@section('title', 'About Us')


@section('content')
<article class="main-layout__content">
           <!-- Begin page-->
           <!---->
           <section class="page-intro page-intro-company">
             <div class="container container-large"> 
               <h1 class="page-intro__title">RELIABLE INVESTMENT WITH {{$settings->site_name}}<br></h1><br><br>
               <h1 class="page-intro__title"style="color: #69e2b0;">About us</h1>
               <div class="page-intro__description"> 
                 <p><span class="color-primary">GET LIFE INCOME</span></p>
               </div>		
               
           <style>
           #class {
   max-width: 100%;
   font-size: 20px;
   text-align: center;
           }
           #classs {
   max-width: 100%;
   font-size: 30px;
   text-align: center;
           }
           </style>
               <div class="page-intro__text"> 
               <h1 class="page-intro__title" id="class"style="color: #fff;">{{$settings->site_name}}- PROFESSIONAL TEAM<br>CRYPTO INDUSTRY DEVELOPERS</h1>
                 <div class="typography"> 
                 <p> Our company has been developing stable cryptocurrency income for 6 years. Today {{$settings->site_name}} employees are leaders in the field of IT technologies. They have created a unique robot that generates daily profit and guesses the steps of the Bitcoin value. That is why the {{$settings->site_name}}robot is able to benefit at any stage of the market: both growth and decline. This development is unique in that it is the first in the world. Today, not many people know about this. That is why they are given the chance to receive a stable income for the rest of their lives. Many people will try to fake it in fraudulent ways after the platform is known around the world. But we assure that only our company {{$settings->site_name}}is unique. </p>
                 
                 <p><p>  {{$content->getContent('rCTDQh','description')}}</p></p>
                 </div>
               </div>
             </div>
           </section>
           <!---->
           <section class="company">
             <div class="container container-large">
               <div class="company__block"> 
                 <div class="company__row"> 
                   <div class="company__col"> 
                     <div class="certificate"> <img src="temp/custom/assets/images/company/certt.png" alt="">
                     </div>
                     <div class="company-info"> 
                       <table> 
                         <tbody> 
                           <tr> 
                             <td> 
                               <p class="company-info__label">Reg name:
                               </p>
                             </td>
                             <td>{{$settings->site_name}}LTD</td>
                           </tr>
                           <tr> 
                             <td> 
                               <p class="company-info__label">Number:
                               </p>
                             </td>
                             <td><a href="" target="_blank">#13699699</a></td>
                           </tr>
                           <tr> 
                             <td> 
                               <p class="company-info__label">Official address:
                               </p>
                             </td>
                             <td>{{$content->getContent('yN7Q4v','description')}} </td>
                           </tr>
                         </tbody>
                       </table>
                     </div>
                   </div>
                   <div class="company__col"> 
                     <h3 class="company__title">Officially registered <strong>company</strong>
                     </h3>
                     <p class="company__count"><a href="" >#13699699</a>
                     </p>
                     <div class="typography"> 
                       <blockquote>{{$settings->site_name}}- UK LEGAL REGISTRATION <a href="">№13699699</a></blockquote>
                       <p>The unique development of our company has an official legal registration. You can check this information by clicking on the registration number.</p>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </section>
           <!---->
           <section class="text-columns">
             <div class="container">
               <h1 class="page-intro__title" id="classs"style="color: #69e2b0;">{{$settings->site_name}} STATS</h1>
               <div class="typography"> 
                 <p> Here are online statistics of our company. Competent distribution of investments in the {{$settings->site_name}}robot allows you to maximize profits at all stages of the market. Gradual development is a guarantee of world recognition in the market. It is important for us to ensure the protection of our investors, as well as the stability and uninterrupted operation of the platform. The {{$settings->site_name}} robot is fully automated to operate without user intervention.</p>
               </div>
             </div>
           </section>
           <section class="counters">
             <div class="container">
               <div class="counters__slider swiper-container swiper-no-swiping js-swiper-counters">
                 <div class="swiper-wrapper">
                   <div class="swiper-slide">
                     <div class="count-item"> 
                       <div class="count-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-002-pie-chart"></use>
                         </svg>
                       </div>
                       <p class="count-item__value"style="width: 100%;text-align: center;">412                        </p>
                       <div class="count-item__description"style="max-width: 100%;text-align: center;">
                         <p>Days in Work</p>
                       </div>
                     </div>
                   </div>
                   <div class="swiper-slide">
                     <div class="count-item"> 
                       <div class="count-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-003-people"></use>
                         </svg>
                       </div>
                       <p class="count-item__value"style="width: 100%;text-align: center;">8 965                        </p>
                       <div class="count-item__description"style="max-width: 100%;text-align: center;">
                         <p>Total Members</p>
                       </div>
                     </div>
                   </div>
                   <div class="swiper-slide">
                     <div class="count-item"> 
                       <div class="count-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-001-money"></use>
                         </svg>
                       </div>
                       <p class="count-item__value"style="width: 100%;text-align: center;">3 384 989 <sub>USD</sub>
                       </p>
                       <div class="count-item__description"style="max-width: 100%;text-align: center;">
                         <p>Total Invested</p>
                       </div>
                     </div>
                   </div>
                   <div class="swiper-slide">
                     <div class="count-item"> 
                       <div class="count-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-004-withdraw"></use>
                         </svg>
                       </div>
                       <p class="count-item__value"style="width: 100%;text-align: center;">1 168 885<sub>USD</sub>
                       </p>
                       <div class="count-item__description"style="max-width: 100%;text-align: center;">
                         <p>Total Paid</p>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </section>
           <!---->
           <section class="investment">
             <div class="container">
               <div class="investment__row"> 
                 <div class="investment__col"> 
                   <div class="section-intro"> 
                     <h3 class="title">REFERRAL PROGRAM                      </h3>
                     <div class="section-intro__description"> 
                       <p>Anyone can take part in the affiliate program. It allows you to receive generous rewards by inviting new members</p>
                     </div>
                   </div>
                   <ul class="investment-stats"> 
                     <li> 
                       <p class="title">4 LEVELS OF REFERRAL PROGRAM                        </p>
                       <div class="section-intro__description"> 
                         <p>Get extra profit when people in your structure invite new investors to the company</p>
                       </div>
                     </li>
                     <li>
                       <p class="investment-stats__count gradient-text"style="font-size: 75px;">7<sup>%</sup> -  3<sup>%</sup><br>2<sup>%</sup> -  1<sup>%</sup></p>
                     </li>
                   </ul>
                 </div>
                 <div class="investment__col"> 
                   <svg class="svg-icon">
                     <use href="temp/custom/assets/icons/sprite.svg#icon-play"></use>
                   </svg></span></span><img src="temp/custom/assets/images/investment/notebook.png" alt=""></a>
                 </div>
               </div>
               <div class="investment__slider swiper-container swiper-no-swiping js-swiper-advantages">
                 <div class="swiper-wrapper">
                   <div class="swiper-slide">
                     <div class="advantages-item">
                       <div class="advantages-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-002-24-hours"></use>
                         </svg>
                       </div>
                       <div class="advantages-item__description"style="text-align: center;"> 
                         <p>ROBOT TRADING WITHOUT WEEKENDS AND HOLIDAYS</p>
                       </div>
                     </div>
                   </div>
                   <div class="swiper-slide">
                     <div class="advantages-item">
                       <div class="advantages-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-003-transfer"></use>
                         </svg>
                       </div>
                       <div class="advantages-item__description"style="text-align: center;"> 
                         <p>WITHDRAWAL 24/7</p>
                       </div>
                     </div>
                   </div>
                   <div class="swiper-slide">
                     <div class="advantages-item">
                       <div class="advantages-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-001-wallet"></use>
                         </svg>
                       </div>
                       <div class="advantages-item__description"style="text-align: center;"> 
                         <p>BIG NUMBER OF PAYMENT SYSTEMS</p>
                       </div>
                     </div>
                   </div>
                   <div class="swiper-slide">
                     <div class="advantages-item">
                       <div class="advantages-item__icon"style="width: 100%;"> 
                         <svg class="svg-icon">
                           <use href="temp/custom/assets/icons/sprite.svg#icon-004-user"></use>
                         </svg>
                       </div>
                       <div class="advantages-item__description"style="text-align: center;"> 
                         <p>100% ANONYMOUS AND TRANSPARENCY OF THE WORK OF THE ROBOT</p>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </section>

               <div class="payments-and-footer-wrapper">
             <div class="payments-and-footer-wrapper__inner"style="padding-top: 70px;">
                     <section class="payments"style="margin-bottom: 20px;">
                 <div class="container">
                   <div class="payments__row"> 
                     <div class="payments__col"> <img src="temp/custom/assets/images/payments/payment.png" alt="">
                     </div>
                     <div class="payments__col">
                       <div class="payments__content">
                         <div class="section-intro"> 
                           <h3 class="title">PAYMENT SYSTEMS                            </h3>
                           <div class="section-intro__description"> 
                             <p>{{$settings->site_name}}supports a big number of payment systems</p>
                           </div>
                         </div>
                         <div class="typography"> 
                           <p>Our company does not charge commissions for opening a deposit, as well as withdrawing funds from the platform</p>
                         </div>
                         <div class="payments__buttons"> 
              
                     <a class="btn btn--line-primary" href="login"><span>INVEST</span></a>
                                            </div>
                       </div>
                     </div>
                   </div>
                 </div>
               </section>
                 </div>
                 </div>

                    
    
   <div id="button-up">
       <i class="fa fa-chevron-up"></i>	
   </div>    <link rel="stylesheet" href="https:/cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <style>
       #button-up{
display: none;
position: fixed;
right: 20px;
bottom: 60px;
color: #000;
background-color: white;
text-align: center;
font-size: 30px;
padding: 3px 10px 10px 10px;
transition: .3s;
border-radius: 50px;
width: 50px;
height: 50px;
z-index: 9999;
   }
    
   #button-up:hover{
     cursor: pointer;
     background-color: #E8E8E8;
     transition: .3s;
   }
   </style>
       <script>
   $(document).ready(function() { 
     var button = $('#button-up');	
     $(window).scroll (function () {
       if ($(this).scrollTop () > 300) {
         button.fadeIn();
       } else {
         button.fadeOut();
       }
   });	 
   button.on('click', function(){
   $('body,').animate({
   scrollTop: 0
   }, 800);
   return false;
   });		 
   });
   </script><script ></script>


@endsection