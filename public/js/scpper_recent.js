/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
           var pages = {};
           container.html(result.content);
           pages = container.find("ul.pagination > li > a").on('click', {container: containerId, url: url}, fetchPaginatorIndex);
       } else {
           showTableError(containerId);           
       }
    }).fail(function () {
        showTableError(containerId);           
    });
}

function fetchPaginatorIndex(event)
{
    var payload = $.extend({}, changeData);
    payload.page = $(this).attr('data-page');
    payload.perPage = 10;    
    fetchPaginator(event.data.container, event.data.url, payload);
}

function fetchPaginatorFirst(event)
{
    var payload = $.extend({}, changeData);
    payload.page = 1;
    payload.perPage = 10;
    fetchPaginator(event.data.container, event.data.url, payload);
}

function initRecent()
{
    $('#show-members').on('click', {container: "#members-list", url: "/recent/members"}, fetchPaginatorFirst);
}

$(document).ready(initRecent);

