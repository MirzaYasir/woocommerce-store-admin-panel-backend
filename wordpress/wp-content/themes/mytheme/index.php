<html>

<head>
    <title>Pakmallz</title>
    <?php wp_head(); ?>
</head>

<body style="background-color: #f1f3f6;">
    <?php get_header(); ?>

    <div class="container">
        <?php

        global $wpdb;
        $selected_currency = [["symbol" => "Pkr.", "selling_rate" => 1]];

        $latestItemQuery = "SELECT mobiles.id, mobiles.name, mobiles.description, mobiles.price_rs, mobiles.expected_price, mobiles.rating, attachments.url as thumbnail, mobiles.is_latest FROM mobiles AS mobiles LEFT JOIN attachments AS attachments ON mobiles.id = attachments.item_id WHERE mobiles.is_home = 1 AND mobiles.deleted_at IS NULL AND attachments.url != '' AND attachments.url IS NOT NULL ORDER BY mobiles.rating DESC LIMIT 54";
        $data["latest"] = $wpdb->get_results($latestItemQuery, ARRAY_A);

        $upCommingQuery = "SELECT mobiles.id, mobiles.name, mobiles.description, mobiles.price_rs, mobiles.expected_price, mobiles.rating, attachments.url as thumbnail, mobiles.is_latest FROM mobiles AS mobiles LEFT JOIN attachments AS attachments ON mobiles.id = attachments.item_id WHERE mobiles.price_rs = -1 AND mobiles.deleted_at IS NULL AND mobiles.description != '' AND attachments.url != '' AND attachments.url IS NOT NULL ORDER BY mobiles.rating DESC LIMIT 10";

        $trendingQuery = "SELECT m.id, m.name, m.description, m.price_rs, m.expected_price, m.rating, m.is_latest, att.url as thumbnail FROM most_viewed AS mv INNER JOIN mobiles AS m ON mv.mobile_id = m.id LEFT JOIN attachments AS att ON m.id = att.item_id WHERE m.deleted_at IS NULL AND m.description != '' AND att.url != '' AND att.url IS NOT NULL ORDER BY mv.number_of_views DESC LIMIT 10";

        $data['trending'] = $wpdb->get_results($trendingQuery, ARRAY_A);
        $data['upcoming'] = $wpdb->get_results($upCommingQuery, ARRAY_A);

        $mainHTMLContent = [];
        if (count($data["latest"]) > 0) {
            $count = 0;
            foreach ($data["latest"] as $item) {
                if ($count % 6 == 0) {
                    $mainHTMLContent[] = '<div class="row mt-3 mb-3" style="gap: 15px;">';
                }

                $html = '<a href="'. home_url("/mobile-detail/").'?id='.$item["id"].'" class="col d-flex flex-column align-items-center justify-content-center p-2 mobile-card" style="background: white; border-radius: 0px; text-decoration: none; color: black;">';
                $html .= '<img class="img-mobile lazyload" src="'.get_template_directory_uri().'/assets/lazy-loading/assets/imgs/loader.gif" data-src="' . get_template_directory_uri() . '/' . $item["thumbnail"] . '" alt="Image Description">';
                $html .= '<p class="truncate mt-2 mb-0">' . base64_decode($item['description']) . '</p>';
                $html .= '<h4 class="mb-0">' . $item["name"] . '</h4>';

                if ($item['price_rs'] == -1) {
                    if ($item['expected_price'] == 0) {
                        $html .= '<div class="product-price">%s NaN</div>';
                    } else {
                        $html .= '<div class="product-price">%s %s (Exp)</div>';
                    }
                } else if ($item['price_rs'] == -2) {
                    $html .= '<small class="text-red"> Disconnected</small>';
                } else {
                    $html .= '<div class="product-price">%s %s</div>';
                }

                $html .= '</a>';

                if ($item['price_rs'] == -1) {
                    $currency = (count($selected_currency) > 0) ? $selected_currency[0]['symbol'] : 'Pkr';
                    $price = ($item['expected_price'] == 0) ? 'NaN' : number_format($item['expected_price'] / $selected_currency[0]['selling_rate'], 2);
                    $mainHTMLContent[] = sprintf($html, $currency, $price);
                } else if ($item['price_rs'] == -2) {
                    $mainHTMLContent[] = $html;
                } else {
                    $currency = (count($selected_currency) > 0) ? $selected_currency[0]['symbol'] : 'Pkr';
                    $price = number_format($item['price_rs'] / $selected_currency[0]['selling_rate'], 2);
                    $mainHTMLContent[] = sprintf($html, $currency, $price);
                }

                $count++;

                
                if ($count % 6 == 0) {
                    $mainHTMLContent[] = '</div>'; // Close the row after every 6 records
                }
            }

            if ($count % 6 != 0) {
                $mainHTMLContent[] = '</div>'; // Close the row if the total count is not divisible by 6
            }
        }

        echo implode('', $mainHTMLContent);

        ?>

    </div>
    </div>

    <?php get_footer(); ?>
    <?php wp_footer(); ?>
</body>

</html>