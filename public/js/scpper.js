/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** Declare our library **/
var scpper = {};

/** Add functionality **/

scpper.isInt = function (value) 
{
  return !isNaN(value) && 
         parseInt(Number(value)) == value && 
         !isNaN(parseInt(value, 10));
};

scpper.loadingOverlayOptions = {
    css: {
        border:		'none',
        backgroundColor:'transparent',                
    },
    overlayCSS: {opacity: 0.25}, 
    message: '<div class="loader"></div>'
};

scpper.isIE = function() {
    var ms_ie = false;
    var ua = window.navigator.userAgent;
    var old_ie = ua.indexOf('MSIE ');
    var new_ie = ua.indexOf('Trident/');

    if ((old_ie > -1) || (new_ie > -1)) {
        ms_ie = true;
    }
    return ms_ie;
};

scpper.convertDate = function(dateString) {
    if (scpper.isIE()) {
        // holy shit fuck IE
        return new Date(dateString.substring(0, dateString.length-5));
    } else {
        return new Date(dateString);
    }        
};

scpper.isElementInViewport = function(el) {

    //special bonus for those using jQuery
    if (typeof jQuery === "function" && el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
};

scpper.textInputFocusIn = function (event) {
    this.setSelectionRange(0, this.value.length);
};

scpper.textInputFocusOut = function (event) {
    this.setSelectionRange(0, 0);
    this.value = this.defaultValue;
    this.blur();
};

scpper.post = function (path, parameters) {
    var form = $('<form></form>');

    form.attr("method", "post");
    form.attr("action", path);

    $.each(parameters, function(key, value) {
        var field = $('<input></input>');

        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);

        form.append(field);
    });

    // The form needs to be a part of the document in
    // order for us to be able to submit it.
    $(document.body).append(form);
    form.submit();
};

scpper.sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/** Table functions **/

