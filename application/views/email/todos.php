<div style="background-color: #f3f3f3; padding: 30px">
    <div style="background: #474544; border: 1px solid #CCC;">
        <h1 style="margin: 0; padding: 15px; color: white;">EcoSystems - Todo's</h1>
    </div>
    <div style="background: white; padding: 30px; border: 1px solid #CCC;">
        <table style="border-spacing: 0; padding: 0; list-style: disc;">
            <?php foreach($todos as $todo) { ?>
                <tr style="border-bottom: 1px solid #e7e7e7;">
                    <td style="padding: 0;">
                        <span style="display: block; width: 20px; margin: 10px;">
                            <span style="display: block; border: 1px solid black; width: 10px; height: 10px;"></span>
                        </span>
                    </td>
                    <td style="padding: 0;">
                        <span style="display: block; padding: 10px; border-left: 3px double #FFE1EB;">
                            <?php echo $todo['title']; ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <p>Click <a href="<?php echo $base_url ?>" target="_blank">here</a> to check those todo's</p>
    </div>
</div>