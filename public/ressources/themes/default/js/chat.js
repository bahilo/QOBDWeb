$(function(){
//
/*================================[ Init ]==================================*/

    _.templateSettings = {
        interpolate: /\$\((.+?)\)/
    };

    chatApi = {       
        lastDiscussion:{
            nbTake: 5,
            nbSkip: 0,
            nbTotal: 0,
        },
        root:{
            chat: $('.chat-wrapper'),
            modal: $('.modal-content')
        },
        data:{
            agents: []
        }
    }

/*==========================[ début programme ]================================*/

    $(function(){

    });

/*================================[ Events ]==================================*/

    $(function(){

        $('.btnMessageNotif').on('click', function(e){
            $('.aside-menu-toggler').click();
        });

        chatApi.root.chat.find('.discussion-create').on('click', function (e) {
            e.preventDefault();
            $.fn.loading('show');            
            createDiscussion(); 
        });

        chatApi.root.chat.find('.btnDiscSetting').on('click', function (e) {
            e.preventDefault();
            $.fn.loading('show');            
            editDiscussion($(this).parent('.dvDiscussion').data('id')); 
        });

        chatApi.root.chat.find('.discussion-home-wrapper').on('click', '.link-discussion',  function(e){
            e.preventDefault();
            $.fn.loading('show');
            updateCanLoadMessages(true);
            var id = $(this).parent('.list-group-item').data('id');
            var $root = $('.messages-home-wrapper');
            $root.attr('data-id-discussion', id);
            $root.find('.message-area-wrapper').empty();
            loadDiscussionMessages(id);
            updateCurrentDiscussion(id);
        });

        chatApi.root.chat.find('.discussion-home-wrapper').on('click', '.btnDelete',  function(e){
            e.preventDefault();
            confirmDelete($(this));            
        });

        chatApi.root.chat.find('.messages-home-wrapper').on('click', '.control-wrapper button[type="submit"]',  function(e){
            e.preventDefault();
            $.fn.loading('show');
            createMessage(chatApi.root.chat.find('.messages-home-wrapper').find('input[name="chat_room[discussion]"]').val(), chatApi.root.chat.find('.messages-home-wrapper').find('textarea[name="chat_room[message]"]').val());
        });

        chatApi.root.chat.find('.messages-home-wrapper').on('click', '.loadMessages',  function(e){
            e.preventDefault();
            if (!$(this).hasClass('isDisabled')){
                chatApi.lastDiscussion.nbSkip += chatApi.lastDiscussion.nbTake;
                $.fn.loading('show');
                var $root = $('.messages-home-wrapper');
                var id = $root.data('id-discussion');
                $root.find('.message-area-wrapper').empty();
                loadDiscussionMessages(id);
            }   
        });

        
    });
    
/*================================[ Functions ]==================================*/

    function createDiscussion() {
        loadAgents(function (result) {
            renderCreateDiscussion(result);
        });      
    }

    function editDiscussion(id) {
        loadAgents(function (result) {
            renderEditDiscussion(result, id);
        });
    }

    function loadAgents(action) {
        
        $.fn.ajaxLoader({
            type: "post",
            data: '',
            url: Routing.generate('chat_agents'),
            onSuccess: function (result) {
                if($.isFunction(action))
                    action(JSON.parse(result));
            },
        });        
    }

    function createMessage(idDiscussion, message){
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: Routing.generate('chat_message_register', { id: idDiscussion, message: message }),
            onSuccess: function (result) {
                $.each(JSON.parse(result), function (index, elt) {
                    renderDiscussionMessage(elt);
                });
                $.fn.loading('hide');
                $('textarea[name="chat_room[message]"]', '.messages-home-wrapper').val('');
            },
        });
    }

    function loadDiscussionMessages(idDiscussion){
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: Routing.generate('chat_load_message', { id: idDiscussion, nbSkip: chatApi.lastDiscussion.nbSkip, nbTake: chatApi.lastDiscussion.nbTake }),
            onSuccess: function (result) {
                var data = JSON.parse(result);
                chatApi.lastDiscussion.nbTotal = data.messages.total; 
                $('.lstDiscussion', '.messages-home-wrapper').html(data.discussion[0].Name);
                $.each(data.messages.result.reverse(), function(index, elt){
                    renderDiscussionMessage(elt);
                });
                $.fn.loading('hide');
                activateTab("messages");
                updateCanLoadMessages();                
            },
        });
    }


