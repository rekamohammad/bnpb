<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ setting('site_title') }}</title>
</head>
<body style="margin: 0;">
<table width="100%" id="mainStructure" border="0" cellspacing="0" cellpadding="0"
       style="background-color: #e1e1e1;border-spacing: 0;">
    <!-- START TAB TOP -->
    <tbody>
    <tr>
        <td valign="top" style="border-collapse: collapse;">
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-spacing: 0;">
                <tbody>
                <tr>
                    <td valign="top" height="6" style="border-collapse: collapse;">
                        <table width="800" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                               style="border-spacing: 0;">
                            <!-- start space height -->
                            <tbody>
                            <tr>
                                <td height="5" valign="top" style="border-collapse: collapse;"></td>
                            </tr>
                            <!-- end space height -->
                            <tr>
                                <td height="5" class="remove" style="border-collapse: collapse;"></td>
                            </tr>
                            <!-- start space height -->
                            <tr>
                                <td height="5" valign="top" style="border-collapse: collapse;"></td>
                            </tr>
                            <tr>
                                <td height="5" class="remove" style="border-collapse: collapse;"></td>
                            </tr>
                            <!-- end space height -->
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <!-- END TAB TOP -->
    <!--START TOP NAVIGATION ?LAYOUT-->
    <tr>
        <td align="center" valign="top" class="fix-box" style="border-collapse: collapse;">
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                   style="border-spacing: 0;">
                <!-- START CONTAINER NAVIGATION -->
                <tbody>
                <tr>
                    <td valign="top" style="border-collapse: collapse;">
                        <!-- start top navigation container -->
                        <table width="800" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                               style="border-spacing: 0;">
                            <tbody>
                            <tr>
                                <td valign="top" bgcolor="00a0e0" style="border-collapse: collapse;">
                                    <!-- start top navigaton -->
                                    <table width="800" align="center" border="0" cellspacing="0" cellpadding="0"
                                           class="full-width" style="border-spacing: 0;">
                                        <tbody>
                                        <tr>
                                            <td valign="top" style="border-collapse: collapse;">
                                                <table align="left" border="0" cellspacing="0" cellpadding="0"
                                                       class="container2" style="border-spacing: 0;">
                                                    <tbody>
                                                    <tr>
                                                        <td height="10" style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="remove-479" height="12"
                                                            style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="remove-479" height="12"
                                                            style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top"
                                                            style="border-collapse: collapse;">
                                                            <table align="center" border="0" cellspacing="0"
                                                                   cellpadding="0" style="border-spacing: 0;">
                                                                <tbody>
                                                                <tr>
                                                                    <!--start  space width -->
                                                                    <td valign="top" align="center" class="remove-479"
                                                                        style="border-collapse: collapse;">
                                                                        <table width="20" align="right" border="0"
                                                                               cellpadding="0" cellspacing="0"
                                                                               style="height: 5px;border-spacing: 0;">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td valign="top"
                                                                                    style="border-collapse: collapse;">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <!--start  space width -->
                                                                    <td align="center" valign="top"
                                                                        style="font-size:13px;line-height:22px;color:#fff;font-weight:normal;text-align:center;font-family:Tahoma,Helvetica,Arial,sans-serif;border-collapse:collapse">
                                                                        <a href="{{ url('/') }}" style="color: #fff; text-decoration: none !important;"
                                                                           target="_blank">
                                                                            @if (empty(theme_option('logo')))
                                                                                {{ setting('site_title') }}
                                                                            @else
                                                                                <img title="Logo"
                                                                                                    src="{{ url(theme_option('logo')) }}"
                                                                                                    width="107"
                                                                                                    style="max-width: 107px;display: block !important;height: auto !important;"
                                                                                                    alt="Logo" border="0"
                                                                                                    hspace="0"
                                                                                                    vspace="0"></a>
                                                                            @endif
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="10" valign="top"
                                                            style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="remove-479" height="12" valign="top"
                                                            style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!--start content nav -->
                                                <table border="0" align="right" cellpadding="0" cellspacing="0"
                                                       class="container2" style="border-spacing: 0;">
                                                    <tbody>
                                                    <tr>
                                                        <td class="remove-479" height="30"
                                                            style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    <!--start event date -->
                                                    <tr>
                                                        <td valign="mindle" align="center"
                                                            style="border-collapse: collapse;">
                                                            <table align="right" border="0" cellpadding="0"
                                                                   cellspacing="0" class="clear-align"
                                                                   style="border-spacing: 0;">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="border-collapse: collapse;">
                                                                        <img width="20"
                                                                             style="display: block;height: auto !important;"
                                                                             src="https://gallery.mailchimp.com/7322fbb8c9fca82452c7533d9/images/336af2da-ecfd-4520-8df5-5df1b12067a6.jpg"
                                                                             alt="icon date">
                                                                    </td>
                                                                    <td style="border-collapse: collapse;">
                                                                        &nbsp;&nbsp;</td>
                                                                    <td style="font-size: 13px;line-height: 22px;color: #FFF;font-weight: normal;text-align: center;font-family: Tahoma, Helvetica, Arial, sans-serif;border-collapse: collapse;">{{ Carbon::now() }}</td>
                                                                    <!--start  space width -->
                                                                    <td valign="top" align="center" class="remove-479"
                                                                        style="border-collapse: collapse;">
                                                                        <table width="20" align="right" border="0"
                                                                               cellpadding="0" cellspacing="0"
                                                                               style="height: 5px;border-spacing: 0;">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td valign="top"
                                                                                    style="border-collapse: collapse;">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <!--start  space width -->
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <!--end event date -->
                                                    <tr>
                                                        <td height="10" style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="remove-479" height="20"
                                                            style="border-collapse: collapse;"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!--end content nav -->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!-- end top navigaton -->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- end top navigation container -->
                    </td>
                </tr>
                <!-- END CONTAINER NAVIGATION -->
                </tbody>
            </table>
        </td>
    </tr>
    <!--END TOP NAVIGATION LAYOUT-->
    <!-- START MAIN CONTENT-->
    <tr>
        <td align="center" valign="top" class="fix-box" title="bg_color" style="border-collapse: collapse;">
            <!-- start layout-7 container -->
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                   style="border-spacing: 0;">
                <tbody>
                <tr>
                    <td valign="top" style="border-collapse: collapse;">
                        <table width="800" align="center" border="0" cellspacing="0" cellpadding="0" class="container"
                               bgcolor="#ffffff" style="background-color: #ffffff;border-spacing: 0;">
                            <!--start space height -->
                            <tbody>
                            <tr>
                                <td height="30" style="border-collapse: collapse;"></td>
                            </tr>
                            <!--end space height -->
                            <tr>
                                <td style="min-height: 400px; padding: 15px; font-size: 13px;">
                                    {!! $content !!}
                                </td>
                            </tr>
                            <!--start space height -->
                            <tr>
                                <td height="28" style="border-collapse: collapse;"></td>
                            </tr>
                            <!--end space height -->
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- end layout-7 container -->
        </td>
    </tr>
    <!-- END MAIN CONTENT-->
    <!-- START FOOTER-BOX-->
    <tr>
        <td align="center" valign="top" class="fix-box" style="border-collapse: collapse;">
            <!-- start layout-7 container -->
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                   style="border-spacing: 0;">
                <tbody>
                <tr>
                    <td valign="top" style="border-collapse: collapse;">
                        <table width="800" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                               bgcolor="#3a3a3a" style="border-spacing: 0;">
                            <!--start space height -->
                            <tbody>
                            <tr>
                                <td height="20" style="border-collapse: collapse;"></td>
                            </tr>
                            <!--end space height -->
                            <tr>
                                <td valign="top" align="center" style="border-collapse: collapse;">
                                    <!-- start logo footer and address -->
                                    <table width="760" align="center" border="0" cellspacing="0" cellpadding="0"
                                           class="container" style="border-spacing: 0;">
                                        <tbody>
                                        <tr>
                                            <td valign="top" style="border-collapse: collapse;">
                                                <!--start icon socail navigation -->
                                                <table width="100%" border="0" align="center" cellpadding="0"
                                                       cellspacing="0" style="border-spacing: 0;">
                                                    <tbody>
                                                    <tr>
                                                        <td valign="top" align="center"
                                                            style="border-collapse: collapse;">
                                                            <table width="100%" border="0" align="left" cellpadding="0"
                                                                   cellspacing="0" class="full-width"
                                                                   style="border-spacing: 0;">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="left" valign="middle"
                                                                        class="clear-padding"
                                                                        style="border-collapse: collapse;">
                                                                        <table width="370" border="0" align="left"
                                                                               cellpadding="0" cellspacing="0"
                                                                               class="col-2" style="border-spacing: 0;">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td height="10"
                                                                                    style="border-collapse: collapse;"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size: 13px;line-height: 15px;font-family: Arial,Tahoma, Helvetica, sans-serif;color: #a7a9ac;font-weight: normal;text-align: left;border-collapse: collapse;">
                                                                                    Email: <a
                                                                                            href="mailto:{{ setting('contact_email') }}"
                                                                                            target="_blank"
                                                                                            style="text-decoration: none; color: #a7a9ac; font-weight: normal;">
                                                                                        {{ setting('contact_email') }}</a><br>
                                                                                    Phone: <a
                                                                                            href="tel:{{ setting('contact_email') }}"
                                                                                            target="_blank"
                                                                                            style="text-decoration: none; color: #a7a9ac; font-weight: normal;">
                                                                                        {{ setting('contact_phone') }}</a>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table width="370" border="0" align="left"
                                                                               cellpadding="0" cellspacing="0"
                                                                               class="col-2-last"
                                                                               style="border-spacing: 0;">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td align="right"
                                                                                    style="border-collapse: collapse;">
                                                                                    <table width="100%" border="0"
                                                                                           align="right" cellpadding="0"
                                                                                           cellspacing="0"
                                                                                           style="border-spacing: 0;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td height="10"
                                                                                                class="remove"
                                                                                                style="border-collapse: collapse;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td valign="mindle"
                                                                                                align="center"
                                                                                                style="border-collapse: collapse;">
                                                                                                <table border="0"
                                                                                                       align="right"
                                                                                                       cellpadding="0"
                                                                                                       cellspacing="0"
                                                                                                       class="clear-align"
                                                                                                       style="border-spacing: 0;">
                                                                                                    <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center"
                                                                                                            valign="middle"
                                                                                                            class="clear-padding"
                                                                                                            style="border-collapse: collapse;">
                                                                                                            <a href="{{ setting('twitter') }}"
                                                                                                               target="_blank">
                                                                                                                <img
                                                                                                                        src="https://gallery.mailchimp.com/7322fbb8c9fca82452c7533d9/images/48488f81-0a34-4d83-a891-e23f53510e7b.jpg"
                                                                                                                        width="36"
                                                                                                                        alt="icon-twitter"
                                                                                                                        style="max-width: 36px;display: block !important;height: auto !important;"
                                                                                                                        border="0"
                                                                                                                        hspace="0"
                                                                                                                        vspace="0">
                                                                                                            </a>
                                                                                                        </td>
                                                                                                        <td style="padding-left: 5px;border-collapse: collapse;"
                                                                                                            height="30"
                                                                                                            align="center"
                                                                                                            valign="middle"
                                                                                                            class="clear-padding">
                                                                                                            <a href="{{ setting('facebook') }}"
                                                                                                               target="_blank">
                                                                                                                <img
                                                                                                                        src="https://gallery.mailchimp.com/7322fbb8c9fca82452c7533d9/images/79f64b26-1868-48b0-85b4-dfaf29874af4.jpg"
                                                                                                                        width="37"
                                                                                                                        alt="icon-facebook"
                                                                                                                        style="max-width: 37px;display: block !important;height: auto !important;"
                                                                                                                        border="0"
                                                                                                                        hspace="0"
                                                                                                                        vspace="0">
                                                                                                            </a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <!--end icon socail navigation -->
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <!-- end logo footer and address -->
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <!--start space height -->
                                        <tr>
                                            <td height="20" style="border-collapse: collapse;"></td>
                                        </tr>
                                        <!--end space height -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- start space height -->
                            <tr>
                                <td height="10" valign="top" style="border-collapse: collapse;"></td>
                            </tr>
                            <!-- end space height -->
                            </tbody>
                        </table>
                        <!-- end layout-FOOTER-BOX container -->
                    </td>
                </tr>
                <!-- END FOOTER-BOX-->
                <!-- START FOOTER COPY RIGHT  -->
                <tr>
                    <td align="center" valign="top" class="fix-box" style="border-collapse: collapse;">
                        <!-- start layout-7 container -->
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                               style="border-spacing: 0;">
                            <!-- start space height -->
                            <tbody>
                            <tr>
                                <td height="5" valign="top" style="border-collapse: collapse;"></td>
                            </tr>
                            <!-- end space height -->
                            <tr>
                                <td align="center" valign="top" style="border-collapse: collapse;">
                                    <table width="800" align="center" border="0" cellspacing="0" cellpadding="0"
                                           class="container" style="border-spacing: 0;">
                                        <tbody>
                                        <tr>
                                            <td valign="top" align="center" style="border-collapse: collapse;">
                                                <table width="560" align="center" border="0" cellspacing="0"
                                                       cellpadding="0" class="container" style="border-spacing: 0;">
                                                    <tbody>
                                                    <tr>
                                                        <!-- start COPY RIGHT content -->
                                                        <td valign="top" align="center"
                                                            style="font-size: 11px;line-height: 22px;font-family: Arial,Tahoma, Helvetica, sans-serif;color: #919191;font-weight: normal;border-collapse: collapse;">
                                                            © Copyright {{ Carbon::now()->format('Y') }}, All rights reserved
                                                        </td>
                                                        <!-- end COPY RIGHT content -->
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!--  END FOOTER COPY RIGHT -->
                            <!-- start space height -->
                            <tr>
                                <td height="20" valign="top" style="border-collapse: collapse;"></td>
                            </tr>
                            <!-- end space height -->
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
</body>
</html>
