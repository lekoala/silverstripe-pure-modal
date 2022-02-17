<% require css('lekoala/silverstripe-pure-modal: client/pure-modal.css') %>

<label for="$ModalID" class="<% if $extraClass %> $extraClass<% end_if %>">
    $ButtonTitle.RAW
</label>
<div class="pure-modal from-top">
    <input id="$ModalID" class="checkbox no-change-track" type="checkbox">
    <div class="pure-modal-overlay">
        <div class="pure-modal-wrap pure-modal-action fill-height" style="max-height:90vh;">
            <label for="$ModalID" class="pure-modal-close"></label>
            <div class="toolbar toolbar--north row">
                <h1>$Title</h1>
                <label for="$ModalID" class="close">&#10006;</label>
            </div>
            <div class="panel panel--padded panel--scrollable flexbox-area-grow">
                <fieldset>
                    <% loop $FieldList %>
                        $FieldHolder
                    <% end_loop %>
                </fieldset>
            </div>
            <% if $ShowDialogButton %>
                <div class="toolbar toolbar--south">
                    <input type="submit" name="action_$Name" class="btn action btn btn-info custom-action" value="$ButtonTitle">
                </div>
            <% end_if %>
        </div>
    </div>
</div>
