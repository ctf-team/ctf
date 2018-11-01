<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="c-table-responsive@wide">
                <table class="c-table">
                    <thead class="c-table__head">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head">First Name</th>
                        <th class="c-table__cell c-table__cell--head">Last Name</th>
                        <th class="c-table__cell c-table__cell--head">Email</th>
                        <th class="c-table__cell c-table__cell--head">Rank</th>
                    </tr>
                    </thead>

                    <tbody id="usersList">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <footer class="c-footer">
                <p>© 2018 Neat, Inc</p>
                <span class="c-footer__divider">|</span>
                <nav>
                    <a class="c-footer__link" href="#">Terms</a>
                    <a class="c-footer__link" href="#">Privacy</a>
                    <a class="c-footer__link" href="#">FAQ</a>
                    <a class="c-footer__link" href="#">Help</a>
                </nav>
            </footer>
        </div>
    </div>
</div>
<script>
    // Populate the list of users.
    jQuery(function() {
        jQuery.post("<?php echo $this->getURL('/userlist_ajax'); ?>", { fields: ["firstname", "lastname", "email", "rank"] }, function(data) {
            // populate tr.
            data = JSON.parse(data);
            data = data.data;
            for (i = 0; i < data.length; i++) {
                jQuery("#usersList").append('<tr class="c-table__row" id="t'+i+'"></tr>');
                var ndata = Object.values(data[i]);
                for(j = 0; j < ndata.length; j++) {
                    jQuery("#usersList tr#t"+i).append('<td class="c-table__cell">'+ndata[j]+'</td>')
                }
            }
        });
    });
</script>