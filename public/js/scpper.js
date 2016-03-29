/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** Declare our library **/
var scpper = {};

/** Add functionality **/

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

/** Table functions **/

scpper.tables = {
    /**
     * 
     * @param {string} containerId
     */
    showTableError: function (containerId)
    {
        var container = $(containerId);
        var table = container.find('table.table-preview>tbody');
        table.find('tr:first-child').nextAll().remove();
        table.append($("#table-error-row > table > tbody").html());    
    },

    /**
     * 
     * @param {string} containerId
     * @param {string} url
     * @param {object} payload
     */
    fetchPaginator: function (containerId, url, payload)
    {
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
            container.find("ul.pagination > li > a").on('click', {container: containerId, url: url, payload: payload}, scpper.tables.fetchPaginatorIndex);
            container.find(".per-page-control").on('change', {container: containerId, url: url, payload: payload}, scpper.tables.changePaginatorSize);
            container.find("th.can-order").on('click', {container: containerId, url: url, payload: payload}, scpper.tables.changePaginatorOrder);
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
        payload.perPage = $(event.data.container+' select.per-page-control').val();        
        payload.orderBy = $(this).attr('data-name');
        payload.ascending = $(this).attr('data-ascending') === "1" ? "0": "1";
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
        orderCol = $(event.data.container+' th.ordered');
        payload.orderBy = orderCol.attr('data-name');
        payload.ascending = orderCol.attr('data-ascending');    
        scpper.tables.fetchPaginator(event.data.container, event.data.url, payload);
    },

    /**
     * 
     * @param {object} event
     */
    fetchPaginatorIndex: function (event)
    {
        var payload = event.data.payload;
        var orderCol = null;
        payload.page = $(this).attr('data-page');
        payload.perPage = $(event.data.container+' select.per-page-control').val();    
        orderCol = $(event.data.container+' th.ordered');
        payload.orderBy = orderCol.attr('data-name');
        payload.ascending = orderCol.attr('data-ascending');
        scpper.tables.fetchPaginator(event.data.container, event.data.url, payload);
        document.getElementById(event.data.container.substring(1)).scrollIntoView();       
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

