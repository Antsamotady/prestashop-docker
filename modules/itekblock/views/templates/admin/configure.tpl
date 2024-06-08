<div class="bootstrap" id="itkblock_configuration">
    <!-- Module content -->
    <div id="modulecontent" class="clearfix">
        <!-- Centered Content header block -->
       <!-- Centered Content header block -->
        <div class="content-header text-center">
            <h1 class="text-center">Itekblock</h1>
            <p class="text-center" style="font-size: 16px;">
            Personnalisez l'apparence de votre contenu en choisissant l'emplacement idéal à l'aide du hook intégré.

                <br><br>

                <strong>Titre:</strong> Ajoutez un titre distinctif à votre bloc.
                <br>
                <strong>Description:</strong> Fournissez une description captivante pour expliquer le contenu du bloc.
                <br>
                <strong>Hook:</strong> Choisissez judicieusement l'emplacement de votre bloc en sélectionnant le hook correspondant. Cela détermine où le bloc apparaîtra sur votre page.
            </p>
        </div>
        <div class="bootstrap">
            <div class="page-head custom-tab">
                <div class="page-head-tabs" id="head_tabs">
                    <ul class="nav">
                        <li class="active">
                            <a href="#pscontent" data-toggle="tab">{l s='Content' d='Modules.Itekblock.Admin'}</a>
                        </li>
                        <li>
                            <a href="#display" data-toggle="tab">{l s='hook' d='Modules.Itekblock.Admin'}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Tab panes -->
        <div class="tab-content row">
            {if !$folderIsWritable}
                {include file="./alert_folder_writable.tpl"}
            {/if}
            <div class="tab-pane active" id="pscontent">
                <div class="tab_cap_listing">
                    {include file="./tabs/content.tpl"}
                </div>
            </div>
            <div class="tab-pane" id="display">
                <div class="tab_cap_listing">
                    {include file="./tabs/display.tpl"}
                </div>
            </div>
        </div>
    </div>
</div>
