<?php
function generate_pdf($data, $insurance)
{
    ob_start();
?>
    <div>
        <table>
            <tbody>
                <tr>
                    <td align="center">
                        <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode(file_get_contents(WC_INSURANCE_DIR . './assets/icons/logo.jpg')) ?>" height="40px" />
                    </td>
                    <td align="center" colspan="3">
                        <strong>CERTIFICATE OF LIABILITY INSURANCE</strong><br />
                        Integrity Business Insurance
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <h2> CONTACT INFORMATION</h2>
        <table style="margin:10px">
            <tbody>
                <tr>
                    <td align="LEFT">
                        <strong>CONTACT NAME:</strong>
                    </td>
                    <td align="right">
                        <?php echo $data["contact_name"] ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>PHONE NUMBER:</strong>
                    </td>
                    <td align="right">
                        <?php echo $data["contact_phone"] ?>
                    </td>
                </tr>

                <tr>
                    <td align="LEFT">
                        <strong>EMAIL ADDRESS:</strong>
                    </td>
                    <td align="right">
                        <?php echo $data["contact_email"] ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>FAX NUMBER:</strong>
                    </td>
                    <td align="right">
                        <?php echo $data["fax"] ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2> BUSINESS INFORMATION</h2>
        <table style="margin:10px">
            <tbody>
                <tr>
                    <td align="left">
                        <strong>NAICS CLASS:</strong>
                    </td>
                    <td align="right">
                        <?php
                        global $naics_list;
                        echo $naics_list[$data["naics_list"]] ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>INSURANCE INFORMATION</h2>
        <?php $insurance->renderHTMLTable() ?>
    </div>
<?php
    $html = ob_get_clean();
    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->addPage();
    $pdf->setPrintFooter();
    $pdf->WriteHTML($html);
    return $pdf;
}
?>