scpper.tables = {
    BASIC_SELECTORS: 
    {
        table: 'table',
        tableBody: 'table.table-preview>tbody',
        firstRow: 'tr:first-child',
        errorBody: '#basic-table-error-row > table > tbody',
        pageLink: 'ul.pagination > li > a',
        perPageCount: 'select.per-page-control',
        sortableColumn: 'th.can-order',
        sortColumn: 'th.ordered',
        sortAttribute: 'data-name',
        sortOrderAttribute: 'data-ascending',
        currentPageAttribute: 'data-page',
        pageInput: '.table-pagination-input',
        topPagination: '.table-navigation-top'
    },
    RESPONSIVE_SELECTORS:
    {
        table: '.responsive-table',
        tableBody: '.responsive-table',
        firstRow: '.responsive-table-data:first-child',
        errorBody: '#responsive-table-error-row',
        pageLink: 'ul.pagination > li > a',
        perPageCount: 'select.per-page-control',
        sortableColumn: '.responsive-table-column-name.can-order',
        sortColumn: '.responsive-table-column-name.ordered',
        sortAttribute: 'data-name',
        sortOrderAttribute: 'data-ascending',
        currentPageAttribute: 'data-page',
        pageInput: '.table-pagination-input',
        topPagination: '.table-navigation-top'
    },
    selectors: null,
    
    /**
     * 
     * @param {string} containerId
     */
    showTableError: function (containerId)
    {
        var container = $(containerId);
        var table = container.find(scpper.tables.selectors.tableBody);
        table.find(scpper.tables.selectors.firstRow).nextAll().remove();
        table.append($(scpper.tables.selectors.errorBody).html());    
    },

    /**
     * 
     * @param {string} containerId
     * @param {string} url
     * @param {object} payload
     */
    fetchPaginator: function (containerId, url, payload)
    {
        var table = $(containerId).children(scpper.tables.selectors.table);
        table.block(scpper.loadingOverlayOptions);
        $.ajax({
            url: url,
            data: payload
        }).done(function (result) {
           if (result.success) {
               var container = $(containerId);
               container.html(result.content);           
               scpper.tables.assignPaginatorEvents(containerId, url, payload);
           } else {
               scpper.tables.showTableError(containerId);           
           }
        }).fail(function () {            
            scpper.tables.showTableError(containerId);
        }).always(function () {
            table.unblock(); 
        });      
    },    
    
    /**
     * 
     * @param {type} containerId
     * @param {type} url
     * @param {type} payload
     * @returns {undefined}
     */
    assignPaginatorEvents: function (containerId, url, payload)
    {        
        var container = $(containerId);
        if (container) {
            container.find(scpper.tables.selectors.pageLink).on('click', {container: containerId, url: url, payload: payload}, scpper.tables.fetchPaginatorFromLink);
            container.find(scpper.tables.selectors.perPageCount).on('change', {container: containerId, url: url, payload: payload}, scpper.tables.changePaginatorSize);
            container.find(scpper.tables.selectors.sortableColumn).on('click', {container: containerId, url: url, payload: payload}, scpper.tables.changePaginatorOrder);
            container.find(scpper.tables.selectors.pageInput).on('focusin', {}, scpper.textInputFocusIn);
            container.find(scpper.tables.selectors.pageInput).on('focusout', {}, scpper.textInputFocusOut);
            container.find(scpper.tables.selectors.pageInput).on('keydown', {container: containerId, url: url, payload: payload}, scpper.tables.pageInputKeyDown);            
        }
    },

    fetchPaginatorByIndex: function (index, container, url, payload)
    {
        var orderCol = null;
        var table = document.getElementById(container.substring(1));
        var topPagination = $(table).find(scpper.tables.selectors.topPagination);
        var offset = null;
        var scrollTo = null;
        if (topPagination.is(':visible')) {
            scrollTo = topPagination.get(0);
        } else {
            scrollTo = table;
        }
        payload.page = index;
        payload.perPage = $(container+' '+scpper.tables.selectors.perPageCount).val();    
        orderCol = $(container+' '+scpper.tables.selectors.sortColumn);
        payload.orderBy = orderCol.attr(scpper.tables.selectors.sortAttribute);
        payload.ascending = orderCol.attr(scpper.tables.selectors.sortOrderAttribute);
        scpper.tables.fetchPaginator(container, url, payload);
        if (!scpper.isElementInViewport(scrollTo)) {
            offset = $(scrollTo).offset();
            offset.top-=$('.scpper-navbar').height();
            $('html, body').animate({
                scrollTop: offset.top,
                scrollLeft: offset.left
            });
        }
    },

    pageInputKeyDown: function(event) 
    {  
        if (event.key === 'Escape') {
            $(this).trigger('focusout');
            handled = true;
        } else if (event.key === 'Enter') {
            if (scpper.isInt(this.value)) {
                this.defaultValue = this.value;
                scpper.tables.fetchPaginatorByIndex(this.value, event.data.container, event.data.url, event.data.payload);                
                this.blur();
            } else {
                this.value = this.defaultValue;
                this.setSelectionRange(0, this.value.length);
            }
        }
    },

    /**
     * 
     * @param {object} event
     * @returns {undefined}
     */
    changePaginatorOrder: function (event)
    {
        var payload = event.data.payload;
        payload.page = 1;
        payload.perPage = $(event.data.container+' '+scpper.tables.selectors.perPageCount).val();        
        payload.orderBy = $(this).attr(scpper.tables.selectors.sortAttribute);
        payload.ascending = $(this).attr(scpper.tables.selectors.sortOrderAttribute) === "1" ? "0": "1";
        scpper.tables.fetchPaginator(event.data.container, event.data.url, payload);
    },

    /**
     * 
     * @param {object} event
     */
    changePaginatorSize: function (event)
    {
        var payload = event.data.payload;
        var orderCol = null;
        payload.page = 1;
        payload.perPage = $(this).val();
        orderCol = $(event.data.container+' '+scpper.tables.selectors.sortColumn);
        payload.orderBy = orderCol.attr(scpper.tables.selectors.sortAttribute);
        payload.ascending = orderCol.attr(scpper.tables.selectors.sortOrderAttribute);
        scpper.tables.fetchPaginator(event.data.container, event.data.url, payload);
    },

    /**
     * 
     * @param {object} event
     */
    fetchPaginatorFromLink: function (event)
    {
        var index = $(this).attr(scpper.tables.selectors.currentPageAttribute);
        scpper.tables.fetchPaginatorByIndex(index, event.data.container, event.data.url, event.data.payload);
    },

    /**
     * 
     * @param {object} event
     */
    fetchPaginatorFirst: function (event)
    {
        scpper.tables.fetchPaginator(event.data.container, event.data.url, event.data.payload);
    }    
};

