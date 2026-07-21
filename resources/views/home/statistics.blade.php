@extends('layouts.base')

@section('title', 'statistics')


@inject('content', 'App\Http\Controllers\FrontController')
@section('content')
<article class="main-layout__content">
            <!-- Begin page-->
            <section class="page-intro page-intro-statistic">
              <div class="container container-large">
                <div class="page-intro__row">
                  <div class="page-intro__content">
                    <h1 class="page-intro__title">Our clients are already making money                    </h1>
                    <div class="page-intro__description">
                  <p><span class="color-primary">GET LIFE INCOME</span></p>
                    </div>
                  </div>
                  <div class="page-intro__button"> <a class="btn btn--primary" href="login">Get Started</a>
                  </div>
                </div>
              </div>
            </section>
            <!---->
            <section class="statisticts-section">

              <div class="container container-large">

                <div class="counters__slider swiper-container swiper-no-swiping js-swiper-counters"style="margin-bottom: 40px;">
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

{{--                <div class="statisticts-section__row"> --}}
{{--                  <div class="statisticts-section__col">--}}
{{--                    <div class="statisticts-block">--}}
{{--                      <h3 class="title">Last Deposits                      </h3>--}}
{{--                      <table> --}}
{{--                        <tbody> --}}
{{--                                                					  --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Vagner                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/svg/trx.svg" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">860000 trx</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Austin132002                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/svg/doge.svg" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">500000 doge</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Qinny                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/svg/usdttrc20.svg" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">1800 usdt</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">rannovan                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/svg/ltc.svg" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">520 ltc</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Parimpe                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">570 usd</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">ahmadm1990                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/svg/trx.svg" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">103000.68 trx</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Drann                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">2700 usd</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{--	--}}
{{--                        </tbody>--}}
{{--                      </table>--}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                  <div class="statisticts-section__col">--}}
{{--                    <div class="statisticts-block statisticts-block--gradient">--}}
{{--                      <h3 class="title">Last Payments                      </h3>--}}
{{--                      <table> --}}
{{--                        <tbody> --}}
{{--                         --}}
{{--                                                  					  --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Serg337                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">3.6 usd</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">016timur                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">450.36 usd</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Temirkhan                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/svg/usdttrc20.svg" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">1349.45 usdt</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Aom2525                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">230.26 usdt</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Tik188                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">1890.17 usdt</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">Qinny                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">650 usdt</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{-- --}}
{{--                          <tr> --}}
{{--                            <td> --}}
{{--                              <div class="username">subhan911                              </div>--}}
{{--                            </td>                            <td style="text-align: center;"><img src="temp/custom/img/ps/pmusd.png" style="height: 35px;"alt=""></td>--}}
{{--							                            <td>--}}
{{--                              <div class="price-gradient-primary">1890.26 usdt</div>--}}
{{--                            </td>--}}
{{--                          </tr>--}}
{{--	--}}
{{--                        </tbody>--}}
{{--                      </table>--}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
              </div>
            </section>

       <div id="button-up">
        <i class="fa fa-chevron-up"></i>
    </div>    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    $('body, html').animate({
    scrollTop: 0
    }, 800);
    return false;
    });
    });
    </script><script src="" async></script>

  @endsection
