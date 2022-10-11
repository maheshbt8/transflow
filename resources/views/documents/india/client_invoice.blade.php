<!DOCTYPE html>
<!-- Created by pdf2htmlEX (https://github.com/pdf2htmlEX/pdf2htmlEX) -->
<html>
    <head>
        <meta charset="utf-8" />
        <style type="text/css">
            /*! 
 * Base CSS for pdf2htmlEX
 * Copyright 2012,2013 Lu Wang <coolwanglu@gmail.com> 
 * https://github.com/pdf2htmlEX/pdf2htmlEX/blob/master/share/LICENSE
 */
            #sidebar {
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                width: 250px;
                padding: 0;
                margin: 0;
                overflow: auto;
            }
            #page-container {
                position: absolute;
                top: 0;
                left: 0;
                margin: 0;
                padding: 0;
                border: 0;
            }
            @media screen {
                #sidebar.opened + #page-container {
                    left: 250px;
                }
                #page-container {
                    bottom: 0;
                    right: 0;
                    overflow: auto;
                }
                .loading-indicator {
                    display: none;
                }
                .loading-indicator.active {
                    display: block;
                    position: absolute;
                    width: 64px;
                    height: 64px;
                    top: 50%;
                    left: 50%;
                    margin-top: -32px;
                    margin-left: -32px;
                }
                .loading-indicator img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    right: 0;
                }
            }
            .pf {
                position: relative;
                background-color: white;
                overflow: hidden;
                margin: 0;
                border: 0;
            }
            .pc {
                position: absolute;
                border: 0;
                padding: 0;
                margin: 0;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                display: block;
                transform-origin: 0 0;
                -ms-transform-origin: 0 0;
                -webkit-transform-origin: 0 0;
            }
            .pc.opened {
                display: block;
            }
            .bf {
                position: absolute;
                border: 0;
                margin: 0;
                top: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                -ms-user-select: none;
                -moz-user-select: none;
                -webkit-user-select: none;
                user-select: none;
            }
            .bi {
                position: absolute;
                border: 0;
                margin: 0;
                -ms-user-select: none;
                -moz-user-select: none;
                -webkit-user-select: none;
                user-select: none;
            }
            .c {
                position: absolute;
                border: 0;
                padding: 0;
                margin: 0;
                overflow: hidden;
                display: block;
            }
            .t {
                position: absolute;
                white-space: pre;
                font-size: 1px;
                transform-origin: 0 100%;
                -ms-transform-origin: 0 100%;
                -webkit-transform-origin: 0 100%;
                unicode-bidi: bidi-override;
                -moz-font-feature-settings: "liga" 0;
            }
            .t:after {
                content: "";
            }
            .t:before {
                content: "";
                display: inline-block;
            }
            .t span {
                position: relative;
                unicode-bidi: bidi-override;
            }
            ._ {
                display: inline-block;
                color: transparent;
                z-index: -1;
            }
            ::selection {
                background: rgba(127, 255, 255, 0.4);
            }
            ::-moz-selection {
                background: rgba(127, 255, 255, 0.4);
            }
            .pi {
                display: none;
            }
            .d {
                position: absolute;
                transform-origin: 0 100%;
                -ms-transform-origin: 0 100%;
                -webkit-transform-origin: 0 100%;
            }
            .it {
                border: 0;
                background-color: rgba(255, 255, 255, 0);
            }
            .ir:hover {
                cursor: pointer;
            }
        </style>
     
        
        <title></title>
    </head>
    <body>
        <div id="sidebar">
            <div id="outline"></div>
        </div>
        <div id="page-container">
            <div id="pf1" class="pf w0 h0" data-page-no="1">
                <div class="pc pc1 w0 h0">
                   <!--  <img
                        class="bi x0 y0 w1 h1"
                        alt=""
                        src="data:image/png;base64,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ZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAAAA2RIAAADZEgAAANkSAAAA2RIAAABkSwAAAGRLAAAAZEsAAABkSwAAAJAtAQAAkC0BAACQLQEAAJAtAQAAQLYEAABAtgQAAEC2BAAAQLYEAABAtgQAAADZEgAAANkSAAAA2RIAAADZEgAAAGRLAAAAZEsAAABkSwAAAGRLAAAAkC0BAACQLQEAAJAtAQAAkC0BAABAtgQAAEC2BAAAQLYEAABAtgQAAADZEgAAANkSAAAA2RIAAADZEgAAAGRLAAAAZEsAAABkSwAAAGRLAAAAkC0BAACQLQEAAJAtAQAAkC0BAABAtgQAAEC2BAAAQLYEAABAtgQAAADZEgAAANkSAAAA2RIAAADZEgAAAGRLAAAAZEsAAABkSwAAAGRLAAAAkC0BAACQLQEAAJAtAQAAkC0BAABAtgQAAEC2BAAAQLYEAABAtgQAAADZEgAAANkSAAAA2RIAAADZEgAAAGRLAAAAZEsAAABkSwAA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"
                    /> -->
                    <div class="c x1 y1 w2 h2"><div class="t m0 x2 h3 y2 ff1 fs0 fc0 sc0 ls0 ws0"></div></div>
                    <div class="c x3 y1 w3 h2">
                        <div class="t m0 x4 h4 y3 ff2 fs1 fc0 sc0 ls0 ws0">
                            KeyPoint Technologie<span class="ls1">s I<span class="ls2">nd</span></span>ia Pvt Ltd.
                        </div>
                        <div class="t m0 x4 h5 y4 ff1 fs1 fc0 sc0 ls3 ws0">
                            RA<span class="ls0">JAPRAASADA<span class="ls4">MU</span><span class="ff2"> </span></span>
                        </div>
                        <div class="t m0 x4 h5 y5 ff1 fs1 fc0 sc0 ls0 ws0">
                            D.<span class="ls5">No<span class="ls6">. 1</span></span>-55/4/RP<span class="ls7">/L</span>2/W<span class="_ _0"></span>1
                        </div>
                        <div class="t m0 x4 h5 y6 ff1 fs1 fc0 sc0 ls0 ws0">Level 2, Wing 1B &amp; 2</div>
                        <div class="t m0 x4 h5 y7 ff1 fs1 fc0 sc0 ls0 ws0">Botanical Gardens Road</div>
                        <div class="t m0 x4 h5 y8 ff1 fs1 fc0 sc0 ls0 ws0">Kondapur, Hyderabad - <span class="ls8">500084</span></div>
                        <div class="t m0 x4 h5 y9 ff1 fs1 fc1 sc0 ls0 ws0">w<span class="ls9">ww</span>.<span class="ls4">ke</span>ypoi<span class="lsa">nt</span>-tech<span class="ls6">.c</span>om</div>
                        <div class="t m0 x4 h5 ya ff1 fs1 fc0 sc0 ls0 ws0">C<span class="ls6">IN</span> : U722<span class="ls8">00</span>TG2007FTC0<span class="ls8">543</span>51</div>
                    </div>
                    <div class="c x0 yb w4 h0">
                        <div class="t m0 x5 h3 yc ff1 fs0 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x6 h3 yd ff1 fs0 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x6 h3 ye ff1 fs0 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x7 h6 yf ff2 fs2 fc0 sc0 ls0 ws0">Tax Invoice</div>
                    </div>
                    <div class="c x8 y10 w5 h7">
                        <div class="t m0 x9 h8 y11 ff1 fs3 fc0 sc0 ls0 ws0">Seller</div>
                        <div class="t m0 x9 h9 y12 ff3 fs3 fc0 sc0 lsb ws0">
                            M/S
                            <span class="ff1 ls0">
                                <span class="ff3">KeyPoint<span class="_ _0"></span> Technologies India <span class="_ _0"></span>Pvt <span class="lsc">Ltd</span></span><span class="lsd">.,<span class="_ _0"></span></span>
                            </span>
                        </div>
                        <div class="t m0 x9 ha y13 ff4 fs3 fc0 sc0 ls0 ws0">Rajapraasadamu, Lev<span class="_ _0"></span>el 2 Wing 2,<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y14 ff4 fs3 fc0 sc0 ls0 ws0">Botanical Gardens Ro<span class="_ _0"></span>ad, Kondapur,<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y15 ff4 fs3 fc0 sc0 ls0 ws0">Hyderabad <span class="ff5">–</span> 500<span class="_ _0"></span> 049, Telangana S<span class="_ _0"></span>tate</div>
                        <div class="t m0 x9 ha y16 ff4 fs3 fc0 sc0 ls0 ws0">State Code: 36<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y17 ff4 fs3 fc0 sc0 ls0 ws0">GSTIN<span class="lse"> </span> : 36A<span class="_ _0"></span>ADCK1115A1ZC<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y18 ff4 fs3 fc0 sc0 ls0 ws0">PAN : AADC<span class="_ _0"></span>K1115A<span class="ff6"> </span></div>
                    </div>
                    <div class="c xa y10 w6 h7">
                        <div class="t m0 x9 h9 y19 ff3 fs3 fc0 sc0 ls0 ws0">
                            Invoice No<span class="ff4">.<span class="_ _0"></span> </span>
                        </div>
                        <div class="t m0 x9 ha y1a ff4 fs3 fc0 sc0 ls0 ws0">215<span class="lsf">71</span></div>
                        <div class="t m0 x9 h8 y1b ff1 fs3 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x9 h9 y1c ff3 fs3 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x9 h9 y1d ff3 fs3 fc0 sc0 ls0 ws0">
                            PO.<span class="ls10">NO<span class="lse"> </span></span>:
                            <span class="ff6">
                                <span class="ff4">eFACT<span class="_ _0"></span>S/KEYPOINT/001<span class="_ _0"></span> </span>
                            </span>
                        </div>
                        <div class="t m0 x9 h9 y1e ff3 fs3 fc0 sc0 ls0 ws0">
                            Quote ID:<span class="ff4"> Q0112<span class="_ _0"></span>2021121237<span class="_ _0"></span><span class="ff6"> </span></span>
                        </div>
                    </div>
                    <div class="c xb y10 w7 h7">
                        <div class="t m0 x9 h9 y19 ff3 fs3 fc0 sc0 ls10 ws0">Da<span class="ls0">te </span></div>
                        <div class="t m0 x9 ha y1a ff4 fs3 fc0 sc0 ls11 ws0">
                            24<span class="ls0">-Dec-</span>20<span class="ls0">21<span class="_ _0"></span><span class="ff6"> </span></span>
                        </div>
                    </div>
                    <div class="c x8 y1f w5 hb">
                        <div class="t m0 x9 h9 y20 ff4 fs3 fc0 sc0 ls0 ws0">Buyer<span class="ff3"> </span></div>
                        <div class="t m0 x9 h9 y13 ff3 fs3 fc0 sc0 lsb ws0">
                            M/<span class="ls0">S eFACTS Solution<span class="_ _0"></span>s Private Limit<span class="_ _0"></span>ed.,<span class="_ _0"></span> </span>
                        </div>
                        <div class="t m0 x9 ha y14 ff4 fs3 fc0 sc0 ls11 ws0">
                            33, <span class="ls0">Maruti Tower<span class="_ _0"></span><span class="ls12">, </span>Thakur Complex, </span>
                        </div>
                        <div class="t m0 x9 ha y15 ff4 fs3 fc0 sc0 ls0 ws0">Kandiv<span class="ls13">li</span> (East<span class="ls14">),</span> Mumbai 40<span class="_ _0"></span>0101</div>
                        <div class="t m0 x9 ha y16 ff4 fs3 fc0 sc0 ls0 ws0">State Code: 27<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y17 ff4 fs3 fc0 sc0 ls0 ws0">GSTIN : 27AACC<span class="_ _0"></span>E1613L1Z<span class="_ _0"></span>S<span class="lse"> </span></div>
                        <div class="t m0 x9 ha y18 ff4 fs3 fc0 sc0 ls0 ws0">PAN : AACCE<span class="_ _0"></span>1613L</div>
                    </div>
                    <div class="c xa y1f w8 hb">
                        <div class="t m0 x9 ha y21 ff4 fs3 fc0 sc0 ls0 ws0">Ban<span class="ls15">k </span>Name<span class="lse"> </span> : <span class="ls10">HD</span>FC <span class="_ _0"></span>Bank</div>
                        <div class="t m0 x9 ha y22 ff4 fs3 fc0 sc0 ls16 ws0">
                            Ba
                            <span class="ls0">
                                nk Address<span class="_ _0"></span> : <span class="ls10">No<span class="ls12">.2</span></span>-<span class="ls11">41/</span>2/A, Pavan Pr<span class="_ _0"></span>iyanka Plaz<span class="_ _0"></span>a,
                                <span class="lse"> </span> <span class="fc2 sc0"> </span><span class="fc2 sc0"> </span><span class="_ _0"></span><span class="fc2 sc0"> </span><span class="fc2 sc0"> </span>
                            </span>
                        </div>
                        <div class="t m0 x9 ha y23 ff4 fs3 fc0 sc0 lse ws0">
                            <span class="_ _0"></span> <span class="ls0">Kot<span class="_ _0"></span>haguda Cross<span class="_ _0"></span>roads</span>, <span class="ls0">Kon<span class="_ _0"></span>dapur, </span>
                        </div>
                        <div class="t m0 x9 ha y24 ff4 fs3 fc0 sc0 lse ws0">
                            <span class="_ _0"></span>
                            <span class="ls0"> Hy<span class="_ _0"></span>derabad <span class="ff5">–</span> 5<span class="_ _0"></span>00 084, Telang<span class="_ _0"></span>ana State<span class="_ _0"></span> </span>
                        </div>
                        <div class="t m0 x9 ha y25 ff4 fs3 fc0 sc0 ls0 ws0">
                            Account <span class="ls17">Na</span>m<span class="ls11">e:</span> <span class="lse"> </span>KeyPoint <span class="_ _0"></span>Technologies (Indi<span class="_ _0"></span>a) Pvt Lt<span class="_ _0"></span>d
                        </div>
                        <div class="t m0 x9 ha y26 ff4 fs3 fc0 sc0 ls0 ws0">Account Numbe<span class="ls14">r:</span> 2<span class="ls11">01</span>9<span class="_ _0"></span>2320000213<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y27 ff4 fs3 fc0 sc0 ls0 ws0">IFSC Code : HDF<span class="_ _0"></span>C0002019<span class="_ _0"></span></div>
                    </div>
                    <div class="c x8 y28 w9 hc">
                        <div class="t m0 xc h9 y29 ff3 fs3 fc0 sc0 ls16 ws0">S.<span class="ls0"> No. </span></div>
                    </div>
                    <div class="c x4 y28 wa hc">
                        <div class="t m0 x9 h9 y2a ff2 fs0 fc0 sc0 ls0 ws0">Description<span class="ff3 fs3"> </span></div>
                    </div>
                    <div class="c xa y28 wb hc">
                        <div class="t m0 xd h9 y29 ff3 fs3 fc0 sc0 ls0 ws0">Word C<span class="lsc">ount</span></div>
                    </div>
                    <div class="c xe y28 wb hc">
                        <div class="t m0 x1 h9 y29 ff3 fs3 fc0 sc0 ls0 ws0">Cost Per Word<span class="_ _0"></span></div>
                    </div>
                    <div class="c xb y28 w7 hc">
                        <div class="t m0 xf h9 y29 ff3 fs3 fc0 sc0 ls0 ws0">A<span class="ls18">mount</span> (INR)</div>
                    </div>
                    <div class="c x8 y2b w9 hd">
                        <div class="t m0 x10 ha y2c ff4 fs3 fc0 sc0 ls0 ws0">1</div>
                        <div class="t m0 x10 ha y2d ff4 fs3 fc0 sc0 ls0 ws0">2</div>
                        <div class="t m0 x10 ha y2e ff4 fs3 fc0 sc0 ls0 ws0">3</div>
                        <div class="t m0 x10 ha y2f ff4 fs3 fc0 sc0 ls0 ws0">4</div>
                        <div class="t m0 x10 ha y26 ff4 fs3 fc0 sc0 ls0 ws0">5</div>
                        <div class="t m0 x10 ha y27 ff4 fs3 fc0 sc0 ls0 ws0">6</div>
                    </div>
                    <div class="c x4 y2b wa hd">
                        <div class="t m0 x9 ha y2c ff4 fs3 fc0 sc0 ls0 ws0">Translation from <span class="_ _0"></span>English to Tamil<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y2d ff4 fs3 fc0 sc0 ls0 ws0">Translation from <span class="_ _0"></span>English to Malayal<span class="_ _0"></span>am</div>
                        <div class="t m0 x9 ha y2e ff4 fs3 fc0 sc0 ls0 ws0">Translation from <span class="_ _0"></span>English to Kannad<span class="_ _0"></span>a</div>
                        <div class="t m0 x9 ha y2f ff4 fs3 fc0 sc0 ls0 ws0">Translation from <span class="_ _0"></span>English to Telugu<span class="_ _0"></span></div>
                        <div class="t m0 x9 ha y26 ff4 fs3 fc0 sc0 ls0 ws0">Translation from <span class="_ _0"></span>English to Bengali<span class="_ _0"></span></div>
                        <div class="t m0 x9 he y27 ff4 fs3 fc0 sc0 ls0 ws0">Translation from <span class="_ _0"></span>English to Guj<span class="_ _0"></span>arati<span class="fs4"> </span></div>
                    </div>
                    <div class="c xa y2b wb hd">
                        <div class="t m0 x11 ha y2c ff4 fs3 fc0 sc0 ls11 ws0">1171<span class="ls0"> </span></div>
                        <div class="t m0 x11 ha y2d ff4 fs3 fc0 sc0 ls11 ws0">1171<span class="ls0"> </span></div>
                        <div class="t m0 x11 ha y2e ff4 fs3 fc0 sc0 ls11 ws0">1171<span class="ls0"> </span></div>
                        <div class="t m0 x11 ha y2f ff4 fs3 fc0 sc0 ls11 ws0">1171<span class="ls0"> </span></div>
                        <div class="t m0 x11 ha y26 ff4 fs3 fc0 sc0 ls11 ws0">1171<span class="ls0"> </span></div>
                        <div class="t m0 x11 ha y27 ff4 fs3 fc0 sc0 ls11 ws0">1171<span class="ls0"> </span></div>
                    </div>
                    <div class="c xe y2b wb hd">
                        <div class="t m0 x12 ha y2c ff4 fs3 fc0 sc0 ls11 ws0">1.50<span class="ls0"> </span></div>
                        <div class="t m0 x12 ha y2d ff4 fs3 fc0 sc0 ls11 ws0">1.50<span class="ls0"> </span></div>
                        <div class="t m0 x12 ha y2e ff4 fs3 fc0 sc0 ls11 ws0">1.50<span class="ls0"> </span></div>
                        <div class="t m0 x12 ha y2f ff4 fs3 fc0 sc0 ls11 ws0">1.50<span class="ls0"> </span></div>
                        <div class="t m0 x12 ha y26 ff4 fs3 fc0 sc0 ls11 ws0">1.50<span class="ls0"> </span></div>
                        <div class="t m0 x12 ha y27 ff4 fs3 fc0 sc0 ls11 ws0">1.5<span class="ls0">0 </span></div>
                    </div>
                    <div class="c xb y2b w7 hd">
                        <div class="t m0 x13 ha y2c ff4 fs3 fc0 sc0 ls0 ws0">1,757<span class="lse">.00</span></div>
                        <div class="t m0 x13 ha y2d ff4 fs3 fc0 sc0 ls0 ws0">1,757<span class="lse">.00</span></div>
                        <div class="t m0 x13 ha y2e ff4 fs3 fc0 sc0 ls0 ws0">1,757<span class="lse">.00</span></div>
                        <div class="t m0 x13 ha y2f ff4 fs3 fc0 sc0 ls0 ws0">1,757<span class="lse">.00</span></div>
                        <div class="t m0 x13 ha y26 ff4 fs3 fc0 sc0 ls0 ws0">1,757<span class="lse">.00</span></div>
                        <div class="t m0 x13 ha y27 ff4 fs3 fc0 sc0 ls0 ws0">1,757<span class="lse">.00</span></div>
                    </div>
                    <div class="c x8 y30 wc hf"><div class="t m0 x14 h9 y31 ff3 fs3 fc0 sc0 ls0 ws0">Total</div></div>
                    <div class="c xb y30 w7 hf">
                        <div class="t m0 x15 h9 y31 ff3 fs3 fc0 sc0 ls11 ws0">
                            10<span class="ls0">,539.00<span class="_ _0"></span> </span>
                        </div>
                    </div>
                    <div class="c x8 y32 wd h10">
                        <div class="t m0 x9 ha y27 ff4 fs3 fc0 sc0 lse ws0">
                            <span class="ls0"> </span> <span class="ls0"> <span class="_ _0"></span> </span> <span class="ls0"> <span class="_ _0"></span> </span> <span class="ls0"> <span class="_ _0"></span> </span>
                            <span class="_ _0"></span><span class="ls0"> </span> <span class="ls0"> </span> <span class="ls0"> <span class="_ _0"></span> <span class="_ _0"></span> </span> <span class="_ _0"></span>
                            <span class="ls0">IGST <span class="_ _0"></span>@<span class="ls11">18</span>% </span>
                        </div>
                    </div>
                    <div class="c xb y32 we h10">
                        <div class="t m0 x13 ha y27 ff4 fs3 fc0 sc0 ls0 ws0">1,897.<span class="ls11">00</span><span class="ff6"> </span></div>
                    </div>
                    <div class="c x8 y33 wc hf">
                        <div class="t m0 x16 h9 y31 ff3 fs3 fc0 sc0 ls0 ws0">Grand Total<span class="_ _0"></span></div>
                    </div>
                    <div class="c xb y33 w7 hf">
                        <div class="t m0 x15 h9 y31 ff3 fs3 fc0 sc0 ls11 ws0">
                            12<span class="ls0">,436.<span class="lsf">00</span> </span>
                        </div>
                    </div>
                    <div class="c x8 y34 wc h11">
                        <div class="t m0 x9 ha y35 ff4 fs3 fc0 sc0 ls16 ws0">
                            Am<span class="ls0">ount charge<span class="_ _0"></span>able (in wo<span class="ls19">rd</span>s<span class="ls14">):</span> </span>
                        </div>
                        <div class="t m0 x9 h9 y31 ff3 fs3 fc0 sc0 ls0 ws0">
                            INR <span class="lsc">Tw</span>elve Thousand F<span class="_ _0"></span>our Hundred<span class="_ _0"></span> Thirty-Si<span class="_ _0"></span>x Only<span class="ff7"> </span>
                        </div>
                    </div>
                    <div class="c xb y34 w7 h11">
                        <div class="t m0 x17 h12 y36 ff7 fs3 fc0 sc0 ls16 ws0">E&amp;<span class="ls0"> O. E </span></div>
                    </div>
                    <div class="c x8 y37 w9 hf">
                        <div class="t m0 xf h9 y31 ff3 fs3 fc0 sc0 ls0 ws0">S<span class="ls10">AC</span></div>
                    </div>
                    <div class="c x4 y37 wa hf">
                        <div class="t m0 x18 h9 y31 ff3 fs3 fc0 sc0 ls0 ws0">Taxable Value<span class="_ _0"></span></div>
                    </div>
                    <div class="c xa y37 w6 hf">
                        <div class="t m0 x19 h9 y31 ff3 fs3 fc0 sc0 ls0 ws0">Tax R<span class="ls11">at</span>e<span class="_ _0"></span></div>
                    </div>
                    <div class="c xb y37 w7 hf">
                        <div class="t m0 x6 h9 y31 ff3 fs3 fc0 sc0 ls10 ws0">
                            Am<span class="lsc">ount<span class="ls0"> </span></span>
                        </div>
                    </div>
                    <div class="c x8 y38 w9 h13">
                        <div class="t m0 x9 ha y39 ff4 fs3 fc0 sc0 ls11 ws0">99<span class="ls0">83</span>95<span class="ls0"> </span></div>
                    </div>
                    <div class="c x4 y38 wa h13">
                        <div class="t m0 x1a ha y39 ff4 fs3 fc0 sc0 ls11 ws0">
                            10<span class="ls0">,539.00<span class="_ _0"></span> </span>
                        </div>
                    </div>
                    <div class="c xa y3a w6 h14">
                        <div class="t m0 x1b ha y3b ff4 fs3 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x1c ha y3c ff4 fs3 fc0 sc0 ls11 ws0">18%<span class="ls0"> </span></div>
                    </div>
                    <div class="c xb y38 w7 h13">
                        <div class="t m0 x1d ha y39 ff4 fs3 fc0 sc0 ls0 ws0">1,897<span class="lse">.0</span>0</div>
                    </div>
                    <div class="c x8 y3a w9 h15">
                        <div class="t m0 x1e h9 y3d ff3 fs3 fc0 sc0 lsc ws0">
                            Tot<span class="ls11">al<span class="ls0"> </span></span>
                        </div>
                    </div>
                    <div class="c x4 y3a wa h15">
                        <div class="t m0 x1a h9 y3d ff3 fs3 fc0 sc0 ls11 ws0">
                            10<span class="ls0">,539.00<span class="_ _0"></span> </span>
                        </div>
                    </div>
                    <div class="c xb y3a w7 h15">
                        <div class="t m0 x1d h9 y3d ff3 fs3 fc0 sc0 ls0 ws0">1,897<span class="lse">.00</span></div>
                    </div>
                    <div class="c x8 y3e w5 h16">
                        <div class="t m0 x9 ha y23 ff4 fs3 fc0 sc0 ls0 ws0">Tax amount (in w<span class="_ _0"></span>ords<span class="ls14">):</span></div>
                        <div class="t m0 x9 h9 y24 ff3 fs3 fc0 sc0 ls0 ws0">INR One Thousand <span class="_ _0"></span>Eight <span class="_ _0"></span>Hundred Ninety-<span class="_ _0"></span>Seven O<span class="_ _0"></span>nly</div>
                    </div>
                    <div class="c xa y3e w8 h16">
                        <div class="t m0 x9 h9 y23 ff3 fs3 fc0 sc0 ls0 ws0">For KeyPoint Techno<span class="_ _0"></span>logies India P<span class="_ _0"></span>rivate Limit<span class="_ _0"></span>ed<span class="lse">.,</span></div>
                        <div class="t m0 x9 h9 y24 ff3 fs3 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x9 h9 y25 ff3 fs3 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x9 h9 y26 ff3 fs3 fc0 sc0 ls0 ws0"></div>
                        <div class="t m0 x9 h9 y27 ff3 fs3 fc0 sc0 ls0 ws0">
                            Authorised Signa
                            <span class="ls14">
                                to<span class="ls1a">ry<span class="_ _0"></span></span>
                            </span>
                            <span class="ff4"> </span>
                        </div>
                    </div>
                    <div class="c x0 yb w4 h0"><div class="t m0 x6 ha y3f ff4 fs3 fc0 sc0 ls0 ws0"></div></div>
                    <div class="c x1f y40 wf h17">
                        <div class="t m1 x0 h18 y41 ff8 fs5 fc0 sc0 ls0 ws0">GOPI</div>
                        <div class="t m1 x0 h18 y42 ff8 fs5 fc0 sc0 ls0 ws0">SRINIVAS</div>
                        <div class="t m1 x0 h18 y43 ff8 fs5 fc0 sc0 ls0 ws0">THEDLAPU</div>
                    </div>
                    <div class="c x20 y40 w10 h17">
                        <div class="t m1 x0 h19 y44 ff8 fs6 fc0 sc0 ls0 ws0">Digitally signed by</div>
                        <div class="t m1 x0 h19 y45 ff8 fs6 fc0 sc0 ls0 ws0">GOPI SRINIVAS</div>
                        <div class="t m1 x0 h19 y46 ff8 fs6 fc0 sc0 ls0 ws0">THEDLAPU</div>
                        <div class="t m1 x0 h19 y47 ff8 fs6 fc0 sc0 ls0 ws0">Date: 2021.12.24</div>
                        <div class="t m1 x0 h19 y48 ff8 fs6 fc0 sc0 ls0 ws0">15:47:14 +05&apos;30&apos;</div>
                    </div>
                    <a class="l" href="file:///C:/Users/bbaig/AppData/Local/Microsoft/Windows/Temporary%20Internet%20Files/Content.Outlook/RX0O5RBX/www.keypoint-tech.com">
                        <div class="d m2" style="border-style: none; position: absolute; left: 677.55px; bottom: 1055.4px; width: 94.79px; height: 10.98px; background-color: rgba(255, 255, 255, 0.000001);"></div>
                    </a>
                </div>
                <div class="pi" data-data='{"ctm":[1.500000,0.000000,0.000000,1.500000,0.000000,0.000000]}'></div>
            </div>
        </div>
        <div class="loading-indicator">
            <img
                alt=""
                src="data:image/png;base64,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"
            />
        </div>
    </body>
</html>
