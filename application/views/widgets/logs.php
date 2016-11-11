<div id="widget-logs" class="widget">
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-file-text-o fa-fw "></i></span>
        <h2>Logs</h2>
        <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
    </header>
    <div class="widget-body"></div>
</div>
<script type="text/x-handlebars-template" id="log-tmpl">
    <li data-id="{{id}}">{{{logIcon}}}&nbsp;{{message}}<span>&nbsp;-&nbsp;{{datetime}}</span></li>
</script>