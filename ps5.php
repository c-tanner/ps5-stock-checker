<?php

    function checkStock($name, $url, $delimiter, $elm, $text) {

        echo "\033[37mChecking $name.. ";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "Cookie: session-id=147-0104658-0536114; session-id-time=2082787201l; i18n-prefs=USD"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (strpos($response, $delimiter) !== FALSE) {

            $check = explode("</$elm>", explode($delimiter, $response)[1])[0];
            if (strpos($check, $text) !== FALSE) {
                echo "\033[31mUnavailable.\n";
            } else {
                echo "\033[32mIn Stock! $url\n";
                echo $check."\n";
            }

        } else {

            echo "\033[33mDelimiter text not found in response!\n";

            echo $response;

        }

        // Return terminal to default color
        echo "\033[39m";

    }

    $stores = [
        "Amazon" => [
            "url"       => "https://www.amazon.com/PlayStation-5-Console/dp/B08FC5L3RG",
            "delimiter" => "a-size-medium a-color-price",
            "elm"       => "span",
            "text"      => "Currently unavailable."
        ],
        "Walmart" => [
            "url"       => "https://www.walmart.com/ip/PlayStation5-Console/363472942",
            "delimiter" => "prod-ProductOffer-oosMsg prod-PaddingTop--xxs",
            "elm"       => "div",
            "text"      => "Out of stock"
        ],
        "Gamestop" => [
            "url"       => "https://www.gamestop.com/video-games/playstation-5/consoles/products/playstation-5/11108140.html",
            "delimiter" => "add-to-cart btn btn-primary",
            "elm"       => "button",
            "text"      => "Not Available"
        ],
        "Best Buy" => [
            "url"       => "https://www.bestbuy.com/site/sony-playstation-5-console/6426149.p?skuId=6426149",
            "delimiter" => "add-to-cart-button",
            "elm"       => "button",
            "text"      => "Coming Soon"
        ]
    ];

    foreach ($stores as $k => $v) {
        checkStock($k, $v['url'], $v['delimiter'], $v['elm'], $v['text']);
    }