scpper.tables.selectors = scpper.tables.RESPONSIVE_SELECTORS;

/** Tags **/

scpper.tags = {
    inputTagsId: '#tags',
    combineSwitchId: '#combine-with-and',
    searchTagsRegionId: '#search-tags',
    prototypeTagElementId: '#prototype-tag-element',    

    searchTags: [],
    tags: [],    
    
    tagPrototype: {        
        tag: '',
        include: true,
        buildUI: function() {            
            var newElement = $(scpper.tags.prototypeTagElementId).clone(true);            
            newElement.removeAttr('id');            
            newElement.attr('data-tag', this.tag);            
            newElement.attr('data-include', this.include?"1":"0");            
            newElement.children('.search-tag-body').html(this.tag);            
            newElement.appendTo(scpper.tags.searchTagsRegionId);
        },
        getSearchString: function() {
            var result = '';
            if (this.include)
                result = '+';
            else
                result='-';
            result += this.tag;
            return result;
        }
    },
 
    findTagIndex: function (tag)
    {
        var i = 0;
        for (i = 0; i < scpper.tags.tags.length; i++) {
            if (scpper.tags.tags[i].tag === tag)
              return i;
        }
        return -1;
    },
    
    addSearchTag: function (tag)
    {
        var include = true;
        var i;
        var tagObject = null;
        if (tag[0] === '+')
            tag = tag.substr(1)
        else if (tag[0] === '-') {
            include = false;
            tag = tag.substr(1);
        }
        tag = tag.toLowerCase();
        for (i = 0; i < scpper.tags.searchTags.length; i++)
            if (scpper.tags.searchTags[i].tag === tag)
                return null;
        if (scpper.tags.findTagIndex(tag) < 0)
            return null;
        tagObject = jQuery.extend({}, scpper.tags.tagPrototype);
        tagObject.tag = tag;
        tagObject.include = include;
        tagObject.buildUI();
        scpper.tags.searchTags.push(tagObject);
        return tagObject;
    },

    switchSearchTag: function (tag)
    {
        var i=0;
        for (i=0; i<scpper.tags.searchTags.length; i++)
            if (scpper.tags.searchTags[i].tag === tag) {
                scpper.tags.searchTags[i].include = !scpper.tags.searchTags[i].include;
                break;
            }    
    },
    
    deleteSearchTag: function (tag)
    {
        var i = 0;
        for (i = 0; i < scpper.tags.searchTags.length; i++)
            if (scpper.tags.searchTags[i].tag === tag) {
                scpper.tags.searchTags.splice(i, 1);
                break;
            }
    },
    
    autocompleteTag: function(request, response)
    {
        var prefix = '';
        var filter = request.term;
        var result = [];
        var i = 0;
        if (filter.length > 0 && (filter[0] == '+' || filter[0] == '-')) {
            prefix = filter[0];
            filter = filter.substr(1);
        }
        if (filter.length > 0) {        
            for (i = 0; i < scpper.tags.tags.length; i++) {
                if (scpper.tags.tags[i].tag.startsWith(filter)) {
                    result.push(prefix+scpper.tags.tags[i].tag);
                }
            }
        }
        response(result);
    },

    buildTagsString: function ()
    {
        var i = 0;
        var result = '';    
        for (i = 0; i < scpper.tags.searchTags.length; i++) {
            if (i > 0) 
                result += ',';
            result += scpper.tags.searchTags[i].getSearchString();
        }
        return result;
    },
    
    buildMethodString: function ()
    {
        var method = 'and';        
        var methodSwitch = $(scpper.tags.combineSwitchId);
        if (!methodSwitch.prop('checked')) {
            method = 'or';
        }
        return method;
    },
    
    buildSearchString: function ()
    {
        var i = 0;
        var result = '?';
        result += 'method='+scpper.tags.buildMethodString()+'&tags='+encodeURIComponent(scpper.tags.buildTagsString());
        return result;
    },
    
    processAutocompleteResponse: function (event, ui) 
    {
        var i = 0;
        var index = 0;
        var newLabel = '';
        for (i = 0; i < ui.content.length; i++) {
            newLabel = ui.content[i].label;
            if (['+', '-'].indexOf(newLabel[0]) >= 0) {
                newLabel = newLabel.substr(1);
            }
            index = scpper.tags.findTagIndex(newLabel);
            if (index >= 0) {
                newLabel = scpper.tags.tags[index].label+' ('+scpper.tags.tags[index].count+')'
            }
            ui.content[i].label = newLabel;
        }
    },
    
    renderAutocompleteItem: function (ul, item) 
    {
        return $("<li>")
            .attr("data-value", item.value)
            .append(item.label+'()')
            .appendTo(ul);
    },
    
    addSelectedTag: function (event, ui) 
    {
        scpper.tags.addSearchTag(ui.item.value);
        ui.item.value = '';
        ui.item.label = '';
    },
    
    submit: function (event)
    {
        var paramString = '';
        scpper.tags.addSearchTag($(scpper.tags.inputTagsId).val());
        paramString = scpper.tags.buildSearchString();
        location.href = location.protocol + '//' + location.host + location.pathname + paramString;
    },   
    
    tagKeyUp: function (event)
    {
        var tag = '';
        if ([13, 10, 32].indexOf(event.keyCode) >= 0) {
            tag = event.target.value.trim();
            if (scpper.tags.addSearchTag(tag))
                $(".ui-menu-item").hide();            
                event.target.value = '';
        }
    },
    
    deleteSearchTagClick: function (event)
    {
        var tag = '';
        var parent = $(event.target.parentElement);
        tag = parent.attr('data-tag');
        parent.remove();
        scpper.tags.deleteSearchTag(tag);
    },

    bodySearchTagClick: function (event)
    {
        var tag = '';
        var include = 1;
        var parent = $(event.target.parentElement);
        tag = parent.attr('data-tag');
        include = parent.attr('data-include');
        parent.attr('data-include', include==="0"?"1":"0");
        scpper.tags.switchSearchTag(tag);
    },
        
    init: function (tags, inputId, switchId, tagsId, prototypeId)
    {
        scpper.tags.tags = tags;
        scpper.tags.inputTagsId = inputId;
        scpper.tags.combineSwitchId = switchId;
        scpper.tags.searchTagsRegionId = tagsId;
        scpper.tags.prototypeTagElementId = prototypeId;        
    }    
};

