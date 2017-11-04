ScpperDB
=======================

This is a project to provide a web interface for a database containing data extracted from SCP Foundation wiki (http://www.scp-wiki.net/) and its branches.
Database itself is updated with the help of SCP web crawler.
Source code for website and web-crawler is available at https://github.com/FiftyNine?tab=repositories

Author: Alexander "FiftyNine" Krivopalov, E-mail: lixbart@mail.ru

=======================
API

Endpoint: /api/[method]

So far only the following methods are implemented

    Method: tags
    Description: retrieves up to 50 pages from the specified wiki, 
        selected using provided tags and ordered by clean rating, descended
    Type: GET
    Arguments: 
        1) "site": Short name from the list of available sites (see below)
        2) "method": How to combine provided tags for the query ("and"/"or")
            Values: 
                "and" - only pages that have all the tags (default)
                "or" - pages that contain any of the tags.
        3) "tags": List of tags, each prefixed with "+" or "-", separated by commas
            "+" indicates that pages containing this tag must be included in the query
            "-" indicates that pages containing this tag must be excluded from the query
            Each tag MUST be prefixed by only ONE of those options.
    Returns:
        JSON object containing only one field "pages", which is an array of all selected pages.
    Example request:
        http://scpper.com/api/tags?site=en&method=and&tags=%2Bscp%2C%2Bmemetic%2C-joke
        Selects up to 50 pages that have both tags "scp" and "memetic", but DO NOT have a "joke" tag
    Example response:
        {"pages":[{"name":"scp-701","title":"SCP-701","altTitle":"The Hanged King\u0027s Tragedy","status":"Original","kind":"SCP","creationDate":{"date":"2009-03-27 08:25:19.000000","timezone_type":3,"timezone":"America\/New_York"},"rating":686,"cleanRating":682,"contributorRating":162,"adjustedRating":523,"wilsonScore":0.96553260087967,"rank":29,"authors":[{"user":"tinwatchman","role":"Author"}]},{"name":"scp-1893","title":"SCP-1893","altTitle":"The Minotaur\u0027s Tale","status":"Original","kind":"SCP","creationDate":{"date":"2012-03-17 22:35:44.000000","timezone_type":3,"timezone":"America\/New_York"},"rating":625,"cleanRating":622,"contributorRating":143,"adjustedRating":426,"wilsonScore":0.96598047018051,"rank":36,"authors":[{"user":"Eskobar","role":"Author"}]}}


List of available sites:
    Site                                            API name
    SCP Foundation (scp-wiki.net):                  "en"
    Russian branch (scpfoundation.ru):              "ru"
    Korean branch (ko.scp-wiki.net):                "ko"
    Japanese branch (ja.scp-wiki.net):              "ja"
    French branch (fondationscp.wikidot.com):       "fr"
    Spanish branch (lafundacionscp.wikidot.com):    "es"
    Thai branch (scp-th.wikidot.com):               "th"
    Polish branch (scp-wiki.net.pl):                "pl"
    German branch (scp-wiki-de.wikidot.com):        "de"
    Chinese branch (scp-wiki-cn.wikidot.com):       "cn"
    Italian branch (fondazionescp.wikidot.com):     "it"