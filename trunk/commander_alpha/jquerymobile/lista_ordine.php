
<section id="contentList">

    <div id="list-ord_nav" class="list-ord_nav">
        <section id="options" class="clearfix">
            <div class="option-combo">
                <ul id="sort" class="option-set_list clearfix" data-option-key="sortBy">
                    <li><a href="#categorie" data-option-value="cat" class="selected">Categorie</a></li>
                    <li><a href="#alphabetical" data-option-value="nome">Alfabetico</a></li>
                </ul>
            </div>
        </section>
    </div>

    <div id="list-ord" class="list-ord">
        <div id="container2"></div>
        <ul class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-dividertheme="b" data-theme="c" data-inset="true" data-role="listview"></ul>
    </div>

    <div id="list-ord_footer" class="list-ord_footer">
        <ul class="ui-listview" data-role="listview" style="margin: 0px">
        <li class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="totale">
            <h2 class="name">Totale:</h2>
            <h2 class="prezzo">0 €</h2>
            </div>
        </li>
        </ul>
        <a href="#chiusura" data-role="button" data-icon="check" class="ui-btn-right">Conferma ordine</a>
    </div>


    <script type="text/javascript">
        var h_nav = document.getElementById("list-ord_nav").style.height;
        var h_foo = document.getElementById("list-ord_footer").style.height;
        document.getElementById("list-ord").style.height = (window.innerHeight-250) - h_nav - h_foo + "px";
    </script>

</section> <!-- #contentList -->