/** Search **/

scpper.search = {
    siteId: 66711,
    
    autocompleteSearch: function(request, response)
    {
        $.ajax({
            url: "/search/autocomplete",
            data: {
                query: request.term,
                siteId: scpper.search.siteId
            }
        }).done(function (result) {
            var content = [];
            if (result.success) {               
               for (var i=0; i<result.pages.length; i++) {
                   content.push({
                       label: result.pages[i].label,
                       altLabel: result.pages[i].altTitle,
                       value: result.pages[i].label,
                       type: 'page',
                       id: result.pages[i].id
                   });
               }
               for (var i=0; i<result.users.length; i++) {
                   content.push({
                       label: result.users[i].label,
                       altLabel: null,
                       value: result.users[i].label,
                       type: 'user',
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
    
    selectItem: function (event, ui)
    {
        if (ui.item.type === 'page') {
            location.href = location.protocol + '//' + location.host + '/page/'+ui.item.id;
        } else if (ui.item.type === 'user') {
            location.href = location.protocol + '//' + location.host + '/user/'+ui.item.id;
        }
    },
    
    registerSearchAutocomplete: function()
    {
        $.widget('custom.searchAutocomplete', $.ui.autocomplete, {
            _renderItem: function(ul, item) {
                var li = $('<li>')
                    .addClass('ui-menu-item')
                    .appendTo(ul);
                var div = $('<div>')
                    .addClass('ui-menu-item-wrapper')
                    .append(item.label)                    
                    .appendTo(li);
                if (item.altLabel) {
                    div.append($('<em>').append(' - '+item.altLabel));
                }
                var span = $('<span>')
                    .addClass('small-text')
                    .append(' ('+item.type+')')
                    .appendTo(div);
                if (item.type === 'page') {
                    span.addClass('search-item-page');
                } else if (item.type === 'user') {
                    span.addClass('search-item-user');
                }
                return li;
            }
        });
    },
    
    initSearchControl: function(id)
    {       
        $(id).searchAutocomplete({
          source: scpper.search.autocompleteSearch,
          select: scpper.search.selectItem,
          delay: 500,
          minLength: 3
        });
        $(id).searchAutocomplete('widget').addClass('search-autocomplete');
    }
};