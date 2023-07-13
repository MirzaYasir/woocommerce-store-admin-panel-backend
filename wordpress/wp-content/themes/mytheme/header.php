<?php
global $wpdb;
$query = "SELECT b.id, b.name, COUNT(*) AS brand_count FROM mobiles m JOIN brands b ON m.brand_id = b.id GROUP BY b.id, b.name ORDER BY brand_count DESC LIMIT 10;";
$brands = $wpdb->get_results($query, ARRAY_A);

$cQuery = "SELECT id,name,symbol,selling_rate FROM currency_rates WHERE 1";
$currencies = $wpdb->get_results($cQuery, ARRAY_A);
?>
<script>
    jQuery(document).ready(function($) {
        $('.search-form-input').on('keyup', function() {
            var searchTerm = $(this).val();

            // Perform AJAX request or fetch request here
            // Example using jQuery AJAX:
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: 'POST',
                data: {
                    action: 'search_action',
                    searchTerm: searchTerm,
                    type: 'main_search'
                },
                success: function(response) {
                    // Handle the response from the server
                    console.log(response);
                }
            });
        });

        $(".search-brand-input").on('keyup', function() {
            // Example using fetch API:
            var searchTerm = $(this).val();
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: 'POST',
                data: {
                    action: 'search_action',
                    searchTerm: searchTerm,
                    type: 'brand_search'
                },
                success: function(response) {
                    // Handle the response from the server
                    $('#search-brand-list').empty();
                    if (response.status === 'success' && response.data.length > 0) {
                        response.data.forEach(function(brand) {
                            var brandLink = $('<a>')
                                .addClass('list-group-item')
                                .attr('href', '<?php echo $base_url = home_url() . "/"; ?>brands/' + brand.id)
                                .text(brand.name);

                            var listItem = $('<li>').append(brandLink);
                            $('#search-brand-list').append(listItem);
                        });
                    }
                }
            });
        });
    });
</script>
<nav class="navbar navbar-expand-lg" style="background: #48afff">
    <div class="container-fluid">
        <div style="margin-right: calc(30px / 3)">
            <a on="tap:sidebar-left.toggle" class="humburger-icon">
                <amp-img src="<?php echo get_template_directory_uri() . '/assets/bar.svg'; ?>" class="i-amphtml-element i-amphtml-layout-fixed i-amphtml-layout-size-defined i-amphtml-built i-amphtml-layout" alt="bar" width="28px" height="28px" layout="fixed" i-amphtml-layout="fixed" style="width: 28px; height: 28px;">
                    <img decoding="async" alt="bar" src="<?php echo get_template_directory_uri() . '/assets/bar.svg'; ?>" class="i-amphtml-fill-content i-amphtml-replaced-content">
                </amp-img>
            </a>
        </div>
        <a class="navbar-brand" href="#" style="font-weight: 700; font-size: 30px; color: white;">Pakmallz</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">

            <div class="search-form-outer">
                <form class="search-form">
                    <input class="form-control search-form-input" placeholder="search...">
                    <a href="#" class="search-icon">
                        <amp-img src="<?php echo get_template_directory_uri() . '/assets/search.svg'; ?>" height="20">
                            <img src="<?php echo get_template_directory_uri() . '/assets/search.svg'; ?>" height="20" />
                        </amp-img>
                    </a>
                </form>

                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="padding-left: 50px;">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Brands
                        </a>
                        <ul class="dropdown-menu brands-dropdown-menu">
                            <div style="padding-left: 15px; padding-right: 15px;"><input type="text" name="value" class="form-control search-brand-input" placeholder="search..." /></div>
                            <ul id="search-brand-list" class="list-group"></ul>
                            <?php if (count($brands) > 0) {
                                foreach ($brands as $brand) { ?>
                                    <li><a class="dropdown-item" href='<?php echo $base_url = home_url() . "/"; ?>brands/<?php echo $brand["id"]; ?>'><?php echo $brand["name"]; ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Currencies
                        </a>
                        <ul class="dropdown-menu currencies-dropdown-menu">
                            <?php if (count($currencies) > 0) {
                                foreach ($currencies as $curr) { ?>
                                    <li><a class="dropdown-item" href='#'><?php echo $curr["name"] . " ". $curr["symbol"]; ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </li>
                </ul>
            </div>



            <div id="p-login " class="btn-signup  btn-login" amp-access="NOT loggedIn">
                <a href="#" class="login-btn p3">
                    <span style="color: #48afff;">
                        Log in
                    </span>
                </a>
            </div>

            <div id="p-register " class="btn-signup" amp-access="NOT loggedIn">
                <a href="#" class="login-btn p3">
                    <span amp-access-hide="">
                        Register
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>