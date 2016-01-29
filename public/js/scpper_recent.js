/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var paginatorData = {
    siteId: -1,
    fromDate: '1899-01-01',
    toDate: '1899-01-01'
};

var lastUpdate = null;

function showTableError(containerId)
{
    var container = $(containerId);
    var table = container.find('table.table-preview>tbody');
    table.find('tr:first-child').nextAll().remove();
    table.append($("#table-error-row > table > tbody").html());    
}

function fetchPaginator(containerId, url, payload)
{
    $.ajax({
        url: url,
        data: payload
    }).done(function (result) {
       if (result.success) {
           var container = $(containerId);
           container.html(result.content);
           container.find("ul.pagination > li > a").on('click', {container: containerId, url: url}, fetchPaginatorIndex);
           container.find(".per-page-control").on('change', {container: containerId, url: url}, changePaginatorSize);
           container.find("th.can-order").on('click', {container: containerId, url: url}, changePaginatorOrder);
       } else {
           showTableError(containerId);           
       }
    }).fail(function () {
        showTableError(containerId);           
    });
}

function changePaginatorOrder(event)
{
    var payload = $.extend({}, paginatorData);
    payload.page = 1;
    payload.perPage = $(event.data.container+' select.per-page-control').val();        
    payload.orderBy = $(this).attr('data-name');
    payload.ascending = $(this).attr('data-ascending') === "1" ? "0": "1";
    fetchPaginator(event.data.container, event.data.url, payload);
}

function changePaginatorSize(event)
{
    var payload = $.extend({}, paginatorData);
    var orderCol = null;
    payload.page = 1;
    payload.perPage = $(this).val();
    orderCol = $(event.data.container+' th.ordered');
    payload.orderBy = orderCol.attr('data-name');
    payload.ascending = orderCol.attr('data-ascending');    
    fetchPaginator(event.data.container, event.data.url, payload);
}

function fetchPaginatorIndex(event)
{
    var payload = $.extend({}, paginatorData);
    var orderCol = null;
    payload.page = $(this).attr('data-page');
    payload.perPage = $(event.data.container+' select.per-page-control').val();    
    orderCol = $(event.data.container+' th.ordered');
    payload.orderBy = orderCol.attr('data-name');
    payload.ascending = orderCol.attr('data-ascending');
    fetchPaginator(event.data.container, event.data.url, payload);
}

function fetchPaginatorFirst(event)
{
    var payload = $.extend({}, paginatorData);
    payload.page = 1;
    payload.perPage = 10;
    fetchPaginator(event.data.container, event.data.url, payload);
}

function initRecent()
{
    $('#show-members').on('click', {container: "#members-list", url: "/recent/members"}, fetchPaginatorFirst);
    $('#show-pages').on('click', {container: "#pages-list", url: "/recent/pages"}, fetchPaginatorFirst);
    $('#show-editors').on('click', {container: "#editors-list", url: "/recent/editors"}, fetchPaginatorFirst);
    $('#show-voters').on('click', {container: "#voters-list", url: "/recent/voters"}, fetchPaginatorFirst);    
    $('.datepicker').datepicker({autoSize: true, dateFormat: "yy-mm-dd", maxDate: lastUpdate});
}

$(document).ready(initRecent);

