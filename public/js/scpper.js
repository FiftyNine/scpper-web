/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function isIE() {
  var ms_ie = false;
    var ua = window.navigator.userAgent;
    var old_ie = ua.indexOf('MSIE ');
    var new_ie = ua.indexOf('Trident/');

    if ((old_ie > -1) || (new_ie > -1)) {
        ms_ie = true;
    }
    return ms_ie;
}

/*** TABLE FUNCTIONS ***/

/**
 * 
 * @param {string} containerId
 */
function showTableError(containerId)
{
    var container = $(containerId);
    var table = container.find('table.table-preview>tbody');
    table.find('tr:first-child').nextAll().remove();
    table.append($("#table-error-row > table > tbody").html());    
}

/**
 * 
 * @param {string} containerId
 * @param {string} url
 * @param {object} payload
 */
function fetchPaginator(containerId, url, payload)
{
    $.ajax({
        url: url,
        data: payload
    }).done(function (result) {
       if (result.success) {
           var container = $(containerId);
           container.html(result.content);
           container.find("ul.pagination > li > a").on('click', {container: containerId, url: url, payload: payload}, fetchPaginatorIndex);
           container.find(".per-page-control").on('change', {container: containerId, url: url, payload: payload}, changePaginatorSize);
           container.find("th.can-order").on('click', {container: containerId, url: url, payload: payload}, changePaginatorOrder);
       } else {
           showTableError(containerId);           
       }
    }).fail(function () {
        showTableError(containerId);           
    });
}

/**
 * 
 * @param {object} event
 * @returns {undefined}
 */
function changePaginatorOrder(event)
{
    var payload = event.data.payload;
    payload.page = 1;
    payload.perPage = $(event.data.container+' select.per-page-control').val();        
    payload.orderBy = $(this).attr('data-name');
    payload.ascending = $(this).attr('data-ascending') === "1" ? "0": "1";
    fetchPaginator(event.data.container, event.data.url, payload);
}

/**
 * 
 * @param {object} event
 */
function changePaginatorSize(event)
{
    var payload = event.data.payload;
    var orderCol = null;
    payload.page = 1;
    payload.perPage = $(this).val();
    orderCol = $(event.data.container+' th.ordered');
    payload.orderBy = orderCol.attr('data-name');
    payload.ascending = orderCol.attr('data-ascending');    
    fetchPaginator(event.data.container, event.data.url, payload);
}

/**
 * 
 * @param {object} event
 */
function fetchPaginatorIndex(event)
{
    var payload = event.data.payload;
    var orderCol = null;
    payload.page = $(this).attr('data-page');
    payload.perPage = $(event.data.container+' select.per-page-control').val();    
    orderCol = $(event.data.container+' th.ordered');
    payload.orderBy = orderCol.attr('data-name');
    payload.ascending = orderCol.attr('data-ascending');
    fetchPaginator(event.data.container, event.data.url, payload);
}

/**
 * 
 * @param {object} event
 */
function fetchPaginatorFirst(event)
{
    fetchPaginator(event.data.container, event.data.url, event.data.payload);
}

