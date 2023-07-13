<?php
/*
Template Name: Detail Page
*/

// Add your custom code for the detail page here
// You can access the URL parameter using $_GET['id'], for example:
$product_id = $_GET['id'];
global $wpdb;
$param_name = $name;
$settings = $wpdb->get_results("SELECT * FROM settings WHERE 1");
$specification_groups = $wpdb->get_results("SELECT *, '' AS details FROM specification_groups WHERE 1");

$nameArray = $wpdb->get_results("SELECT name, website_id FROM mobiles WHERE id = $product_id");
$website_id = 1;

if (count($nameArray) > 0) {
    $name = $nameArray[0]->name;
    $website_id = $nameArray[0]->website_id;
}
$sql = "SELECT GROUP_CONCAT(id) AS ids, GROUP_CONCAT(thumbnail) AS thumbnails FROM (SELECT * FROM mobiles WHERE name LIKE '%$name%' GROUP BY website_id) testtable";
$temp = $wpdb->get_results($sql);
$mobile_ids = 0;
$thumbnails = null;
if (count($temp) > 0) {
    $mobile_ids = $temp[0]->ids;
    $thumbnails = explode(",", $temp[0]->thumbnails);
}
$sql = "SELECT m.name AS mobile_name, m.thumbnail, s.*, CONCAT (s.name, '_', s.value) AS Group1 FROM mobiles m LEFT JOIN specifications s ON m.id = s.mobile_id LEFT JOIN specification_groups sg ON sg.id = s.specification_group_id WHERE m.id IN ($mobile_ids) AND m.deleted_at IS NULL GROUP BY Group1";
$sql2 = "SELECT id, filename, url, item_id FROM attachments WHERE item_id IN ($mobile_ids) AND deleted_at IS NULL";
$result = $wpdb->get_results($sql);
$attachments = $wpdb->get_results($sql2);
if (count($attachments) === 0) {
    foreach ($thumbnails as $nail) {
        $obj = new stdClass();
        $obj->id = null;
        $obj->filename = basename($nail);
        $obj->url = $nail;
        $obj->item_id = $product_id;
        $attachments[] = $obj;
    }
}

$prominents = [];
$hits = 0;
$res = $wpdb->get_results("SELECT id, name, value, icon, is_prominent FROM specifications WHERE mobile_id IN ($mobile_ids) AND is_prominent = 1 AND deleted_at IS NULL AND name != '' GROUP BY name");
if ($res) {
    foreach ($res as $key => $r) {
        if ($r->name == 'MP' || $r->name == 'GB RAM' || $r->name == 'mAh') {
            $previous_id = intval($r->id) - 1;
            $n = $wpdb->results("SELECT name FROM specifications WHERE id = '$previous_id'");
            $res[$key]->name = $n . " " . $res[$key]->name;

            foreach ($res as $k => $rr) {
                if ($rr->id == $previous_id) {
                    unset($res[$k]);
                    break;
                }
            }
        }
    }
}
foreach ($res as $kk => $rr) {
    if ($rr->name == 'Become a fan') {
        unset($res[$kk]);
        break;
    }
}
foreach ($res as $kkk => $rrr) {
    if (strpos($rrr->name, 'hits') !== false) {
        $hits = $rrr->name;
        unset($res[$kkk]);
        break;
    }
}
$prominents = $res;

$sql3 = "SELECT m.price_rs, m.price_usd, m.expected_price, w.name AS brand_name, m.website_id, m.price_on_gsmerena FROM mobiles m INNER JOIN websites w ON m.website_id = w.id WHERE m.name LIKE '%$name%' GROUP BY w.id";
$tempArray = $wpdb->get_results("SELECT description, new_description FROM mobiles WHERE name LIKE '%$name%'");
$description = count($tempArray) > 0 ? $tempArray[count($tempArray) - 1]->description : null;
$new_description = count($tempArray) > 0 ? $tempArray[count($tempArray) - 1]->new_description : null;
if ($new_description != null) {
    $description = base64_encode($new_description);
}
$prices = $wpdb->get_results($sql3);
$hasSpecification = false;
$selling_rate = 0;
$currencies = $wpdb->get_results("SELECT id,name,symbol,selling_rate FROM currency_rates WHERE 1", ARRAY_A);
foreach ($currencies as $cur) {
    if ($cur['symbol'] == '€') {
        $selling_rate = $cur['selling_rate'];
        break;
    }
}

