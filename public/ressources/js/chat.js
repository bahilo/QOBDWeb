$(function(){
//
/*================================[ Init ]==================================*/

api = {
    root: $('.chat-wrapper'),
}

$(function(){

});

/*================================[ Events ]==================================*/

    $(function(){

        api.root.find('.discussion-create').on('click', function (e) {
            e.preventDefault();            
            createDiscussion(); 
        });

    });
    
/*================================[ Functions ]==================================*/

    function createDiscussion() {
        var discussion = {};
        discussion.title = 'Nouvelle discussion';
        discussion.target = 'discussion-name';
        discussion.body = '';
        discussion.body += '<div class="form-group">';
        discussion.body += '   <label for="recipient-name" class="col-form-label">Titre de la discussion</label>';
        discussion.body += '   <input type="text" class="form-control ' + discussion.target + '">';
        discussion.body += '</div>';

        displayConfirm(discussion.title, discussion.body, function (response) {
            if (response) {
                ajaxLoader({
                    type: "post",
                    data: '',
                    url: Routing.generate('chat_discussion_register', { name: $('.' + discussion.target, '.modal').val() }),
                    onSuccess: function (result) {
                        renderDiscussion(JSON.parse(result)[0]);
                    },
                    onError: function (jqXHR, textStatus) {
                        displayMessage('Erreur détectée',"Une erreur s'est produite lors de la création de votre discussion. Veuillez contacter un administrateur");
                        console.log('Error found: ' + textStatus);
                    }
                });

            }
        });
    }


/*================================[ Utlities ]==================================*/

    function renderDiscussion(data){
        var regex = /\$\((.+?)\)/;
        var compiledTemplate = _.template($('#template-discussion').html(), { interpolate: regex });
        

        $root = $('.discussion-home-wrapper');
        $root.append(compiledTemplate({
            url_chat_agent_discusion: Routing.generate('chat_agent_discusion', { id: data.id }),
            url_chat_delete_discussion: Routing.generate('chat_delete_discussion', { id: data.id }),
            discussion_id: data.id,
            discussion_name: data.Name,
            url_agent_owner_avatar: data.PathAvatarDir + '/' + data.Owner.Picture,
            created_at: data.CreatedAtShort,
        }));

        compiledTemplate = _.template($('#template-discussion-recipients').html(), { interpolate: regex });
        $.each(data.Recipients, function(indx, elt){
            $root.find('[data-id="' + data.id + '"]').find('.discussion-recip-wrapper').append(compiledTemplate({
                agent_username: elt.UserName,
                url_agent_avatar: data.PathAvatarDir + '/' + elt.Picture,
            })); 
        });

        $root.find('[data-id="' + data.id + '"]').find('.owner-wrapper').append(
            '<div>Créer par'+
            '<strong>'+ data.Owner.UserName +'</strong>'+
            ' </div>'
        );
        
    }

    function displayMessage(messageTitle, messageBody){
        var modal = $('.modal');
        modal.find('.modal-title').html(messageTitle);
        modal.find('.modal-body').html(messageBody);
        openModal();
    }

    function openModal(){
        $('.modal').modal('show');
    }

    function closeModal(){
        $('.modal').modal('hide');
    }

    //-----------------------------------------------------------------------------
    //-- modal confirmation de la suppression
    //-----------------------------------------------------------------------------
    function displayConfirm(titre, message, action) {
        $root = $('.modal');
        $root.find('._mvalide').unbind();
        $root.find('._mcancel').unbind();

        displayMessage(titre, message);
        
        $root.find('._mvalide').on('click', function () {            
            if ($.isFunction(action)) {
                action(true);
            }
            closeModal();
        });

        $root.find('._mcancel').on('click', function () {           
            if ($.isFunction(action)) {
                action(false);
            }
            closeModal();
        });
    }

    

    //-----------------------------------------------------------------------------
    //-- envoi les requettes vers le server en ajax
    //-----------------------------------------------------------------------------
    function ajaxLoader(options) {
        $.ajax({
            url: options.url,
            data: options.data,
            type: options.type,
            success: options.onSuccess,
            error: options.onError
        });
    }
    
});