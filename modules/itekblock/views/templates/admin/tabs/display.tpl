<div id="modulecontent module_display" class="clearfix">
    <div class="panel panel-default col-lg-10 col-lg-offset-1 col-xs-12 col-xs-offset-0">
        <div class="panel-heading">
            {l s='Liste des hooks' d='Modules.Blockreassurance.Admin'}
        </div>
        <div class="panel-body">
            <div class="item-hook">
                <div class="position">
                    Reassurance
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/reassurance.png" alt="Reassurance Image" class="img-responsive"/>
                    </a>
                    displayBlockReassurance
                </div>
            </div>
            <div id="item-hook" class="item-hook">
                <div class="position">
                    Header
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/header.png" alt="Header Image" class="img-responsive"/>
                    </a>
                    displayBlockHeader
                </div>
            </div>
             <div id="item-hook" class="item-hook">
                <div class="position">
                    Right
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/Right.png" alt="Right Image" class="img-responsive"/>
                    </a>
                    displayBlockRight
                </div>
            </div>
            <div class="item-hook">
                <div class="position">
                    Middle
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/middle.png" alt="Middle Image" class="img-responsive"/>
                    </a>
                    displayBlockMiddle
                </div>
            </div>
            <div class="item-hook">
                <div class="position">
                    Newsletter
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/newsletter.png" alt="Newsletter Image" class="img-responsive"/>
                    </a>
                    displayBlockNewsletter
                </div>
            </div>
            <div class="item-hook">
                <div class="position">
                    Seo
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/SEO.png" alt="SEO Image" class="img-responsive"/>
                    </a>
                    displayBlockSeo                  
                </div>
            </div>
            <div class="item-hook">
                <div class="position">
                   Pub
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                        <img src="/modules/itekblock/views/img/Essentials/Pub.png" alt="Pub Image" class="img-responsive"/>
                    </a>
                    displayBlockPub                  
                </div>
            </div>
            <div class="item-hook">
                <div class="position">
                Ars
                </div>
                <div class="hook">
                    <a href="#" data-toggle="modal" data-target="#genericModal" class="modal-trigger">
                         <img src="/modules/itekblock/views/img/Essentials/Ars.png" alt="Ars Image" class="img-responsive"/>
                    </a>
                    displayBlockArs                 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generic Modal -->
<div class="modal fade" id="genericModal" tabindex="-1" role="dialog" aria-labelledby="genericModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="genericModalLabel">Image Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" alt="" id="modalImage" class="img-responsive"/>
      </div>
    </div>
  </div>
</div>

{literal}
<script type="text/javascript">
    var currentPage = "{/literal}{$currentPage|escape:'htmlall':'UTF-8'}{literal}";
    var moduleAdminLink = "{/literal}{$moduleAdminLink|escape:'htmlall':'UTF-8'}{literal}";
    $(document).ready(function(){
        $('.modal-trigger').click(function() {
            var imageSrc = $(this).find('img').attr('src');
            var imageAlt = $(this).find('img').attr('alt');
            
            // Update the modal image
            $('#modalImage').attr('src', imageSrc);
            $('#modalImage').attr('alt', imageAlt);
            
            // Update the modal title
            $('#genericModalLabel').text(imageAlt);
        });
    });
</script>
{/literal}
