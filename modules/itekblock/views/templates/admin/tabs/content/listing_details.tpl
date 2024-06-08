 <div class="listing-general-rol row" data-block="{$block.id_itekblock}">
                            <div class="listing-row row">
                                <div class="col-xs-1">
                                    {$block['id_itekblock']}<i class="icon-info" data-toggle="tooltip" data-placement="top" title="
                                        {if isset($allshook[$block['hook']])}
                                            {l s='Ce hook sert à'|cat:' '|cat:$allshook[$block['hook']]|cat:" partie de la page d'accueil" d='Modules.Itekblock.Admin'}
                                        {else}
                                            {l s='Information sur ce bloc' d='Modules.Itekblock.Admin'}
                                        {/if}">
                                    </i>
                                </div>

                                <div class="col-xs-1">
                                    {if $block['icon'] != 'undefined'}
                                        <img class="svg"
                                             src="{if $block['icon']}{$block['icon']}{else if $block['custom_icon']}{$block['custom_icon']}{/if}"
                                        />
                                    {else}
                                        {l s='none' d='Modules.Itekblock.Admin'}
                                    {/if}
                                </div>
                               <div class="col-xs-2">
                                    {if isset($block['title'])}{$block['title']}{/if}
                                </div>
                                <div class="col-xs-2">
                                    {if isset($block['description'])}{$block['description']}{/if}
                                </div>
                                <div class="col-xs-2">
                                    <div class="listing-itkb-hook hook-{$allshook[$block['hook']]|lower}">
                                        {$allshook[$block['hook']]}
                                    </div>
                                </div>
                                <div class="col-xs-1 inline-flex">
                                    <label class="col-lg-12 col-xs-12 status-toggle"
                                           id="reminder_active_{$block['id_itekblock']}"
                                           for="reminder_active_{$block['id_itekblock']}"
                                           data-cart_itekblock_id='{$block['id_itekblock']}'>
                                        <section class="switch-input {if $block['status']}-checked{/if}">
                                            <input data-toggle="switch" class="switch-new" data-inverse="true"
                                                   type="checkbox" name="reminder_active_{$block['id_itekblock']}"
                                                   checked="">
                                        </section>
                                        <span class="switch_text switch-on" style="{if !$block['status']}display:none;{/if}"></span>
                                        <span class="switch_text switch-off" style="{if $block['status']}display:none;{/if}"></span>
                                    </label>
                                </div>
                                <div class="col-xs-2">
                                   {$block['date_upd']}
                                </div>
                                <div class="col-xs-1">
                                     <div class="btn-group">
                                        <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" x-placement="bottom-start">
                                            <span class="dropdown-item psre-edit" data-id="{$block['id_itekblock']}"><i class="material-icons">mode_edit</i> {l s='Edit' d='Admin.Actions'}</span>
                                            <span class="dropdown-item psre-delete" data-id="{$block['id_itekblock']}"><i class="material-icons">delete</i> {l s='Delete' d='Admin.Actions'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xs-12">
                                <div id="_more_info" class="col-lg-12 more_info ajax_return"></div>
                            </div>
                        </div>