
<table class="body">
    <tr>
        <td class="center" align="center" valign="top">
            <!-- BEGIN: Header -->

            <!-- END: Header -->
            <!-- BEGIN: Content -->
            <table class="container content" align="center">
                <tr>
                    <td>
                        <table class="row note">
                            <tr>
                                <td class="wrapper last">
                                    <h2>Welcome to  Stockgitter</h2>
                                    <p>
                                        Hello <?= $user_details['first_name'].' '.$user_details['last_name']; ?>, <br /> <br />
                                        We have introduced some new features in our web application. Enjoy free 14 days trial with our professional experience using the code given below. <br /> <br />
                                        Your account invitation code is : <?php echo $invitation_code; ?>
                                    </p>
                                    <p>
                                    Please use this code before : <?php echo $inv_code_exp; ?>
                                    </p>
                                    <p>
                                    To enter this code, please visit <a href="www.stockgitter.com">www.stockgitter.com</a> and enter the code provided under guest invite.
                                    </p>
                                    <p>
                                    Thank You,
                                    Stockgitter Team
                                    </p>


                            </tr>
                        </table>
                        <span class="devider">
                        </span>

                    </td>
                </tr>
            </table>
            <!-- END: Content -->
            <!-- BEGIN: Footer -->
            <table class="footer" align="center">
                <tr>
                    <td class="center" align="center">
                        <table class="container" align="center">
                            <tr>
                                <td>
                                    <!-- BEGIN: Unsubscribet -->
                                    <table class="row">
                                        <tr>
                                            <td class="wrapper last">
                                                <span style="font-size:12px;">
                                                    <i>This ia a system generated email and reply is not required.</i>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END: Unsubscribe -->
                                    <!-- BEGIN: Footer Panel -->

                                    <!-- END: Footer Panel List -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- END: Footer -->
        </td>
    </tr>
</table>