foreach ($specification_groups as $group) {
    $group->details = [];
    foreach ($result as $res) {
        if ($res->specification_group_id == $group->id) {
            array_push($group->details, $res);
            if ($website_id == 3) {
                if ($res->name == 'Price') {
                    foreach ($prices as $pr) {
                        if ($pr->brand_name == 'gsmarena') {
                            $price_in_eur = (int) ltrim($res->value, 'A..z: ');
                            $pr->price_rs = $price_in_eur * $selling_rate;
                        }
                    }
                }
            }
            if (!$hasSpecification) {
                if ($res != null || !empty($res)) {
                    $hasSpecification = true;
                }
            }
        }
    }
}

foreach ($prices as $prr) {
    if ($prr->brand_name == 'gsmarena') {
        $price_in_eur = (int) ltrim($prr->price_on_gsmerena, 'A..z: ');
        $prr->price_rs = $price_in_eur * $selling_rate;
        break;
    }
}

// $data = [];
// $data["prices"] = $prices;
// $data["attachments"] = $attachments;
$generals = $specification_groups;
// $data["prominents"] = $prominents;
// $data["item_name"] = $name;
// $data["item_id"] = $product_id;
// $data["description"] = $description;
// $data["currencies"] = $currencies;
// $data["hasSecifications"] = $hasSpecification;
$reviews = $wpdb->get_results("SELECT * FROM reviews WHERE item_id IN ($mobile_ids) AND deleted_at IS NULL");
//$canonical_url = get_permalink($product_id) . "/$param_name";
//$data["hits"] = $hits;


?>

<html>

<head>
    <title>Pakmallz</title>
    <?php wp_head(); ?>
</head>

<body style="background-color: #f1f3f6;">
    <?php get_header(); ?>
    <div class="container-fluid" style="background: white;">
        <div class="container d-flex justify-content-center align-items-start flex-column p-2" style="font-size: 12px; font-weight: 300;">
            <div style="font-weight: 300 !important;">
                <a href="#" style="text-decoration: none; color: rgba(7,18,27,.4);">Home</a>
                <span style="color: rgba(7,18,27,.4);">&#x21d2;</span>
                <a href="#" style="text-decoration: none; color: rgba(7,18,27,.4);"><?php echo $name; ?></a>
            </div>
            <p class="mb-0"><?php echo $name; ?> prices in Pakistan</p>
        </div>
    </div>
    <div class="container">
        <div class="col-md-12">
            <div class="row mt-3">
                <div class="col-md-5">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($attachments as $key => $attch) { ?>
                                <div class="carousel-item <?php if ($key == 0) {
                                                                echo 'active';
                                                            } ?>">
                                    <div class="image-container">
                                        <a href="<?php echo get_template_directory_uri() . '/' . $attch->url; ?>" data-src="<?php echo get_template_directory_uri() . '/' . $attch->url; ?>">
                                            <img src="<?php echo get_template_directory_uri() . '/' . $attch->url; ?>" alt="Image <?php echo $key + 1; ?>">
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Controls -->
                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
                <div class="col-md-7">
                    <h2 style="font-size: 20px !important; font-weight: 600;"><?php echo $name; ?></h2>
                </div>
            </div>
        </div>




        <div class="container mt-3">
            <?php if ($description != null || !empty($description)) { ?>
                <div class="col-md-12 bg-white mb-2" style="padding: 25px !important; font-size: 12px !important;">
                    <div class"row"">
                        <h3 style="font-size: 18px !important; font-weight: 600;">Description:</h3>
                        <?php echo strip_tags(base64_decode($description)); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-12 bg-white mb-2" style="padding: 25px !important; font-size: 12px !important;">
                <h3 style="font-size: 18px !important; font-weight: 600; text-decoration: underline;">Specifications</h3>
                <?php foreach ($generals as $gen) {
                    if (count($gen->details) > 0) { ?>
                        <h3 style="font-size: 15px !important; font-weight: 600;"><?php echo $gen->name; ?></h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="font-size: 12px !important;">
                                <tbody>
                                    <?php foreach ($gen->details as $d) { ?>
                                        <tr>
                                            <th style="<?php if (!is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'))) {
                                                            echo 'width: 130px !important;';
                                                        } ?>""><?php echo $d->name; ?></th>
                                                <td style=" font-weight: 300 !important;"><?php echo $d->value; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>



    </div>

    <?php get_footer(); ?>
    <?php wp_footer(); ?>
</body>

</html>