<script type="text/javascript">
    $(document).ready(function () {
        {block name="autoload_tinyMCE"}
        function setupTinyMCE(editor) {
            editor.on('change', function (e) {
                $('textarea#' + editor.id).html(editor.getContent());
            });
        }

        tinySetup({
            editor_selector: "autoload_rte",
            setup: setupTinyMCE
        });
        {/block}
    });
</script>

<div id="blockDisplay" class="panel panel-default col-lg-10 col-xs-10 col-lg-offset-1 col-xs-offset-1 inactive">
    <div class="panel-heading">
        {l s='Block' d='Modules.Itekblock.Admin'}
    </div>
    <form class="form_add" method="post" ENCTYPE="multipart/form-data">
        <div class="panel-body-0 panel-body show-rea-block inactive">
            {assign var="block" value=null}
            {* icon *}
            {include file="./config_elements/icon.tpl"}
            {* language *}
            {include file="./config_elements/language.tpl"}
            {foreach from=$languages item=language}
                {* title *}
                {include file="./config_elements/title.tpl"}
                {* description *}
                {include file="./config_elements/description.tpl"}
            {/foreach}
            {* redirection *}
            {include file="./config_elements/redirection.tpl"}
            {* CMS *}
            {include file="./config_elements/cms.tpl"}
            {* URL *}
            {include file="./config_elements/url.tpl"}
            {* Type button *}
            {include file="./config_elements/btn.tpl"}
            {* hook *}
            {include file="./config_elements/hook.tpl"}
        </div>
    </form>
    {foreach from=$allblock item=$block key=$key}
        <form class="form_{$block['id_itekblock']}" method="post" ENCTYPE="multipart/form-data">
            <div class="panel-body-{$block['id_itekblock']} panel-body show-rea-block inactive">
                {if $block['hook'] != 2 && $block['hook'] != 5}
                    {* icon *}
                    {include file="./config_elements/icon.tpl"}
                {/if}

                {* language *}
                {include file="./config_elements/language.tpl"}

                {foreach from=$languages item=language}
                    {* title *}
                    {include file="./config_elements/title.tpl"}

                    {* description *}
                    {include file="./config_elements/description.tpl"}
                {/foreach}

                {* redirection *}
                {include file="./config_elements/redirection.tpl"}

                {* CMS *}
                {include file="./config_elements/cms.tpl"}

                {if $block['hook'] != 1 && $block['hook'] != 2 && $block['hook'] != 5}
                    {* Type button *}
                    {include file="./config_elements/btn.tpl"}
                {/if}
                {if $block['hook'] != 2 && $block['hook'] != 5}
                    {* URL *}
                    {include file="./config_elements/url.tpl"}
                {/if}

                {* hook *}
                {include file="./config_elements/hook.tpl"}
            </div>
        </form>
   {/foreach}
    
    <div class="panel-footer">
        <div class="col-xs-6 text-left">
            <input name="refreshPage" type="submit" class="btn btn-primary refreshPage"
                   value="{l s='Return' d='Modules.Itekblock.Admin'}">
        </div>
        <div class="col-xs-6 text-right">
            <input name="saveContentConfiguration" id="saveContentConfiguration" data-id="" type="submit"
                   class="btn btn-primary" value="{l s='Save' d='Modules.Itekblock.Admin'}">
        </div>
    </div>
</div>