/*================================[ Utlities ]==================================*/
   
    

    function updateCanLoadMessages(reset = false){
        if (reset){
            chatApi.lastDiscussion.nbSkip = 0;
            chatApi.lastDiscussion.nbTotal = 0;
        }else{
            if ((chatApi.lastDiscussion.nbSkip + chatApi.lastDiscussion.nbTake) < chatApi.lastDiscussion.nbTotal || chatApi.lastDiscussion.nbTotal == 0) {
                chatApi.root.chat.find('.messages-home-wrapper').find('.loadMessages').removeClass('isDisabled');
            }
            else {
                chatApi.root.chat.find('.messages-home-wrapper').find('.loadMessages').addClass('isDisabled');
            }   
        }             
    }
   
    //-----------------------------------------------------------------------------
    //-- envoi les requettes vers le server en ajax
    //-----------------------------------------------------------------------------
    
    function activateTab($target){
        var $tab = chatApi.root.chat.find('.nav-tabs');
        var $tabBody = chatApi.root.chat.find('.tab-content');

        $tab.find('.nav-link.active').removeClass('active');
        $tab.find('.nav-link[href="#'+ $target + '"').addClass('active');

        $tabBody.find('.tab-pane.active').removeClass('active');
        $tabBody.find('#' + $target).addClass('active');
    }

    function updateDiscussionRecipients(id){
        if (id > 0) {
            var indx = $.inArray(id, chatApi.data.agents);
            if (indx != -1) {
                chatApi.data.agents = $.grep(chatApi.data.agents[indx], function (value) {
                    return value != chatApi.data.agents[indx];
                });
            }
            else {
                chatApi.data.agents.push(id);
            }
        }
    }

    function updateCurrentDiscussion(id){
        var $root = $('.discussion-home-wrapper');
        var $Current = $root.find('.list-group-item-accent-danger');
        $Current.removeClass('list-group-item-accent-danger');
        $Current.removeClass('list-group-item-accent-warning');
        $Current.addClass('list-group-item-accent-warning');
        $Current.attr('data-current', 'false');

        $Current = $root.find('[data-id="' + id + '"]');
        $Current.removeClass('list-group-item-accent-warning');
        $Current.addClass('list-group-item-accent-danger');
        $Current.attr('data-current', 'true');

        $('input[name="chat_room[discussion]"]','.messages-home-wrapper').attr('value', id);
    }

    function fillEditDiscussion(id){
        var $discussionRoot = $('.discussion-home-wrapper');

        var $discussion = $discussionRoot.find('.dvDiscussion[data-id="'+ id +'"]');

        $('.discussion-name', '#mdlDefault').attr('value', $discussion.find('.DiscName').html());

        var $root = $('.ctrlAgents', '#mdlDefault .discussion-new-wrapper');
        $root.find('input.chxAgent').each(function (index) {
            $aElt = $(this);
            $.each($discussion.find('.avatars-stack').find('img.img-avatar'), function (indx, elt) {
                if ($aElt.data('id-agent') == $(elt).data('id-agent')){
                    $aElt.prop('checked', true);
                    $aElt.attr('disabled', 'disabled');
                }
            });
        });
        
    }

