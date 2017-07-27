/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


scpper.reports = {
    SELECTORS:
    {
        error: 'div#report-error',
        pageId: '[name*="pageId"]',
        pageName: '[name*="page-name"]',
        kind: '[name*="kind"]',
        status: '[name*="status"]',
        hasOriginal: '[name*="has-original"]',
        originalId: '[name*="original-id"]',
        originalSite: '[name*="original-site"]',
        originalPage: '[name*="original-page"]',
        contributors: '[name*="contributors"]',
        contributorId: '[name*="user-id"]',
        contributorName: '[name*="user-name"]',
        contributorRole: '[name*="role"]',
        contributorDelete: '[name*="user-delete"]',
        contributorAdd: '[name*="user-add"]',        
    },

    autocompletePageLookup: function(request, response)
    {
        var siteId = $(this.element).parents('form').find(scpper.reports.SELECTORS.originalSite).val();
        $.ajax({
            url: "/page/search",
            data: {
                query: request.term,
                siteId: siteId
            }
        }).done(function (result) {
            var content = [];
            if (result.success) {               
               for (var i=0; i<result.pages.length; i++) {
                   content.push({
                       label: result.pages[i].label,
                       altLabel: result.pages[i].altTitle,
                       value: result.pages[i].label,
                       // value: result.pages[i].id,
                       id: result.pages[i].id
                   });
               }
               response(content);
            } else {
               response(null);
            }
        }).fail(function () {            
            response(null);
        });         
    },

    autocompleteUserLookup: function(request, response)
    {
        $.ajax({
            url: "/user/search",
            data: {
                query: request.term
            }
        }).done(function (result) {
            var content = [];
            if (result.success) {               
               for (var i=0; i<result.users.length; i++) {
                   content.push({
                       label: result.users[i].label,                       
                       value: result.users[i].label,
                       // value: result.users[i].id,
                       id: result.users[i].id
                   });
               }
               response(content);
            } else {
               response(null);
            }
        }).fail(function () {            
            response(null);
        });         
    },    
    
    selectPage: function (event, ui)
    {
        $(this).attr('data-temp-id', ui.item.id);
        $(this).attr('data-temp-value', ui.item.label);
        $(this).removeClass('input-invalid');
        // return false;
    },

    selectUser: function (event, ui)
    {
        $(this).attr('data-temp-id', ui.item.id);
        $(this).attr('data-temp-value', ui.item.label);
        $(this).removeClass('input-invalid');        
        // return false;
    },

    refreshOriginalControls: function (form)
    {
        var status = form.find(scpper.reports.SELECTORS.status);
        var hasOriginal = form.find(scpper.reports.SELECTORS.hasOriginal).last();
        var site = form.find(scpper.reports.SELECTORS.originalSite);
        var page = form.find(scpper.reports.SELECTORS.originalPage);
        if ('2' !== status.val() || !hasOriginal.prop('checked')) {
            page.attr('disabled', true);
            site.attr('disabled', true);
        } else {
            page.removeAttr('disabled');
            site.removeAttr('disabled');
        }        
    },

    statusChanged: function (event)
    {
        var form = $(this).parents('form');
        var original = form.find(scpper.reports.SELECTORS.hasOriginal);
        if ('1' === $(this).val()) {
            original.attr('disabled', true);
        } else {
            original.removeAttr('disabled');
        }
        scpper.reports.refreshOriginalControls(form);
    },
    
    originalSiteChanged: function (event)
    {
        $(this).parents('form').find(scpper.reports.SELECTORS.originalPage).val('');
        $(this).parents('form').find(scpper.reports.SELECTORS.originalId).val('');
    },

    hasOriginalClick: function (event)
    {
        scpper.reports.refreshOriginalControls($(this).parents('form'));
    },

    addContributorClick: function(event)
    {
        var collection = $(this).parents('form').find('div.contributors');
        var contributors = collection.find('fieldset.page-report-contributor');
        var template = collection.find('span[data-template]').data('template');
        var newContributor;
        template = template.replace(/__index__/g, contributors.length);
        newContributor = $(template);
        // contributors.last().after(newContributor);
        collection.append(newContributor);
        scpper.reports.initContributorControls(newContributor);        
    },

    /**
     * 
     * @param InputElement lookup
     * @param InputElement id
     * @param jQueryAjaxSettings request
     * @param Callback done
     */
    lookupControlBlur: function (lookup, id, request, done)
    {
        if ($(lookup).is('[data-temp-id]') && $(lookup).is(['data-temp-value='+$(lookup).val()])) {
            $(id).val($(lookup).attr('data-temp-id'));
            $(lookup).removeClass('input-invalid');
        } else if ($(lookup).val() !== '') {
            $(lookup).addClass('input-loading');
            $.ajax(request).done(function (result){
                if (!done(this, id, result)) {
                    $(this).addClass('input-invalid');
                    $(id).val('');
                }
            }).fail(function(){
                $(this).addClass('input-invalid');
                $(id).val('');
            }).always(function(){
                $(this).removeClass('input-loading');
            });
        } else {
            $(lookup).addClass('input-invalid');
            $(id).val('');            
        }
        $(lookup).removeAttr('data-temp-id');
        $(lookup).removeAttr('data-temp-value');        
    },


    originalPageBlur: function (event)
    {
        var siteId = $(this).parents('form').find(scpper.reports.SELECTORS.originalSite).val();
        var idControl = $(this).parents('form').find(scpper.reports.SELECTORS.originalId)[0];
        var request = {
            url: '/page/search',
            data: {
                query: $(this).val(),
                siteId: siteId
            },
            context: this
        };        
        scpper.reports.lookupControlBlur(
            this, 
            idControl, 
            request, 
            function(lookup, id, result) {
                if (result.success) {
                    for (var i=0; i<result.pages.length; i++) {
                        if (result.pages[i].label.toUpperCase() === $(lookup).val().toUpperCase()) {
                            $(id).val(result.pages[i].id);
                            $(lookup).val(result.pages[i].label);
                            $(lookup).removeClass('input-invalid');
                            return true;
                        }
                    }
                }   
                return false;
            }
        );
    },

    userNameBlur: function (event)
    {
        var idControl = $(this).parents('.page-report-contributor').find(scpper.reports.SELECTORS.contributorId)[0];
        var request = {
            url: '/user/search',
            data: {
                query: $(this).val()
            },
            context: this
        };        
        scpper.reports.lookupControlBlur(
            this, 
            idControl, 
            request, 
            function(lookup, id, result) {
                if (result.success) {
                    for (var i=0; i<result.users.length; i++) {
                        if (result.users[i].label.toUpperCase() === $(lookup).val().toUpperCase()) {
                            $(id).val(result.users[i].id);
                            $(lookup).val(result.users[i].label);
                            $(lookup).removeClass('input-invalid');
                            return true;
                        }
                    }
                }   
                return false;
            }
        );
    },

    deleteUserClick: function (event)
    {
        $(this).parents('fieldset.page-report-contributor').remove();
    },

    initPageLookup: function(control)
    {       
        control.autocomplete({
          source: scpper.reports.autocompletePageLookup,
          select: scpper.reports.selectPage,
          delay: 200,
          minLength: 3
        });
    },
   
    initContributorControls: function (contributor)
    {
        var name = $(contributor).find(scpper.reports.SELECTORS.contributorName);
        name.autocomplete({
          source: scpper.reports.autocompleteUserLookup,
          select: scpper.reports.selectUser,
          delay: 200,
          minLength: 3
        });        
        name.blur(scpper.reports.userNameBlur);
        $(contributor).find(scpper.reports.SELECTORS.contributorRole).change(function() {$(this).removeClass('input-invalid')});
        $(contributor).find(scpper.reports.SELECTORS.contributorDelete).click(scpper.reports.deleteUserClick);
    },
    
    validateForm: async function (form)
    {
        var result = true;           
        var kind = $(form).find(scpper.reports.SELECTORS.kind);
        var status = $(form).find(scpper.reports.SELECTORS.status);
        var contributors = $(form).find('fieldset.page-report-contributor');
        var hasOriginal = $(form).find(scpper.reports.SELECTORS.hasOriginal).last();
        var originalId = $(form).find(scpper.reports.SELECTORS.originalId);
        var originalPage = $(form).find(scpper.reports.SELECTORS.originalPage);
        var firstInvalid = null;
        var i=0;        
        if (!kind.val()) {
            firstInvalid = firstInvalid || kind;
            kind.addClass('input-invalid');
            result = false;
        }
        // Wait until all ajax requests are done or timeout is over
        while ($(form).find('.input-loading').length > 0 && i < 10) {
            await scpper.sleep(500);
            i++;
        }        
        if ('2' === status.val() && hasOriginal.prop('checked') && ('' === originalId.val() || '' === originalPage.val() || originalPage.hasClass('input-invalid') || originalPage.hasClass('input-loading'))) {
            firstInvalid = firstInvalid || originalPage;
            originalPage.addClass('input-invalid');
            result = false;
        }
        contributors.each(function (index, elem) {
            var id = $(elem).find(scpper.reports.SELECTORS.contributorId);
            var role = $(elem).find(scpper.reports.SELECTORS.contributorRole);
            var name = $(elem).find(scpper.reports.SELECTORS.contributorName);
            if ('' === id.val() || '' === name.val() || name.hasClass('input-invalid') || name.hasClass('input-loading')) {
                firstInvalid = firstInvalid || name;
                name.addClass('input-invalid');
                result = false;                
            }
            // Role must be in line with the status
            if ('1' === status.val() && '3' === role.val() || '1' !== status.val() && '3' !== role.val()) {
                firstInvalid = firstInvalid || role;
                role.addClass('input-invalid');
                result = false;
            } else {
                role.removeClass('input-invalid');
            }
            
        });
        if (firstInvalid) {
            firstInvalid.focus();
        }
        return result;
    },
    
    responseToList: function (messageObj, list) {
        $.each(messageObj, function (name, value) {
            var li = $('<li>');
            var ul;
            if (typeof value === 'object') {
                li.append('<span>'+name+'</span>');
                ul = $('<ul>');
                scpper.reports.responseToList(value, ul);
                li.append(ul);
            } else {
                li.text(name+': '+value);
            }
            list.append(li);
        });
    },
    
    showSubmitResponse: function(dialog, data) {
        var errorBlock;
        var ul;
        if (data.success) {
            dialog.dialog('close');
        } else {
            errorBlock = dialog.find(scpper.reports.SELECTORS.error);
            errorBlock.empty();
            errorBlock.append('<span><strong>Report wasn\'t submitted due to an error!</strong></span>');
            ul = $('<ul>');
            scpper.reports.responseToList(data.messages, ul);
            errorBlock.append(ul);
            errorBlock.show();            
        }
    },
    
    submitReportForm: function (event)
    {
        var form = $(this).find('form');       
        var dialog = $(this);
        scpper.reports.validateForm(form).then(success => {
            if (success) {
                $.post(
                    '/page/report', 
                    form.serialize(), 
                    function(data, textStatus) {
                        scpper.reports.showSubmitResponse(dialog, data);
                    });
                // form[0].submit();
                
            }
        });            
    },
    
    closeReportForm: function ()
    {
        var form = $(this).find('form');
        var reset = $(this).find('#initial-values').data('content');
        form.html(reset);
        scpper.reports.initReportForm(form);
    },
    
    initReportForm: function (form)
    {
        var pageControl = form.find(scpper.reports.SELECTORS.originalPage);
        form.find(scpper.reports.SELECTORS.kind).change(function() {
            if ($(this).val()) {
                $(this).removeClass('input-invalid');
            }            
        });
        pageControl.change(scpper.reports.originalPageChanged);
        scpper.reports.initPageLookup(pageControl);
        form.find(scpper.reports.SELECTORS.hasOriginal).click(scpper.reports.hasOriginalClick);
        form.find(scpper.reports.SELECTORS.status).change(scpper.reports.statusChanged).trigger('change');        
        form.find(scpper.reports.SELECTORS.originalSite).change(scpper.reports.originalSiteChanged);
        pageControl.blur(scpper.reports.originalPageBlur);
/*        if (form.find(scpper.reports.SELECTORS.originalId).val() === '') {
            pageControl.addClass('input-invalid');
        }*/
        form.find(scpper.reports.SELECTORS.contributorAdd).click(scpper.reports.addContributorClick);
        scpper.reports.initContributorControls(form.find('fieldset.page-report-contributor'));        
    },
    
    initReportDialog: function (content) 
    {        
        var form = $(content).find('form');
        var dialog;
        var resetSpan = $('<span id="initial-values"></span>');
        resetSpan.data('content', form.html());
        // replace(/(['"])/g, "\\$1");
        $(content).append(resetSpan);
        dialog = $(content).dialog({
            autoOpen: false,
            height: 665,
            width: 600,
            modal: true,
            buttons: {
                "Submit": scpper.reports.submitReportForm,
                "Cancel": function(event) {
                    $(this).dialog('close');
                }
            },
            close: scpper.reports.closeReportForm
        });
        scpper.reports.initReportForm(form);
        return dialog;
    }
}