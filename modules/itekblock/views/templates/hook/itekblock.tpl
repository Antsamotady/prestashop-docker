{if $elements && $page.page_name != 'authentication'}    
    <div id="itkblock-{$hook|lower}" class="itkblock-container">
        <div class="container">
            <ul class="itkblock-list">
                {foreach from=$elements item=element}
                    {if $hook|lower == 'header'}
                        <li class="itkblock-list__item">                            
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.title|strip_tags}" class="itkblock-element">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.title|strip_tags}" class="image img-fluid"/>
                                </div>
                            {/if}
                            <div class="itkblock-element__description">{$element.description|nl2br nofilter}</div>
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li>
                    {elseif $hook|lower == 'right'}
                        <a href="{$element.link}" title="{$element.title|strip_tags}" class="itkblock-element">
                            {$shop.address.address1}, <br>
                            {$shop.address.postcode} {$shop.address.city} - {$shop.phone}
                        </a>
                        {* <li class="itkblock-list__item">
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.title|strip_tags}" class="itkblock-element">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.title|strip_tags}" class="image img-fluid"/>
                                </div>
                            {/if}
                            <div class="itkblock-content">
                                <div class="itkblock-element__title">{$element.title nofilter}</div>
                                <div class="itkblock-element__description">{$element.description|nl2br nofilter}</div>
                            </div>
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li> *}
                    {elseif $hook|lower == 'seo'}
                        <li class="itkblock-list__item">                            
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.title|strip_tags}" class="itkblock-element">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.title|strip_tags}" class="itkblock-image img-fluid"/>
                                </div>
                            {/if}
                            <div class="itkblock-content">
                                <div class="itkblock-element__description">{$element.description|nl2br nofilter}</div>
                                {if $element.is_btn}
                                    <a href="{$element.link}" class="btn btn-default btn-reassurance itkblock-btn" role="button">{$element.txt_btn|escape:'quotes'}</a>
                                {/if}
                            </div>
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li>
                    {elseif $hook|lower == 'pub'}
                        <li class="itkblock-list__item">                            
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.txt_btn}" class="itkblock-element">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.title|strip_tags}" class="itkblock-image img-fluid"/>
                                </div>
                            {/if}
                            <div class="itkblock-element__content">
                                {if $element.is_btn}
                                    <span class="btn btn-default btn-reassurance itkblock-btn" role="button">{$element.txt_btn}</span>
                                {/if}
                            </div>
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li>
                    {elseif $hook|lower == 'middle'}
                        <li class="itkblock-list__item">                            
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.description|nl2br|strip_tags:false}" class="itkblock-element">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.description|nl2br|strip_tags:false}" class="itkblock-image img-fluid"/>
                                </div>
                            {/if}
                            <div class="itkblock-element__content">
                                <div class="itkblock-element__description">{$element.description|nl2br nofilter}</div>
                                {if $element.is_btn}
                                    <span class="btn btn-default btn-reassurance itkblock-btn" role="button">{$element.txt_btn|escape:'quotes'}</span>
                                {/if}
                            </div>
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li>
                    {elseif $hook|lower == 'ars'}
                        <li class="itkblock-list__item">                            
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.title|strip_tags}" class="itkblock-element" target="_blank">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.title|strip_tags}" class="itkblock-image img-fluid"/>
                                </div>
                            {/if}
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li>
                    {else}
                        <li class="itkblock-list__item">                            
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                <a href="{$element.link}" title="{$element.title|strip_tags}" class="itkblock-element">
                            {else}
                                <div class="itkblock-element">
                            {/if} 
                            {if $element.image != 'undefined' && $element.image != ''}
                                <div class="itkblock-element__image">
                                    <img src="{$element.image}" alt="{$element.title|strip_tags}" class="itkblock-image img-fluid"/>
                                </div>
                            {/if}
                            {if Configuration::get('ITEK_CONFIGURATION_PROMO_NL_TYPE') == 0}
                                {assign var="promoNl" value=Configuration::get('ITEK_CONFIGURATION_PROMO_NL_VALUE')|cat:"€"}
                            {else}
                                {assign var="promoNl" value=Configuration::get('ITEK_CONFIGURATION_PROMO_NL_VALUE')|cat:"%"}
                            {/if}
                            <div class="itkblock-element__content">
                                <div class="itkblock-element__title">{$element.title nofilter}</div>
                                <div class="itkblock-element__description">{$element.description|nl2br|replace:"[[promo_newsletter]]": $promoNl nofilter}</div>
                                {if $element.is_btn}
                                    <span class="btn btn-default btn-reassurance itkblock-btn" role="button">{$element.txt_btn|escape:'quotes'}</span>
                                {/if}
                            </div>
                            {if $element.type_link == ItekblockObject::TYPE_LINK_CMS_PAGE || $element.type_link == ItekblockObject::TYPE_LINK_URL}
                                </a>
                            {else}
                                </div>
                            {/if}
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
{/if}
