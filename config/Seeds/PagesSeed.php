<?php

use Migrations\AbstractSeed;

/**
 * Countries seed.
 */
class PagesSeed extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
//        $data = [
//            [
//                'name' => 'Contact Us',
//                'body' => '<div style="width: 100%"><h2 style="font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-weight: 600; color: rgb(65, 65, 65);">Visit Us</h2><p style="-webkit-margin-before: 0.1em; -webkit-margin-after: 0.1em; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;">Lid est laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis fugats vitaes nemo minima rerums unsers sadips amets.</p><hr style="height: 1px; overflow: visible; margin-top: 1rem; margin-bottom: 1rem; border-top: 0px; background-image: -webkit-linear-gradient(left, transparent, rgba(0, 0, 0, 0.2), transparent); color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;"><p style="-webkit-margin-before: 0.1em; -webkit-margin-after: 0.1em; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;"><span class="block"><span style="font-weight: bolder;"><span class="fa fa-map-marker"></span>&nbsp;Address:</span>&nbsp;Street Name, City Name, Country</span><span class="block"><span style="font-weight: bolder;"><span class="fa fa-phone"></span>&nbsp;Phone:</span>&nbsp;<a href="tel:1800-555-1234" style="color: rgb(138, 185, 51); touch-action: manipulation;">1800-555-1234</a></span><span class="block"><span style="font-weight: bolder;"><span class="fa fa-envelope"></span>&nbsp;Email:</span>&nbsp;<a href="mailto:mail@yourdomain.com" style="color: rgb(138, 185, 51); touch-action: manipulation;">mail@yourdomain.com</a></span></p><hr style="height: 1px; overflow: visible; margin-top: 1rem; margin-bottom: 1rem; border-top: 0px; background-image: -webkit-linear-gradient(left, transparent, rgba(0, 0, 0, 0.2), transparent); color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;"><h4 class="font300" style="font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-weight: 600; color: rgb(65, 65, 65);">Business Hours</h4><p style="-webkit-margin-before: 0.1em; -webkit-margin-after: 0.1em; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;"><span class="block"><span style="font-weight: bolder;">Monday - Friday:</span>&nbsp;10am to 6pm</span><span class="block"><span style="font-weight: bolder;">Saturday:</span>&nbsp;10am to 2pm</span><span class="block"><span style="font-weight: bolder;">Sunday:</span>&nbsp;Closed</span></p></div>',
//                'slug' => 'contact-us',
//                'position' => 2
//            ],
//            [
//                'name' => 'About Us',
//                'body' => '<div class="row" style="display: flex; flex-wrap: wrap; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;"><div class="col-lg-12" style="width: 1170px; -webkit-box-flex: 0; flex: 0 0 100%; max-width: 100%;"><h2 style="font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-weight: 600; color: rgb(65, 65, 65);"><p class="text-center" style="-webkit-margin-before: 0.1em; -webkit-margin-after: 0.1em;">What is Stock Gitter?</p></h2></div><div class="col-lg-12" style="width: 1170px; -webkit-box-flex: 0; flex: 0 0 100%; max-width: 100%;"><p style="-webkit-margin-before: 0.1em; -webkit-margin-after: 0.1em;">Stockgitter is a financial social media platform that gives the average person the opportunity to get in on the financial Lingo or chit chat in a community setting. We aim at allowing anyone to realize the opportunities available in the Stock, Forex, Bond and Treasury markets to invest in. The aim is to give a plain and simple lingo to eliminate the notion that you have to be rich to make good financial investments.</p></div></div><div class="row" style="display: flex; flex-wrap: wrap; color: rgb(102, 102, 102); font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-size: 16px;"><div class="col-lg-4" style="width: 390px; -webkit-box-flex: 0; flex: 0 0 33.3333%; max-width: 33.3333%;"><div class="heading-title heading-border-bottom" style="position: relative; margin-bottom: 40px; border-bottom: 2px solid rgb(204, 204, 204);"><h3 style="margin-bottom: 0px; font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-weight: 600; color: rgb(65, 65, 65); padding: 0px 15px 0px 0px; position: relative; display: inline-block;">What will you find here?</h3></div><div class="col-sm-12" style="width: 360px; -webkit-box-flex: 0; flex: 0 0 100%; max-width: 100%;"><ul class="list-unstyled list-icons"><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Communicate with one or more users on several stocks, currencies etc.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>View market data and news on the Jamaican and US Stock Market</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Get first to know IPO information on Jamaican Market</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Make posts on the news feed about a stock/instrument you like or interested in.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>View and Compare Brokerage information to make solid decisions on who to trade with.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Get real time information on IPOs and be able to see what others are saying about it.</li></ul></div></div><div class="col-lg-4" style="width: 390px; -webkit-box-flex: 0; flex: 0 0 33.3333%; max-width: 33.3333%;"><div class="heading-title heading-border-bottom" style="position: relative; margin-bottom: 40px; border-bottom: 2px solid rgb(204, 204, 204);"><h3 style="margin-bottom: 0px; font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-weight: 600; color: rgb(65, 65, 65); padding: 0px 15px 0px 0px; position: relative; display: inline-block;">What we are not:</h3></div><div class="col-sm-12" style="width: 360px; -webkit-box-flex: 0; flex: 0 0 100%; max-width: 100%;"><ul class="list-unstyled list-icons"><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>An investment nor financial advisor.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>An investment broker.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>A Solicitor for investors into any company or vendor.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>A Financial institution that conducts transfers, deposits currency exchange.</li></ul></div></div><div class="col-lg-4" style="width: 390px; -webkit-box-flex: 0; flex: 0 0 33.3333%; max-width: 33.3333%;"><div class="heading-title heading-border-bottom" style="position: relative; margin-bottom: 40px; border-bottom: 2px solid rgb(204, 204, 204);"><h3 style="margin-bottom: 0px; font-family: &quot;Open Sans&quot;, Arial, Helvetica, sans-serif; font-weight: 600; color: rgb(65, 65, 65); padding: 0px 15px 0px 0px; position: relative; display: inline-block;">What we are:</h3></div><div class="col-sm-12" style="width: 360px; -webkit-box-flex: 0; flex: 0 0 100%; max-width: 100%;"><ul class="list-unstyled list-icons"><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Friendly environment that promotes ideas opinions and facts.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Encouraging freedom of expression.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Promoting networking and communicating.</li><li><span class="fa fa-check text-success" style="margin-right: 10px; position: absolute; left: -1.75em; width: 14px; text-align: center; top: 5px;"></span>Ability for investors to help build a healthy financial community.</li></ul></div></div></div>',
//                'slug' => 'about-us',
//                'position' => 1
//            ],
//            [
//                'name' => 'Terms of Service',
//                'body' => '',
//                'slug' => 'terms-of-service',
//                'position' => 3
//            ],
//        ];
//
//        $table = $this->table('pages');
//        $table->truncate();
//        $table->insert($data)->save();
    }

}