/*================================[ Renders ]==================================*/
    
    function renderDiscussion(data) {
        var $root = $('.discussion-home-wrapper');
        if ($root.find('[data-id="' + data.id+'"]').length == 0){
            var compiledTemplate = _.template($('#template-discussion').html());

            $root.append(compiledTemplate({
                url_chat_agent_discusion: Routing.generate('chat_agent_discusion', { id: data.id }),
                url_chat_delete_discussion: Routing.generate('chat_delete_discussion', { id: data.id }),
                discussion_id: data.id,
                discussion_name: data.Name,
                is_current: data.IsCurrent,
                is_owner: data.IsOwner,
                url_agent_owner_avatar: data.PathAvatarDir + '/' + ((data.Owner.Picture) ? data.Owner.Picture : 'default.png'),
                created_at: data.CreatedAtShort,
            }));

            compiledTemplate = _.template($('#template-discussion-recipients').html());
            $.each(data.Recipients, function (indx, elt) {
                $root.find('[data-id="' + data.id + '"]').find('.discussion-recip-wrapper').append(compiledTemplate({
                    agent_username: elt.UserName,
                    url_agent_avatar: data.PathAvatarDir + '/' + ((elt.Picture) ? elt.Picture : 'default.png'),
                }));
            });

            // ajout admin discussion
            $root.find('[data-id="' + data.id + '"]').find('.owner-wrapper').append(
                '<div>Créer par' +
                '<strong>' + data.Owner.UserName + '</strong>' +
                ' </div>'
            );

            // discussion current
            updateCurrentDiscussion(data.id)
        }
        else{
            $.fn.displayMessage({ messageTitle: 'Modification', messageBody: 'Veuillez rafraichir la page pour visualiser vos modifications...'});
        }        
    }

    function renderCreateDiscussion(data) {
        renderModalDiscussion(data, Routing.generate('chat_discussion_register'), function (result) {
            renderDiscussion(result);
            $.fn.loading('hide');
        });
    }

    function renderEditDiscussion(data, id) {
        renderModalDiscussion(data, Routing.generate('chat_discussion_edit', { id: id }), function(result){
            renderDiscussion(result);            
            $.fn.loading('hide');
        });
        fillEditDiscussion(id);
        $('#mdlDefault .discussion-new-wrapper').find('.btnDelete').attr('href', Routing.generate('chat_delete_discussion', { id: id }));
        
    }

    function renderModalDiscussion(data, route, action) {
        // affiche la modal
        $.fn.displayConfirm('Nouvelle discussion', $('#template-discussion-new').html(), function (response) {
            if (response) {
                var name = $('.discussion-name', '#mdlDefault').val();
                $.fn.loading('show');
                $.fn.ajaxLoader({
                    type: "get",
                    data: { name: name, agents: JSON.stringify(chatApi.data.agents) },
                    url: route,
                    onSuccess: function (result) {
                        if($.isFunction(action))
                            action(JSON.parse(result)[0]);
                    },
                });
            }
            else {
                $.fn.closeModal();
            }
        });

        // creation du formulaire + listing des commerciaux
        var compiledTemplate = _.template();
        compiledTemplate = _.template($('#template-discussion-agents').html());
        $.each(data.agents, function (indx, elt) {
            var myDate = new Date(elt.LoggedAt);
            var str_myDate = "";
            if (elt.LoggedAt){
                str_myDate = myDate.getHours() + ':' + myDate.getMinutes() + ':' + myDate.getSeconds() + " " + moment(myDate).format('DD/MM/YYYY');
            }
            $('.ctrlAgents', '#mdlDefault .discussion-new-wrapper').append(compiledTemplate({
                agent_id: elt.id,
                status: ((elt.IsOnline) ? 'success' : 'secondary'),
                agent_username: elt.UserName,
                last_connected: str_myDate,
                url_agent_avatar: data.PathAvatarDir + '/' + ((elt.Picture) ? elt.Picture : 'default.png'),
            }));
        });

        // selection des commerciaux pour la discussion
        chatApi.root.modal.find('.chxAgent').on('click', function () {
            updateDiscussionRecipients(parseInt($(this).data('id-agent')));
        });

    }

    function renderDiscussionMessage(data) {

        var compiledTemplate = _.template($('#template-message').html());

        $root = $('.messages-home-wrapper');

        $root.find('.message-area-wrapper').append(compiledTemplate({
            message_content: data.Content,
            message_id: data.id,
            agent_username: data.Agent.UserName,
            status: ((data.Agent.IsOnline) ? 'success' : 'secondary'),
            url_agent_avatar: data.PathAvatarDir + '/' + ((data.Agent.Picture) ? data.Agent.Picture : 'default.png'),
            created_at: data.CreatedAtShort,
        }));        
    }

});